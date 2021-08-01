<?php
/**
 * Bookmarks REST Controller
 *
 * @package WooCommerceWishlist
 * @since   1.0.0
 * @version 1.0.0
 */

namespace Dornaweb\CustomerRewards\Rest_API\Controllers\V1;

defined('ABSPATH') || exit;

class Swap_REST_Controller extends \Dornaweb\CustomerRewards\Rest_API\REST_Controller {
    /**
     * REST Route
     */
    public $path = 'addresses';

    public $methods = ['get', 'post'];

    public function get() {

    }

    public function post($request) {
        global $current_user;

    }

    public function permission_get() {
        return is_user_logged_in();
    }

    public function permission_post() {
        return is_user_logged_in();
    }
}
