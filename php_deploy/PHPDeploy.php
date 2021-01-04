<?php

include "SFtpDeploy.php";

class PHPDeploy
{

    private string $env;

    private array $runnables = [
        'sftp:touch' => ['sftp', 'touch']
    ];

    private array $tasks = [];

    private string $mode = 'verbose';

    private $configuration = [];

    private SFtpDeploy $sftp;

    public function __construct(string $env, ?string $cwd = null)
    {
        if ($cwd) {
            chdir($cwd);
        }

        $this->env = $env;
        $this->configuration = (include "deploy_cfg.php")[$env];
        if ($this->configuration['host']) {
            $this->sftp = new SFtpDeploy($this->configuration['host']);
        }
    }

    public function task($name, $callback = null, $args = null)
    {
        $this->tasks[$name] = [$callback, $args];
        return $this;
    }

    /**
     * @param string $mode
     * @return bool
     * @throws Throwable
     */
    public function run($mode = 'verbose')
    {
        $this->mode = $mode;

        $logCnt = 0;
        foreach ($this->tasks as $name => [$task, $args]) {
            $logCnt++;
            $this->log("➤ [#{$logCnt}] Executing: {$name}");
            try {
                if (!$this->runTask($name, $task, $args)) {
                    $this->error();
                    exit;
                } else {
                    $this->print_done();
                }
            } catch (\Throwable|\Error $e) {
                $this->error("see error below\n\n" . static::color("↓↓↓↓↓↓↓↓↓↓", "1;31"));
                throw $e;
            }
        }

        return true;
    }

    private function runTask($name, $task, $args = null)
    {

        if (!$task) {
            exec($name, $output, $return);
            return $return == 0;
        }

        if (is_string($task)) {
            if (isset($this->runnables[$task])) {
                $task = $this->runnables[$task];
                return call_user_func([$this->{$task[0]}, $task[1]], $args);
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
            return $task($this, $this->env, $args);
        }
    }

    private function error($msg = '')
    {
        $msg = $msg ? ": $msg" : '';
        print(static::color("×", "0;31") . " task failed{$msg}\n\n");
    }

    private function print_done()
    {
        print(static::color("✔", "0;32") . " task done\n\n");
    }

    private static function color($text, $color)
    {
        return "\033[{$color}m{$text}\033[0m";
    }

    public function log($message)
    {
        if ($this->mode === 'verbose') {
            print("{$message}\n");
        }
    }
}

function php_deploy($env, $cwd = null)
{
    return new PHPDeploy($env, $cwd);
}
