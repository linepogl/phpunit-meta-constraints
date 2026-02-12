<?php

declare(strict_types=1);

$xmlPath = $argv[1]; // @phpstan-ignore variable.undefined

$xml = simplexml_load_file($xmlPath);
if (false === $xml) {
    echo 'Failed to parse XML ' . $xmlPath . PHP_EOL;
    exit(1);
}
$c = intval($xml->attributes()['lines-covered']);
$t = intval($xml->attributes()['lines-valid']);
echo 'Code coverage: ' . $c . '/' . $t . ' (' . round($c / $t * 100, 5) . '%)' . PHP_EOL;
if ($c !== $t) {
    echo 'Code coverage is not 100%' . PHP_EOL;
    exit(1);
}
