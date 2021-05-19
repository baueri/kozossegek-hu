<?php

namespace App\Admin\Settings\Services;

use DateTime;
use DateTimeZone;
use Framework\Support\Collection;

class ErrorLogParser
{
    private string $file = ROOT . 'error.log';

    final public function getErrors(): Collection
    {
        $content = $this->getFileContent();

        return collect($content)->reverse();
    }

    final public function getLastError(): ?array
    {
        return $this->getErrors()->first();
    }

    private function getFileContent(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }
        $handle = fopen($this->file, "r");
        $errors = [];
        if ($handle) {
            $i = -1;
            $errorData = [];
            while (($line = fgets($handle)) !== false) {
                if ($line[0] == '[') {
                    if ($i > 0) {
                        $errors[$i] = $errorData;
                    }
                    $i++;
                    $errorData = ['stackTrace' => ''];
                    preg_match('/\[([^\]]+?)\].(.*)/', $line, $matches);
                    [, $dateData, $errorTitle] = $matches;
                    [$date, $time, $timezone] = explode(' ', $dateData);
                    $errorData['dateTime'] = (new DateTime("$date $time", new DateTimeZone($timezone)))
                        ->format('Y.m.d H:i:s');
                    $errorData['error'] = $errorTitle;
                    $errorData['severity'] = $this->getSeverity($errorTitle);
                    $errorData['class'] = $this->getClass($errorData['severity']);
                } else {
                    $errorData['stackTrace'] .= $line;
                }
            }
            $errors[$i] = $errorData;
        }

        return $errors;
    }

    private function getSeverity(string $line): string
    {
        if (strpos($line, "PHP Warning") !== false) {
            $severity = "WARNING";
        } elseif (strpos($line, "PHP Notice") !== false) {
            $severity = "NOTICE";
        } elseif (strpos($line, "PHP Fatal error") !== false) {
            $severity = "FATAL";
        } elseif (strpos($line, "PHP Parse error") !== false) {
            $severity = "SYNTAX_ERROR";
        } elseif (strpos($line, "Exception") !== false) {
            $severity = "EXCEPTION";
        } else {
            $severity = "UNIDENTIFIED_ERROR";
        }

        return $severity;
    }

    private function getClass(string $severity): string
    {
        if ($severity == 'WARNING') {
            $class = 'warning';
        } elseif ($severity == 'NOTICE') {
            $class = 'info';
        } elseif ($severity == 'FATAL' || $severity == 'SYNTAX_ERROR' || $severity == 'EXCEPTION') {
            $class = 'danger';
        } else {
            $class = 'secondary';
        }

        return $class;
    }
}
