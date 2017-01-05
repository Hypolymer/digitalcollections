<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

// Register Twig View helper
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => false,
        'enableAutoReload' => true,
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    // Make Session available to twig
    $view->getEnvironment()->addGlobal('session', $_SESSION);

    return $view;
};

$container['guzzle'] = function ($c) {
    $request = new \GuzzleHttp\Client();
    return $request;
};

$container['APIClient'] = function ($c) {
    $request = new \GuzzleHttp\Client(['base_uri' => $c->settings['API']['url'], 'http_errors' => FALSE]);
    return $request;
};

$container['APIRequest'] = function ($c) {
    $API = new \App\Services\APIRequest($c['logger'], $c['APIClient']);
    return $API;
};

$container['APIStream'] = function ($c) {
    $API = new \App\Services\APIStream($c['logger'], $c['APIClient']);
    return $API;
};

//Override the default Not Found Handler
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {        
        return $container['response']->withRedirect('/404');
    };
};

$container['debug'] = new PhpMiddleware\PhpDebugBar\PhpDebugBarMiddlewareFactory();
