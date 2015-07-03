<?php
use OverDocs\Template;
use OverDocs\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Response;

chdir('..');
require './vendor/autoload.php';

$config = require './config/application.php';
$categories = require './config/categories.php';

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app['debug'] = $config['debug'];

Template::$globals['app']          = $app;
Template::$globals['base']         = $config['base_url'];
Template::$globals['env']          = getenv('OVERDOCS_ENV') ?: 'development';
Template::$globals['analytics_id'] = $config['analytics_id'];
Template::$globals['github_url']   = $config['github_repository_url'];

require './src/services.php';

$app->get('/', function (Application $app) {
    return new Template('index', [
        'categories' => $app['index_manager']->getNonEmptyCategoryNames()
    ]);
})->bind('index');

$app->get('/about', function () {
    return new Template('about');
})->bind('about');

$app->get('/{category}', function (Application $app, $category) {
    if (!in_array($category, $app['index_manager']->read()['categories'])) {
        $app->abort(404, 'Page not found');
    }

    return new Template('category', [
        'category' => $category,
        'sheets' => $app['index_manager']->getSheetsInCategory($category),
        'names' => require './config/categories.php',
    ]);
})->bind('category');

$app->get('/keyword/{keyword}', function (Application $app, $keyword) {
    $sheets = $app['index_manager']->getSheetsWithKeyword($keyword);

    if (empty($sheets)) {
        $app->abort(404, 'Page not found');
    }

    return new Template('keyword', [
        'keyword' => $keyword,
        'sheets' => $sheets,
    ]);
})->bind('keyword');


// Catch 'em all!
$app->get('/{category}/{sheet}', function (
    Application $app, $category, $sheet
) use ($config, $categories) {
    $sheetId = $category . '/' . $sheet;

    try {
        $content = $app['sheet_cache']->read($sheetId);
        $meta = $app['index_manager']->getSheetMeta($sheetId);
    } catch (\Exception $e) {
        $app->abort(404, 'Page not found');
    }

    return new Template('sheet', [
        'content' => $content,
        'category' => $meta['category'],
        'categoryName' => $categories[$meta['category']],
        'keywords' => $meta['keywords'],
        'editOnGithubURL' => $config['github_repository_url'] . '/edit/master/sheets/' . $sheetId . '.xml',
        'title'   => $meta['title'],
        'disqusShortname' => $config['disqus_shortname'],
        'disqusIdentifier' => $meta['path'],
    ]);
})->bind('sheet');

// HTTP Error handling
$app->error(function (\Exception $e, $code) {
    if (file_exists('templates/errors/'.$code.'.php')) {
        $templateName = 'errors/'.$code;
    } else {
        $templateName = 'errors/error';
    }

    switch ($code) {
        case 400:
            $title = 'Bad request';
            $message = 'Sorry, something is wrong with your browser, try again.';
            break;
        case 403:
            $title = 'Access denied';
            $message = 'You don\'t have access to this page.';
            break;
        case 500:
            $title = 'Server error';
            $message = 'An internal server error occcured, sorry.';
            break;
        default:
            $title = 'Error';
            $message = 'We are sorry, but something went terribly wrong.';
    }

    $response = new Template($templateName, [
        'title' => $title,
        'message' => $message,
    ]);

    return new Response($response, $code);
});

$app->run();
