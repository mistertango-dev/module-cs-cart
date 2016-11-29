<?php

if (!defined('AREA')) {
    die('Access denied');
}

if (!defined('PAYMENT_NOTIFICATION')) {
    $_order_id = ($order_info['repaid']) ? ($order_id . '_' . $order_info['repaid']) : $order_id;

    $url      = (strpos($_SERVER['SERVER_PROTOCOL'], 'HTTPS') !== false ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $url      = strtr($url, array('index.php' => 'index.php?dispatch=mtpayment.information&order='.$_order_id.'&init=1'));

    // Change order status to open as everything is automatic
    fn_change_order_status($_order_id, 'P');

    // Lets clear cart
    $_SESSION['cart'] = array(
        'user_data' => !empty($_SESSION['cart']['user_data']) ? $_SESSION['cart']['user_data'] : array(),
        'profile_id' => !empty($_SESSION['cart']['profile_id']) ? $_SESSION['cart']['profile_id'] : 0,
        'user_id' => !empty($_SESSION['cart']['user_id']) ? $_SESSION['cart']['user_id'] : 0,
    );
    $_SESSION['shipping_rates'] = array();
    unset($_SESSION['shipping_hash']);

    db_query('DELETE FROM ?:user_session_products WHERE session_id = ?s AND type = ?s', Session::get_id(), 'C');

    fn_redirect($url);
}
