<?php

namespace Framework\Console;

class In
{
    protected $stdIn = 'php://stdin';

    protected $out;

    /**
     * @param bool $mandatory
     * @return string
     */
    public function readLn($mandatory = false)
    {
        $input = null;
        while (!$input) {
            $input = fgets(STDIN);
            if ($mandatory && !trim($input)) {
                $input = null;
                continue;
            }
        }

        return trim($input);
    }

    /**
     * @return string|void
     */
    public function readLnSilent()
    {
        if (preg_match('/^win/i', PHP_OS)) {
            $vb_script = sys_get_temp_dir() . 'prompt_password.vbs';
            file_put_contents(
                $vb_script, 'wscript.echo(InputBox("'
                . '", "", "password here"))');
            $command = "cscript //nologo " . escapeshellarg($vb_script);
            $password = rtrim(shell_exec($command));
            unlink($vb_script);
            return $password;
        } else {
            $command = "/usr/bin/env bash -c 'echo OK'";
            if (rtrim(shell_exec($command)) !== 'OK') {
                trigger_error("Can't invoke bash");
                return null;
            }
            $command = "/usr/bin/env bash -c 'read -s -p \""

                . "\" mypassword && echo \$mypassword'";
            $password = rtrim(shell_exec($command));
            echo "\n";
            return $password;
        }
    }

    public function read($format = null, &...$args)
    {
        $input = null;
        while (!$input || empty(array_filter($input))) {
            $input = sscanf(trim(fgets(STDIN)), $format);
            foreach ($input as $key => $val) {
                $args[$key] = $val;
            }
        }
        return $input;
    }
}
