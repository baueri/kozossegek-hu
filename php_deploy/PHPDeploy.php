<?php

namespace PHPDeploy;

use ArrayAccess;
use Error;
use Exception;
use Framework\Console\In;
use phpseclib3\Net\SFTP;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

class PHPDeploy implements ArrayAccess
{

    private string $env;

    private array $shortcuts = [
        'prepare:files' => 'prepareFiles',
        'deploy:migrate' => 'migrate',
        'deploy:composer-install' => 'composerInstall',
        'deploy:files' => 'fileDeploy',
        'sftp:send' => ['sftp', 'send'],
        'site:up' => 'up',
        'site:down' => 'down',
        'deploy:remove-old-deploy' => 'removeOldDeploy'
    ];

    private array $tasks = [];

    private string $mode = 'verbose';

    private array $configuration;

    private SFTP $sftp;

    private string $temp_deploy_dir;

    private $time;

    /**
     * @param string $env
     * @param string|null $cwd
     * @throws Exception
     */
    public function __construct(string $env, ?string $cwd = null)
    {
        $this->time = time();
        if ($cwd) {
            chdir($cwd);
        }

        $this->temp_deploy_dir = "$cwd/tmp_deploy";

        $this->env = $env;
        $this->configuration = (include "deploy_cfg.php")[$env];

        if ($this->configuration['host']) {
            $this->sftp = (new SftpBuilder())->build($this->configuration['host']);
        }
    }

    public function getTargetEnvDirName()
    {
        return $this->configuration['host']['cwd'];
    }

    public function task($callback = null, ...$args)
    {
        $this->tasks[] = [$callback, $args];

        return $this;
    }

    /**
     * @param string $mode
     * @throws Throwable
     */
    public function run($mode = 'verbose')
    {
        if ($this->isProd() && !(new In())->confirm('Biztos indítsunk deploy-t az élesre?')) {
            $this->log('megszakítva');
            return false;
        }
        $this->mode = $mode;

        $logCnt = 0;
        foreach ($this->tasks as [$task, $args]) {
            $logCnt++;
            $this->log("➤ [#{$logCnt}] Executing: {$task}");
            try {
                if (!$this->runTask($task, ...$args)) {
                    $this->error();
                    exit(1);
                } else {
                    $this->printOk('task done');
                }
            } catch (Throwable | Error $e) {
                $this->error("see error below\n\n" . static::color("↓↓↓↓↓↓↓↓↓↓", "1;31"));
                throw $e;
            }
        }

        $this->printOk('deploy success');
    }

    public function log($message)
    {
        if ($this->mode === 'verbose') {
            print("{$message}\n");
        }

        return $this;
    }

    private function runTask($task, ...$args)
    {
        if (is_string($task)) {
            if (isset($this->shortcuts[$task])) {
                $task = $this->shortcuts[$task];
            }

            if (is_array($task)) {
                return call_user_func_array([$this->{$task[0]}, $task[1]], $args);
            }

            if (method_exists($this, $task)) {
                return $this->{$task}(...$args);
            }

            $commands = explode(';', $task);
            foreach ($commands as $command) {
                exec($command, $output, $return);

                if ($return > 0) {
                    return false;
                }
            }

            return true;
        } else {
            return $task($this, $args);
        }
    }

    public function environment()
    {
        return $this->env;
    }

    private function error($msg = '')
    {
        $msg = $msg ? ": $msg" : '';
        print(static::color("×", "0;31") . " task failed{$msg}\n\n");
    }

    private static function color($text, $color)
    {
        return "\033[{$color}m{$text}\033[0m";
    }

    private function printOk($msg)
    {
        print(static::color("✔", "0;32") . " {$msg}\n\n");
    }

    public function prepareFiles(array $files)
    {
        if (file_exists($this->temp_deploy_dir)) {
            rrmdir($this->temp_deploy_dir);
        }

        mkdir($this->temp_deploy_dir, 0777, true);

        copy($this['env_file'], "{$this->temp_deploy_dir}/.env.php");

        foreach ($files as $file) {
            rcopy($file, "{$this->temp_deploy_dir}/$file", true);
        }

        rrmdir($this->temp_deploy_dir . '/public' . DS . 'storage');

        return true;
    }

    public function fileDeploy()
    {
        $dir_iterator = new RecursiveDirectoryIterator($this->temp_deploy_dir);
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

        $deployDir = $this->getDeployDir();

        $this->sftp->mkdir($deployDir);
        $files = [];
        foreach ($iterator as $file) {
            if (is_file((string) $file)) {
                $files[] = (string) $file;
            }
        }

        $total = count($files);
        $percent = 0;
        foreach ($files as $i => $filename) {
            $target = preg_replace('#^' . addslashes($this->temp_deploy_dir . '/') . '#', "", $filename);
            $this->sftp->mkdir(dirname("$deployDir/$target"), 0777, true);
            $ok = $this->sftp->put("$deployDir/$target", $filename, SFTP::SOURCE_LOCAL_FILE);
            if ($percent < (int) (($i * 100 / $total) * 0.25)) {
                $percent = (int)(($i * 100 / $total) * 0.25);

                print($ok ? '.' : 'E');
            }
        }

        print("\n\n");

        rrmdir($this->temp_deploy_dir);

        return true;
    }

    public function offsetExists($offset)
    {
        return isset($this->configuration[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->configuration[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->configuration[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->configuration[$offset]);
    }

    public function up()
    {
        $newName = "__old_" . $this->getTargetEnvDirName() . '_' . time();
        $oldFileRenamed = $this->sftp->rename($this->getTargetEnvDirName(), $newName);

        if (!$oldFileRenamed) {
            return false;
        }

        $publicPath = "/storage/{$this->getTargetEnvDirName()}/public";
        $targetPublicPath = $this->getDeployDir() . '/public/storage';

        $pubSymlinkCreated = $this->sftp->symlink($publicPath, $targetPublicPath);

        $deployDirRenamed = $this->sftp->rename($this->getDeployDir(), $this->getTargetEnvDirName());

        return $deployDirRenamed && $pubSymlinkCreated;
    }

    public function down()
    {
        if ($this->sftp->is_dir($this->getTargetEnvDirName())) {
            $this->sftp->touch("{$this->getTargetEnvDirName()}/.maintenance");
        }

        return true;
    }

    public function composerInstall($options = '')
    {
        exec("composer install {$options} -d {$this->temp_deploy_dir}", $out, $code);

        return $code == 0;
    }

    private function isProd()
    {
        return $this->env == 'production';
    }

    public function migrate()
    {
        exec("composer migrate -- --environment $this->env", $out, $code);

        return $code == 0;
    }

    private function getDeployDir()
    {
        return "{$this->getTargetEnvDirName()}_{$this->time}";
    }

    public function removeOldDeploy()
    {
        $deployDirs = $this->getDeployDirs();

        if (($c = $deployDirs->count()) > 2) {
            for ($i = 0; $i < $c - 2; $i++) {
                $this->sftp->delete($deployDirs[$i], true);
            }
        }

        return true;
    }

    private function getDeployDirs()
    {
        $folders = $this->sftp->nlist();
        $targetDir = $this->getTargetEnvDirName() . '_';
        $pattern = "/__old_{$targetDir}[0-9]+/";
        return collect($folders)
            ->filter(fn($file) => (bool) preg_match($pattern, $file))
            ->sort();
    }
}