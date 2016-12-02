<?php

use Tygh\Session;

defined('BOOTSTRAP') or die('Access denied');

if (!defined('PAYMENT_NOTIFICATION')) {
    $_order_id = ($order_info['repaid']) ? ($order_id . '_' . $order_info['repaid']) : $order_id;

    $processor_id = db_get_field('SELECT processor_id FROM ?:payment_processors WHERE processor = \'MisterTango\'');
    $payment_params = db_get_field('SELECT processor_params FROM ?:payments WHERE processor_id = ?i LIMIT 1', $processor_id);

    fn_change_order_status(
        $_order_id,
        isset($payment_params['status_pending'])?$payment_params['status_pending']:'O'
    );

    // Lets clear cart
    $_SESSION['cart'] = array(
        'user_data' => !empty($_SESSION['cart']['user_data']) ? $_SESSION['cart']['user_data'] : array(),
        'profile_id' => !empty($_SESSION['cart']['profile_id']) ? $_SESSION['cart']['profile_id'] : 0,
        'user_id' => !empty($_SESSION['cart']['user_id']) ? $_SESSION['cart']['user_id'] : 0,
    );
    $_SESSION['shipping_rates'] = array();
    unset($_SESSION['shipping_hash']);

    db_query(
        'DELETE FROM ?:user_session_products WHERE session_id = ?s AND type = ?s',
        Session::getId(),
        'C'
    );

    fn_redirect(fn_url("mtpayment.information?order=$_order_id&init=1"));
}
