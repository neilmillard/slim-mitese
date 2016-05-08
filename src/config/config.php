<?php
$dot_env = new \Dotenv\Dotenv(__DIR__ . '/../../');

if(getenv('APP_ENV') === 'development') {
    $dot_env->load();
    $displayErrorDetails = true;
} else {
    $displayErrorDetails = false;
}
$dot_env->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

return [

    'displayErrorDetails' => $displayErrorDetails,
    // View settings
    'view' => [
        'template_path' => __DIR__ . '/../templates',
        'twig' => [
            'cache' => __DIR__ . '/../../cache/twig',
            'debug' => true,
            'auto_reload' => true,
        ],
    ],

    // monolog settings
    'logger' => [
        'name' => 'app',
        'path' => __DIR__ . '/../../log/app.log',
    ],

    // database settings
    'database' => [
        'driver'    => 'mysql',
        'host'      => $_ENV['DB_HOST'],
        'database'  => $_ENV['DB_NAME'],
        'username'  => $_ENV['DB_USER'],
        'password'  => $_ENV['DB_PASS'],
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => '',
        'frozen'    => false,
    ],

    // authentication settings
    'authenticator' => [
        'tablename' => 'users',
        'usernamefield' => 'name',
        'credentialfield' => 'hash'
    ]
];