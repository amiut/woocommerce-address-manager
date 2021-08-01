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

class Address_Helper_REST_Controller extends \Dornaweb\WOOCAM\Rest_API\REST_Controller {
    /**
     * REST Route
     */
    public $path = 'address-helper';

    public $methods = ['get'];

    /**
     * Get Current user Addresses
     */
    public function get($request) {
        global $current_user;

        $country = !empty($request->get_param('country')) ? $request->get_param('country') : WC()->countries->get_base_country();

        $results = [
            'base_country'  => $country,
            'countries'     => [],
            'states'        => [],
            'cities'        => [],
        ];

        if (is_array(WC()->countries->get_states($country))) {
            foreach (WC()->countries->get_states($country) as $state => $label) {
                $results['states'][] = [
                    'code'  => $state,
                    'name'  => $label
                ];
            }
        }

        foreach (WC()->countries->get_allowed_countries() as $code => $name) {
            $results['countries'][] = [
                'code'  => $code,
                'name'  => $name,
            ];
        }

        wp_send_json_success($results);
    }

    public function permission_get() {
        return true;
    }
}
