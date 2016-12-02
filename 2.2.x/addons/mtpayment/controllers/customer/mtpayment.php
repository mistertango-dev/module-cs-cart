<?php
//
// $Id$
//

if ( !defined('AREA') ) { die('Access denied'); }

/**
 * @param $plain_text
 * @param $key
 *
 * @return string
 */
function mtpayment_hash_encrypt($plain_text, $key)
{
    $key = str_pad($key, 32, "\0");

    $plain_text = trim( $plain_text );
    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    # creates a cipher text compatible with AES (Rijndael block size = 128)
    # to keep the text confidential
    # only suitable for encoded input that never ends with value 00h (because of default zero padding)
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
        $plain_text, MCRYPT_MODE_CBC, $iv);

    # prepend the IV for it to be available for decryption
    $ciphertext = $iv . $ciphertext;

    # encode the resulting cipher text so it can be represented by a string
    $sResult = base64_encode($ciphertext);
    return trim( $sResult );
}

//
// View page details
//
if ($mode == 'information') {
    $order_id = isset($_REQUEST['order'])?$_REQUEST['order']:null;

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
    $price      = number_format($order_info['total'] / $currencyCo, 2, '.', '');

    //Check for callback options
    $overrideCallbackUrl = 0;
    $callbackUrl = null;
    if (isset($processor_params['secret_key'])) {
        if (isset($processor_params['override_callback_url']) && $processor_params['override_callback_url']) {
            $overrideCallbackUrl = 1;
        }

        $callbackUrl = Registry::get('config.current_location').'/payments/mtpayment/mtpayment_callback.php';
        if(isset($processor_params['callback_url']) && !empty($processor_params['callback_url'])) {
            $callbackUrl = $processor_params['callback_url'];
        }
        $callbackUrl = mtpayment_hash_encrypt($callbackUrl, $processor_params['secret_key']);
    }

    $view->assign('init', isset($_GET['init']) && $_GET['init'] == 1?'true':'false');
    $view->assign('username', $processor_params['username']);
    $view->assign('langauge', $language);
    $view->assign('email', $order_info['email']);
    $view->assign('price', $price);
    $view->assign('currency', $currency);
    $view->assign('transaction', $order_id.'_'.uniqid());
    $view->assign('order_info', $order_info);
    $view->assign('status_pending', isset($processor_params['status_pending'])?$processor_params['status_pending']:'O');
    $view->assign('override_callback_url', $overrideCallbackUrl);
    $view->assign('callback_url', $callbackUrl);
}
