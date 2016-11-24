<?php

if (!defined('AREA')) {
    die('Access denied');
}

if (!defined('PAYMENT_NOTIFICATION')) {
    $_order_id = ($order_info['repaid']) ? ($order_id . '_' . $order_info['repaid']) : $order_id;

    $url      = (strpos($_SERVER['SERVER_PROTOCOL'], 'HTTPS') !== false ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $url      = strtr($url, array('index.php' => 'index.php?dispatch=mtpayment.information&order='.$_order_id.'&init=1'));

    $psData = fn_get_payment_method_data($_SESSION['cart']['payment_id']);

    echo '<meta http-equiv="refresh" content="0;url='.$url.'">';

    fn_start_payment($_order_id, false);
}
