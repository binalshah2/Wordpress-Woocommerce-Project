<?php
/**
 * The PlugnPay PHP SDK. Include this file in your project.
 *
 * @package PlugnPay
 */
require dirname(__FILE__) . '/lib/shared/PlugnPay_Request.php';
require dirname(__FILE__) . '/lib/shared/PlugnPay_Response.php';
require dirname(__FILE__) . '/lib/PlugnPay.php';

/**
 * Exception class for PlugnPay PHP SDK.
 *
 * @package PlugnPay
 */
class PlugnPay_Exception extends Exception {

}