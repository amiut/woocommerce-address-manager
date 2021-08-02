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
use \Dornaweb\WOOCAM\Data_Exception;
use \Dornaweb\WOOCAM\REST_Exception;

defined('ABSPATH') || exit;

class Addresses_REST_Controller extends \Dornaweb\WOOCAM\Rest_API\REST_Controller {
    /**
     * REST Route
     */
    public $path = 'addresses';

    public $methods = ['GET', 'POST'];

    public $one_methods = ["DELETE"];

    /**
     * Get Current user Addresses
     */
    public function get() {
        global $current_user;

        $addresses = Address_Helper::get_user_addresses($current_user->ID);
        wp_send_json_success([
            'addresses' => array_map(['\\Dornaweb\\WOOCAM\\Address_Helper', 'format_address_for_REST'], $addresses)
        ]);
    }

    /**
     * Get Current user Addresses
     */
    public function delete_one($request) {
        try {
            global $current_user;

            $address_id = absint($request->get_param('id'));

            $address = Address_Helper::get_user_address($current_user->ID, $address_id);

            if (!$address) {
                throw new REST_Exception("delete_address_address_not_found", __('Address Not found', 'woocam'), 400);
            }

            $address->delete(true);
            wp_send_json_success([
                'message'   => __("Address removed", "woocam")
            ]);
        } catch (REST_Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
                'where'   => $e->getErrorData('where')
            ], $e->getCode());

        } catch (Data_Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
                'where'   => $e->getErrorData('where')
            ], $e->getCode());

        }


        wp_send_json_success([
            'addresses' => array_map(['\\Dornaweb\\WOOCAM\\Address_Helper', 'format_address_for_REST'], $addresses)
        ]);
    }

    public function post($request) {
        global $current_user;

        $fields = [
            'title'         => '',
            'is_default'    => 0,
            'lat'           => '',
            'lng'           => '',
            'first_name'    => '',
            'last_name'     => '',
            'company'       => '',
            'address1'      => '',
            'address2'      => '',
            'city'          => '',
            'state'         => '',
            'country'       => WC()->countries->get_base_country(),
            'postcode'      => '',
            'phone'         => '',
        ];

        try {

            foreach ($fields as $key => $field) {
                switch ($key) {
                    case 'phone':
                        if (!trim($request->get_param('phone'))) {
                            throw new REST_Exception("add_user_address_phone_not_entered", __('Please enter recipient\'s phone number', 'woocam'), 400, ['where' => 'phone']);
                        }

                        if (!ctype_digit($request->get_param('phone')) || strlen($request->get_param('phone')) !== 11) {
                            throw new REST_Exception("add_user_address_phone_not_valid", __('recipient\'s phone number is not valid', 'woocam'), 400, ['where' => 'phone']);
                        }

                        $fields[$key] = $request->get_param($key);
                        break;


                    case 'first_name':
                    case 'last_name':
                        if (!trim($request->get_param('first_name'))) {
                            throw new REST_Exception("add_user_address_first_name_not_entered", __('Please enter recipient\'s name', 'woocam'), 400, ['where' => 'first_name']);
                        }

                        if (!trim($request->get_param('last_name'))) {
                            throw new REST_Exception("add_user_address_last_name_not_entered", __('Please enter recipient\'s last name', 'woocam'), 400, ['where' => 'last_name']);
                        }

                        if (strlen(trim($request->get_param('first_name'))) < 2) {
                            throw new REST_Exception("add_user_address_first_name_not_valid", __('Please enter a valid recipient\'s name', 'woocam'), 400, ['where' => 'first_name']);
                        }

                        if (strlen(trim($request->get_param('last_name'))) < 2) {
                            throw new REST_Exception("add_user_address_last_name_not_valid", __('Please enter a valid recipient\'s last name', 'woocam'), 400, ['where' => 'last_name']);
                        }

                        $fields[$key] = $request->get_param($key);

                        break;

                    case 'city':
                    case 'state':
                        if (!trim($request->get_param('state'))) {
                            throw new REST_Exception("add_user_address_state_not_entered", __('Please enter recipient\'s state', 'woocam'), 400, ['where' => 'state']);
                        }

                        if (!trim($request->get_param('city'))) {
                            throw new REST_Exception("add_user_address_city_not_entered", __('Please enter recipient\'s city', 'woocam'), 400, ['where' => 'city']);
                        }

                        $fields[$key] = $request->get_param($key);

                        break;

                    case 'postcode':
                        if (!trim($request->get_param('postcode'))) {
                            throw new REST_Exception("add_user_address_postcode_not_entered", __('Please enter recipient\'s postcode', 'woocam'), 400, ['where' => 'postcode']);
                        }

                        if (!ctype_digit($request->get_param('postcode')) || strlen($request->get_param('postcode')) !== 10) {
                            throw new REST_Exception("add_user_address_postcode_not_valid", __('recipient\'s postcode is not valid', 'woocam'), 400, ['where' => 'postcode']);
                        }

                        $fields[$key] = $request->get_param($key);
                        break;

                    default:
                        $fields[$key] = $request->get_param($key);
                }
            }

            wp_send_json_success([
                'message'   => 'آدرس ذخیره شد',
                'address'   => Address_Helper::format_address_for_REST(Address_Helper::add_user_address($current_user->ID, $fields))
            ]);

        } catch (REST_Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
                'where'   => $e->getErrorData('where')
            ], $e->getCode());

        } catch (Data_Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
                'where'   => $e->getErrorData('where')
            ], $e->getCode());

        }
    }

    public function permission_get() {
        return is_user_logged_in();
    }

    public function permission_delete_one() {
        return is_user_logged_in();
    }

    public function permission_post() {
        return is_user_logged_in();
    }
}
