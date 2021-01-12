<?php

// removes files and non-empty directories
use PHPDeploy\PHPDeploy;

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                rrmdir("$dir/$file");
            }
        }
        rmdir($dir);
    } elseif (file_exists($dir)) {
        unlink($dir);
    }
} 

// copies files and non-empty directories
function rcopy($src, $dst)
{
    if (file_exists($dst)) {
        rrmdir($dst);
    }
    if (is_dir($src)) {
        mkdir($dst, 0777, true);
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                rcopy("$src/$file", "$dst/$file");
            }
        }
    } elseif (file_exists($src)) {
        return copy($src, $dst);
    }
}


function php_deploy($env, $cwd = null)
{
    return new PHPDeploy($env, $cwd);
}
