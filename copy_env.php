<?php

global $argv;

print "környezetfüggő env változó másolása: " . $argv[1] . PHPE_EOL;
print "-----------------" . PHP_EOL;

sleep(3);

if ($argv[1] == 'development') {
	copy("_env/.env_demo.php", ".env.php");
} elseif($argv[1] == 'production') {
	copy("_env/.env_eles.php", ".env.php");
}
