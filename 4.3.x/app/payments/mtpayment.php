<?php

defined('BOOTSTRAP') or die('Access denied');

if (!defined('PAYMENT_NOTIFICATION')) {
    $_order_id = ($order_info['repaid']) ? ($order_id . '_' . $order_info['repaid']) : $order_id;

    $psData = fn_get_payment_method_data($_SESSION['cart']['payment_id']);

    // Change order status to open as everything is automatic
    fn_change_order_status($_order_id, STATUSES_ORDER);

    echo '<meta http-equiv="refresh" content="0;url='.fn_url("mtpayment.information?order=$_order_id&init=1").'">';

    fn_start_payment($_order_id, false);
}
