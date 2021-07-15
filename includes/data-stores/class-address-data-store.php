<?php
/**
 * Woocam Address class
 *
 * @package WOOCAM
 * @since   1.0
 */

namespace Dornaweb\WOOCAM\Data_Stores;

defined('ABSPATH') || exit;

class Address_Data_Store implements \Dornaweb\WOOCAM\Interfaces\Address_Data_Store_Interface {
    public function create(&$address) {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . 'woocam_addresses',
            [
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
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );

        $address_id = absint( $wpdb->insert_id );
		return $address_id;
    }

    /**
	 * Read a address item from the database.
	 *
	 * @since 3.0.0
	 *
	 * @param WC_Order_Item $address address object.
	 *
	 * @throws Exception If invalid address.
	 */
	public function read( &$address ) {
		global $wpdb;

		$address->set_defaults();

        $fields = ['user_id', 'first_name', 'last_name', 'title', 'phone', 'lat', 'lng', 'address1', 'address2', 'city', 'state', 'country', 'postcode', 'phone'];

		$data = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT ". implode(', ', $fields) ." FROM {$wpdb->prefix}woocam_addresses WHERE ID = %d LIMIT 1;",
                $address->get_id()
            )
        );

		if ( ! $data ) {
			throw new Exception( __( 'Invalid order item.', 'woocommerce' ) );
		}

		$address->set_props(
            array_combine(
                $fields,
                array_map(function($field) use($data) {
                    return $data->$field;
                }, $fields)
            )
		);
        $address->set_object_read( true );
	}

    /**
	 * Update an address.
	 *
	 * @since 3.3.0
	 * @param Address $address Address instance.
	 */
	public function update( &$address ) {
        global $wpdb;

		$changes = $webhook->get_changes();

        $data = [
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

        $wpdb->update(
			$wpdb->prefix . 'woocam_addresses',
			$data,
			array(
				'ID' => $address->get_id(),
			)
		); // WPCS: DB call ok.

		$address->apply_changes();
    }


	/**
	 * Remove an address from the database.
	 *
	 * @since 3.3.0
	 * @param Address $address      Address instance.
	 */
	public function delete( &$address ) {
		global $wpdb;

		$wpdb->delete(
			$wpdb->prefix . 'woocam_addresses',
			array(
				'webhook_id' => $address->get_id(),
			),
			array( '%d' )
		); // WPCS: cache ok, DB call ok.
	}

    /**
	 * Get a address object.
	 *
	 * @param  array $data From the DB.
	 * @return \Dornaweb\WOOCAM\Address
	 */
	private function get_address( $data ) {
        if ($data->ID) {
            $data->id = $data->ID;
        }

		return new \Dornaweb\WOOCAM\Address( $data );
	}

    /**
     * Query addresses
     * 
     * @param array $args
     */
    public function get_addresses($args = []) {
        global $wpdb;

        $args = wp_parse_args(
			$args,
			[
				'user_id'     => '',
				'is_default'  => '',
				'return'      => 'objects',
            ]
		);

        $valid_fields       = ['ID', 'user_id', 'first_name', 'last_name', 'title', 'phone', 'lat', 'lng', 'address1', 'address2', 'city', 'state', 'country', 'postcode', 'phone'];
		$get_results_output = ARRAY_A;

        if ( 'ids' === $args['return'] ) {
			$fields = 'ID';
		} elseif ( 'objects' === $args['return'] ) {
			$fields             = '*';
			$get_results_output = OBJECT;
		} else {
			$fields = explode( ',', (string) $args['return'] );
			$fields = implode( ', ', array_intersect( $fields, $valid_fields ) );
		}

        $query = [];
        $query[] = "SELECT {$fields} FROM {$wpdb->prefix}woocam_addresses WHERE 1=1";

        if ($args['user_id']) {
            $query[] = $wpdb->prepare( 'AND user_id = %d', absint( $args['user_id'] ) );
        }

        if ($args['is_default']) {
            $query[] = $wpdb->prepare( 'AND is_default = %s', '1' );
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results( implode( ' ', $query ), $get_results_output );

        switch ( $args['return'] ) {
			case 'ids':
				return wp_list_pluck( $results, 'ID' );
			case 'objects':
				return array_map( [ $this, 'get_address' ], $results );
			default:
				return $results;
		}
    }
}