<?php
/**
 * Woocam Cache Helper class
 *
 *
 * @package Woocam\Classes
 * @since   3.0.0
 */

namespace Dornaweb\WOOCAM;

defined( 'ABSPATH' ) || exit;

/**
 * Cache Helper Class
 */
class Woocam_Cache_Helper {
    /**
	 * Get prefix for use with wp_cache_set. Allows all cache in a group to be invalidated at once.
	 *
	 * @param  string $group Group of cache to get.
	 * @return string
	 */
	public static function get_cache_prefix( $group ) {
		// Get cache key - uses cache key wc_orders_cache_prefix to invalidate when needed.
		$prefix = wp_cache_get( 'wc_' . $group . '_cache_prefix', $group );

		if ( false === $prefix ) {
			$prefix = microtime();
			wp_cache_set( 'wc_' . $group . '_cache_prefix', $prefix, $group );
		}

		return 'wc_cache_' . $prefix . '_';
	}
}