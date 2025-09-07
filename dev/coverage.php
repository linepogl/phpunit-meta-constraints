<?php

declare(strict_types=1);

$xml = simplexml_load_file($argv[1]);
if (false === $xml) {
    echo 'Failed to parse XML ' . $argv[1] . PHP_EOL;
    exit(1);
}
$c = intval($xml->attributes()['lines-covered']);
$t = intval($xml->attributes()['lines-valid']);
echo 'Code coverage: ' . $c . '/' . $t . ' (' . round($c / $t * 100, 5) . '%)' . PHP_EOL;
if ($c !== $t) {
    echo 'Code coverage is not 100%' . PHP_EOL;
    exit(1);
}
