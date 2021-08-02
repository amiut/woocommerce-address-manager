<?php
/**
 * Bookmarks REST Controller
 *
 * @package WooCommerceWishlist
 * @since   1.0.0
 * @version 1.0.0
 */

namespace Dornaweb\WOOCAM\Rest_API\Controllers\V1;
use \Dornaweb\WOOCAM\Address_Helper;

defined('ABSPATH') || exit;

class Shipping_Helper_REST_Controller extends \Dornaweb\WOOCAM\Rest_API\REST_Controller {
    /**
     * REST Route
     */
    public $path = 'shipping-helper';

    public $methods = ['get'];

    /**
     * Get Current user Addresses
     */
    public function get($request) {
        global $current_user;

        header('Content-type: text/html');

        $packages = WC()->shipping->get_packages();

        var_dump(WC()->cart->needs_shipping());
        var_dump($packages);


        // wp_send_json_success($results);
    }

    public function permission_get() {
        return true;
    }
}
