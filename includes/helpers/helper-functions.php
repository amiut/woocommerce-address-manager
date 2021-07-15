<?php
/**
 * Helper functions
 * 
 */

if (!function_exists('woocam_add_address')) {
    function woocam_add_address($data) {
        $address = new \Dornaweb\WOOCAM\Address();
        $address->set_props($data);
        $address->save();
        return $address;
    }
}

if (!function_exists('woocam_get_addresses')) {
    function woocam_get_addresses($customer_id = 0) {
        if (!$customer_id) {
            $customer_id = get_current_user_id();
        }

        $data_store = \Dornaweb\WOOCAM\Data_Store::load('address');
        return $data_store->get_addresses([
            'user_id' => $customer_id
        ]);
    }
}