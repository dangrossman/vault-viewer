<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array(
    'debug' => false,
    'view' => new CustomView(),
    'log.enabled' => false
));

$app->get('/gateways', function() use ($app) {
    $app->render('gateways.php');
});

$app->get('/gateways.json(/:since)', function($since = '') use ($app) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $gateways = $spreedly->getGateways($since);
    $res->write(json_encode($gateways));
});

$app->get('/payment_methods', function() use ($app) {
    $app->render('payment_methods.php');
});

$app->get('/payment_methods.json(/:since)', function($since = '') use ($app) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $payment_methods = $spreedly->getPaymentMethods($since);
    $res->write(json_encode($payment_methods));
});

$app->get('/payment_method/:payment_method_token/transactions.json', function($payment_method_token) use ($app) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $transactions = $spreedly->getTransactions($payment_method_token);
    $res->write(json_encode($transactions));
});

// POST route
$app->post('/post', function () {
    echo 'This is a POST route';
});

$app->run();