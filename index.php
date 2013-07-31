<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

//Initialize Slim application

$app = new \Slim\Slim(array(
    'debug' => false,
    'view' => new CustomView(),
    'log.enabled' => false
));

//Initialize Spreedly Core API wrapper with access credentials


try {
    $spreedly = new Spreedly($login, $secret);
} catch (Exception $e) {
    die($e->getMessage());
}

//Routes

$app->get('/', function() use ($app, $login, $secret) {
    if (!empty($login) && !empty($secret)) {
        $app->redirect('/gateways');
    }
});

$app->get('/gateways', function() use ($app) {
    $app->render('gateways.php');
});

$app->get('/gateways.json(/:since)', function($since = '') use ($app, $spreedly) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $gateways = $spreedly->getGateways($since);
    $res->write(json_encode($gateways));
});

$app->get('/gateway/:gateway_token/redact.json', function($gateway_token) use ($app, $spreedly) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $response = $spreedly->redactGateway($gateway_token);
    $res->write(json_encode(array('response' => $response)));
});

$app->get('/payment_methods', function() use ($app) {
    $app->render('payment_methods.php');
});

$app->get('/payment_methods.json(/:since)', function($since = '') use ($app, $spreedly) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $payment_methods = $spreedly->getPaymentMethods($since);
    $res->write(json_encode($payment_methods));
});

$app->get('/payment_method/:payment_method_token/redact.json', function($payment_method_token) use ($app, $spreedly) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $response = $spreedly->redactPaymentMethod($payment_method_token);
    $res->write(json_encode(array('response' => $response)));
});

$app->get('/payment_method/:payment_method_token', function($payment_method_token) use ($app) {
    $app->render('payment_method.php', array('payment_method_token' => $payment_method_token));
});

$app->get('/payment_method/:payment_method_token/transactions.json(/:since)', function($payment_method_token, $since = '') use ($app, $spreedly) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $transactions = $spreedly->getTransactions($payment_method_token, $since);
    $res->write(json_encode($transactions));
});

// Run Slim application

$app->run();