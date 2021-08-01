<?php
/**
 * Woocam Address class
 *
 * @package WOOCAM
 * @since   1.0
 */

namespace Dornaweb\WOOCAM;

defined('ABSPATH') || exit;

/**
 * WOOCAM Address class
 */
class Address_Helper
{
    public static function get_user_addresses($user_id = 0) {
        if (!$user_id) {
            if (!is_user_logged_in()) throw new Data_Exception("get_user_addresses_not_loggedin", __('You must be logged in', 'woocam'), 400);
            $user_id = get_current_user_id();
        }

        $data_store = Data_Store::load('address');

        $addresses = $data_store->get_addresses([
            'user_id'   => absint($user_id),
        ]);

        return $addresses;
    }

    public static function add_user_address($user_id, $data = []) {
        $data = wp_parse_args(
            $data,
            array(
                'title'         => '',
                'user_id'       => absint($user_id),
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
            )
        );

        if (!$data['user_id']) {
            throw new Data_Exception("add_user_address_user_id_empty", __('User id cannot be empty', 'woocam'), 400);
        }

        $required_fields = apply_filters('woocam_add_address_required_fields', ['city', 'state', 'country', 'postcode']);

        foreach ($required_fields as $required_field) {
            if (empty($data[$required_field])) {
                throw new Data_Exception("add_user_address_{$required_field}_empty", sprintf(__('`%s` cannot be empty', 'woocam'), $required_field), 400);
            }
        }

        if (!empty($data['phone']) && !ctype_digit($data['phone'])) {
            throw new Data_Exception("add_user_address_invalid_phone", sprintf(__('Phone number is invalid', 'woocam'), $required_field), 400);
        }

        $address = new Address();
        $address->set_props($data);
        $address->save();
        return $address;
    }

    public static function format_address_for_REST($address) {
        return [
            'title'         => $address->get_title(),
            'user_id'       => $address->get_user_id(),
            'is_default'    => $address->is_default(),
            'lat'           => $address->get_lat(),
            'lng'           => $address->get_lng(),
            'first_name'    => $address->get_first_name(),
            'last_name'     => $address->get_last_name(),
            'company'       => $address->get_company(),
            'address1'      => $address->get_address1(),
            'address2'      => $address->get_address2(),
            'city'          => $address->get_city(),
            'state'         => $address->get_state(),
            'country'       => $address->get_country(),
            'postcode'      => $address->get_postcode(),
            'phone'         => $address->get_phone(),
        ];
    }
}
