<?php
/**
 * Plugin Name: Woocommerce Address Manager
 * Description: Manage customers addresses for woocommerce
 * Plugin URI:  https://wwww.dornaweb.com
 * Version:     1.0
 * Author:      Dornaweb
 * Author URI:  https://wwww.dornaweb.com
 * License:     GPL
 * Text Domain: woocam
 * Domain Path: /languages
 * 
 * @package Woocommerce Address Manager
 */

namespace Dornaweb;

defined("ABSPATH") || exit;

@ini_set('error_reporing', E_ALL);
@ini_set('display_errors', 1);

if (!defined("WOOCAM_PLUGIN_FILE")) {
    define("WOOCAM_PLUGIN_FILE", __FILE__);
}

/**
 * Load core packages and the autoloader.
 * The SPL Autoloader needs PHP 5.6.0+ and this plugin won't work on older versions
 */
if (version_compare(PHP_VERSION, "5.6.0", ">=")) {
    require __DIR__ . "/includes/class-autoloader.php";
}

/**
 * Returns the main instance of WOOCAM.
 *
 * @since  1.0
 * @return WOOCAM\App
 */
function woocam()
{
    // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
    return WOOCAM\App::instance();
}

// Global for backwards compatibility.
$GLOBALS["woocam"] = woocam();
