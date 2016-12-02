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

    $processor_params = $processor_data['processor_params'];

    $language = strtoupper($_SESSION['settings']['cart_languageC']['value']);
    $currency = strtoupper($_SESSION['settings']['secondary_currencyC']['value']);

    $currencyCo = db_get_field("SELECT ?:currencies.coefficient FROM ?:currencies WHERE ?:currencies.currency_code = ?s", $currency);
    $price = number_format($order_info['total'] / $currencyCo, 2, '.', '');

    Tygh::$app['view']->assign('init', isset($_GET['init']) && $_GET['init'] == 1 ? 'true' : 'false');
    Tygh::$app['view']->assign('username', $processor_params['username']);
    Tygh::$app['view']->assign('langauge', $language);
    Tygh::$app['view']->assign('email', $order_info['email']);
    Tygh::$app['view']->assign('price', $price);
    Tygh::$app['view']->assign('currency', $currency);
    Tygh::$app['view']->assign('transaction', $order_id . '_' . uniqid());
    Tygh::$app['view']->assign('order_info', $order_info);
    Tygh::$app['view']->assign(
        'status_pending',
        isset($processor_params['status_pending'])?$processor_params['status_pending']:'O'
    );
    Tygh::$app['view']->assign(
        'override_callback_url',
        isset($processor_params['override_callback_url']) && $processor_params['override_callback_url']?1:0
    );
    Tygh::$app['view']->assign(
        'callback_url',
        isset($processor_params['callback_url']) && !empty($processor_params['callback_url'])
            ?$processor_params['callback_url']
            :Registry::get('config.current_location').'/payments/mtpayment/mtpayment_callback.php'
    );
}
