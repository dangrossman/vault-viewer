<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
session_start();

//Initialize Slim application

$app = new \Slim\Slim(array(
    'debug' => false,
    'view' => new CustomView(),
    'log.enabled' => false
));

//Initialize Spreedly Core API wrapper with access credentials

$key = '';
$secret = '';
$logged_in = false;

if (!empty($_SESSION['login']) && !empty($_SESSION['secret'])) {
    $key = $_SESSION['login'];
    $secret = $_SESSION['secret'];
}

$spreedly = null;
if (empty($_POST)) {
    try {
        $spreedly = new Spreedly($key, $secret);
        $logged_in = true;
    } catch (Exception $e) {
        $logged_in = false;
    }
}

//Routes

$app->get('/', function() use ($app, $logged_in) {
    if ($logged_in) {
        $app->redirect('/gateways');
    } else {
        $app->render('login.php');
    }
});

$app->post('/', function() use ($app) {
    $req = $app->request();
    $key = $req->post('key');
    $secret = $req->post('secret');
    $_SESSION['login'] = $key;
    $_SESSION['secret'] = $secret;
    $app->redirect('/');
});

$app->get('/logout', function() use ($app) {
    session_destroy();
    $app->redirect('/');
});

$app->get('/gateways', function() use ($app, $logged_in) {
    if (!$logged_in) $app->redirect('/');
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

$app->get('/payment_methods', function() use ($app, $logged_in) {
    if (!$logged_in) $app->redirect('/');
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