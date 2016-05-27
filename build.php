<?php

use OverDocs\SheetParser;
use Pimple\Container;

if (PHP_SAPI !== 'cli') {
    die("This script is meant to be run using CLI\n");
}

set_time_limit(0);

$counterStart = microtime(true);

require 'vendor/autoload.php';

$config = require 'config/application.php';

// Initialize all services in the container
$app = new Container();
$debug = $app['debug'] = isset($argv[1]) && $argv[1] === '--debug' || $config['debug'];
require 'src/services.php';

// Initialize index storage
$index = [];

// Clear sheet cache directory
$app['sheet_cache']->clear();

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('sheets/'));

$iterator->rewind();
while ($iterator->valid()) {
    if (!$iterator->isDot()) {
        $path = str_replace(['\\', '.xml'], ['/', ''], $iterator->getSubPathname());

        $sheet = new SheetParser($path, $iterator->getPathname(), $config, $debug);
        $meta = $sheet->parseMeta();
        $content = $sheet->parseContent();

        $index['sheets'][$path] = [
            'title' => $meta->title,
            'summary' => $meta->summary,
            'keywords' => $meta->keywords,
            'category' => $iterator->getSubPath(),
            'path' => $path,
        ];

        $index['categories'][] = $iterator->getSubPath();

        $app['sheet_cache']->write($path, $content);

        unset($sheet, $path, $meta, $content);
    }

    $iterator->next();
}

$index['categories']     = array_unique($index['categories']);
$index['category_names'] = require 'config/categories.php';

// Write index in JSON format
if ($debug) {
    $jsonFlags = JSON_PRETTY_PRINT;
} else {
    $jsonFlags = null;
}

$app['index_manager']->write($index, $jsonFlags);

$counterResult = round(microtime(true) - $counterStart, 3);
echo "Sheets built in $counterResult seconds\n";

exit(0);
