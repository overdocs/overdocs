<?php
namespace OverDocs;

require 'vendor/autoload.php';

if ($argc > 1 && $argv[1] == '--help') {
    fprintf(STDOUT, "Usage: {$argv[0]} <files to lint...>\n");
    exit;
}

if ($argc < 2) {
    $files = glob('sheets/**/*.xml');
} else {
    $files  = array_slice($argv, 1);
}

$passed = true;
$linter = new SheetLinter();

foreach ($files as $file) {
    echo "\nLinting {$file}";

    $result = $linter->lint($file);

    if (!$result['passed']) {
        $passed = false;
    } else {
        echo " OK";
        continue;
    }

    foreach ($result['messages'] as $message) {
        if ($message['line']) {
            $line = "(line {$message['line']})";
        } else {
            $line = '';
        }
        echo "\n  {$message['level']}: {$message['message']} {$line}";
    }

}
echo "\n";

if ($linter->hasErrors()) {
    exit(1);
}
