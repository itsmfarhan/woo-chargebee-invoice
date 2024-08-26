<?php
/**
 * Plugin Name: Woo Chargebee Invoice
 * Description: Woocommerce based chargebee invoices
 * Author: Farhan Mahmood
 * Text Domain: cb-invoice
 * Version: 1.1.1.0
 *
 * @package cb-invoice
 */

if (!defined('ABSPATH')) {
    die;
}
if (!defined('CB_PLUGIN_DIR')) {
    define('CB_PLUGIN_DIR', __DIR__);
}
if (!defined('CB_PLUGIN_DIR_URL')) {
    define('CB_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
}
require_once CB_PLUGIN_DIR . '/includes/classes/class-cb-loader.php';

if (!function_exists('init_cb_invoice_plugin')) {

    /**
     * Initiate Plugin.
     */
    function init_cb_invoice_plugin()
    {
        require_once 'vendor/autoload.php';
    }
}

init_cb_invoice_plugin();
