<?php
/**
 * Order Item Data Store Interface
 *
 * @version 3.0.0
 * @package WooCommerce\Interface
 */

namespace Dornaweb\WOOCAM\Interfaces;

/**
 * WC Order Item Data Store Interface
 *
 * Functions that must be defined by the order item data store (for functions).
 *
 * @version  3.0.0
 */
interface Address_Data_Store_Interface {

	/**
	 * Add an order item to an order.
	 *
	 * @param  array $address Order Data.
	 * @return int   Order Item ID
	 */
	public function create( &$address );
}