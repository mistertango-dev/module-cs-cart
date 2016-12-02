<?php

if (!defined('AREA')) {
    die('Access denied');
}

if (!defined('PAYMENT_NOTIFICATION')) {
    $_order_id = ($order_info['repaid']) ? ($order_id . '_' . $order_info['repaid']) : $order_id;

    $processor_data = fn_get_processor_data($order_info['payment_method']['payment_id']);
    $processor_params = $processor_data['params'];
    $status_pending = isset($processor_params['status_pending'])?$processor_params['status_pending']:'O';

    fn_change_order_status($_order_id, $status_pending);

    // Lets clear cart
    $_SESSION['cart'] = array(
        'user_data' => !empty($_SESSION['cart']['user_data']) ? $_SESSION['cart']['user_data'] : array(),
        'profile_id' => !empty($_SESSION['cart']['profile_id']) ? $_SESSION['cart']['profile_id'] : 0,
        'user_id' => !empty($_SESSION['cart']['user_id']) ? $_SESSION['cart']['user_id'] : 0,
    );
    $_SESSION['shipping_rates'] = array();
    unset($_SESSION['shipping_hash']);

    db_query('DELETE FROM ?:user_session_products WHERE session_id = ?s AND type = ?s', Session::get_id(), 'C');

    fn_redirect(fn_url("mtpayment.information?order=$_order_id&init=1"));
}
