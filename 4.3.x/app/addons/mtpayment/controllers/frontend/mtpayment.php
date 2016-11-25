<?php

if (!defined('AREA')) {
    die('Access denied');
}

if ($mode == 'information') {
    $order_id = isset($_REQUEST['order']) ? $_REQUEST['order'] : null;

    if ($order_id === null) {
        exit;
    }

    $order_info = fn_get_order_info($order_id);

    $processor_data = fn_get_processor_data($order_info['payment_method']['payment_id']);

    if ($processor_data['processor'] != 'MisterTango') {
        exit;
    }

    $processor_params = $processor_data['params'];

    $language = strtoupper($_SESSION['settings']['cart_languageC']['value']);
    $currency = strtoupper($_SESSION['settings']['secondary_currencyC']['value']);

    $currencyCo = db_get_field("SELECT ?:currencies.coefficient FROM ?:currencies WHERE ?:currencies.currency_code = ?s", $currency);
    $price = number_format($order_info['total'] / $currencyCo, 2, '.', '');

    $view->assign('init', isset($_GET['init']) && $_GET['init'] == 1 ? 'true' : 'false');
    $view->assign('username', $processor_params['username']);
    $view->assign('langauge', $language);
    $view->assign('email', $order_info['email']);
    $view->assign('price', $price);
    $view->assign('currency', $currency);
    $view->assign('transaction', $order_id . '_' . uniqid());
    $view->assign('order_info', $order_info);
}
