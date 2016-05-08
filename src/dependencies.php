<?php
// set the timezone
date_default_timezone_set('Europe/London');

// DIC configuration
/**
 * @var \Slim\Container $container
 */
$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    /** @var \Slim\Container $c */
    $view = new \Slim\Views\Twig(
        $c->settings['view']['template_path'],
        $c->settings['view']['twig']
    );
    $view->addExtension(new Twig_Extension_Debug());
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

// Flash messages
$container['flash']= function () {
    return new \Slim\Flash\Messages();
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->settings['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

$container['dsn'] = function ($c) {
    $settings = $c->settings['database'];
    $dsn = $settings['driver'] .
        ':host=' . $settings['host'] .
        ((!empty($settings['port'])) ? (';port=' . $settings['port']) : '') .
        ';dbname=' . $settings['database'];
    return $dsn;
};

\RedBeanPHP\R::setup(   $container['dsn'],
                        $container->settings['database']['username'],
                        $container->settings['database']['password'],
                        $container->settings['database']['frozen']
);

// database mysqli connection
$container['database'] = function ($c) {
    $settings = $c->settings['database'];
    $connection = new \PDO($c['dsn'],$settings['username'],$settings['password']);
    return $connection;
};

// authentication
$container['authenticator'] = function ($c) {
    $settings = $c->settings['authenticator'];
    $connection = $c['database'];
    $adapter = new \App\Authentication\Adapter\Db\EloAdapter(
        $connection,
        $settings['tablename'],
        $settings['usernamefield'],
        $settings['credentialfield']
    );
    $authenticator = new \App\Authentication\Authenticator($adapter);
    return $authenticator;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container['App\Action\HomeAction'] = function ($c) {
    return new App\Action\HomeAction($c['view'], $c['logger'], $c['router']);
};

$container['App\Action\PageAction'] = function ($c) {
    return new App\Action\PageAction($c['view'], $c['logger'], $c['router'], $c['flash']);
};

$container['App\Action\ProfileAction'] = function ($c) {
    return new App\Action\ProfileAction($c['view'], $c['logger'], $c['router'], $c['flash'], $c['authenticator']);
};

$container['App\Action\AdminAction'] = function ($c) {
    return new App\Action\AdminAction($c['view'], $c['logger'], $c['router'], $c['flash'], $c['authenticator']);
};

$container['App\Action\UserAction'] = function ($c) {
    return new App\Action\UserAction($c['view'], $c['logger'], $c['router'], $c['flash'], $c['authenticator']);
};

$container['App\Action\LoginAction'] = function ($c) {
    return new App\Action\LoginAction($c['view'], $c['logger'], $c['router'], $c['authenticator'], $c['flash']);
};
