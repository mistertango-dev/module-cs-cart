<?php

if (!defined('AREA')) {
    die('Access denied');
}

/**
 * We need to overcome redirects on placement routines function
 *
 * @throws MisterTangoOrderPlacementRoutinesException
 */
function fn_mtpayment_order_placement_routines()
{
    // Throw exception only if its MisterTango scope call
    if (class_exists('MisterTangoOrderPlacementRoutinesException')) {
        throw new MisterTangoOrderPlacementRoutinesException();
    }
}
