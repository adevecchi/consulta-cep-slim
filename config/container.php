<?php

$container = $app->getContainer();

// Activating routes in a subfolder
$container['environment'] = function (): \Slim\Http\Environment {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);
    return new \Slim\Http\Environment($_SERVER);
};

// Register Twig View helper
$container['view'] = function (\Slim\Container $c): \Slim\Views\Twig {
    $settings = $c->get('settings');
    $viewPath = $settings['twig']['path'];

    $twig = new \Slim\Views\Twig($viewPath, [
        'cache' => $settings['twig']['cache_enabled'] ? $settings['twig']['cache_path'] : false
    ]);
    
    $loader = $twig->getLoader();
    $loader->addPath($settings['public'], 'public');

    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment($c->get('environment'));
    $twig->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $twig;
};

$container['notFoundHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response $response) use ($c): \Slim\Http\Response {
        return $c['view']->render($response->withStatus(404), '404.html', [
            'notFound' => '404 - Page Not Found'
        ]);
    };
};