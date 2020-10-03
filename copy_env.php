<?php

global $argv;

if ($argv[1] == 'development') {
	copy("_env/.env_demo.php", ".env.php");
} elseif($argv[1] == 'production') {
	copy("_env/.env_eles.php", ".env.php");
}
