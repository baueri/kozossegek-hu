<?php

global $argv;


$environments = [
	'development' => "_env/.env_demo.php",
	'production' => "_env/.env_eles.php"
];

if (!isset($argv[1]) || !isset($environments[$argv[1]])) {
	print "hibás környezeti változó!" . PHP_EOL;
	exit(1);
}

$file = $environments[$argv[1]];

print "környezetfüggő env változó másolása: " . $argv[1] . PHP_EOL;
print "-----------------" . PHP_EOL;

sleep(1);


if (!copy($file, ".env.php")) {
	print "Nem sikerült átmásolni a fájlt!" . PHP_EOL;
	exit(1);
}

 print "Sikeres fájl másolás" . PHP_EOL;
