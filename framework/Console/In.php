<?php

namespace Framework\Console;

class In
{
    public function readLn(bool $mandatory = false): string
    {
        $input = null;
        while (!$input) {
            $input = fgets(STDIN);
            if ($mandatory && !trim($input)) {
                $input = null;
            }
        }

        return trim($input);
    }

    public function readLnSilent(): ?string
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
        }
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

    public function confirm(string $question, $accept = 'y'): bool
    {
        Out::writeln("$question [y/n]:");

        $this->read("%s", $answer);

        return $answer === $accept;
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

    public function ask(string $question, string $default = '', bool $mandatory = false): string
    {
        Out::write($question);
        if ($default) {
            Out::write(" [{$default}]", Color::yellow);
        }
        Out::write(' ');
        return $this->readLn($mandatory) ?: $default;
    }

    public function askIf(string $question, string $default = '', bool $mandatory = false): bool
    {
        $true = ['y', 'yes', 'igen', 'i', '1'];
        Out::write($question);
        if ($default) {
            Out::write(" [{$default}]", Color::yellow);
        }
        Out::write(' ');
        return in_array(mb_strtolower($this->readLn($mandatory) ?: $default), $true);
    }
}
