<?php
use OverDocs\IndexManager;
use OverDocs\SheetLinter;
use OverDocs\SheetCache;

$app['sheet_cache'] = function () {
    return new SheetCache('./cache');
};

$app['index_manager'] = function () {
    return new IndexManager();
};

$app['sheet_linter'] = function () {
    return new SheetLinter();
};
