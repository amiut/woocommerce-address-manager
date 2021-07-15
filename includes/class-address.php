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
class Address extends Data
{
    /**
	 * Order Data array. This is the core order data exposed in APIs since 3.0.0.
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $data = array(
		'title'         => '',
        'user_id'       => 0,
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
        'country'       => '',
        'postcode'      => '',
        'phone'         => '',
	);


    /**
	 * Stores meta in cache for future reads.
	 * A group must be set to to enable caching.
	 *
	 * @var string
	 */
	protected $cache_group = 'addresses';

    /**
	 * Meta type. This should match up with
	 * the types available at https://developer.wordpress.org/reference/functions/add_metadata/.
	 * WP defines 'post', 'user', 'comment', and 'term'.
	 *
	 * @var string
	 */
	protected $meta_type = 'address';

    /**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'address';

	/**
	 * Constructor.
	 *
	 * @param int|object|array $item ID to load from the DB, or WC_Order_Item object.
	 */
	public function __construct( $address = 0 ) {
		parent::__construct( $address );

		if ( $address instanceof Address ) {
			$this->set_id( $address->get_id() );
		} elseif ( is_numeric( $address ) && $address > 0 ) {
			$this->set_id( $address );
		} elseif ( ! empty( $address->ID ) ) {
			$this->set_id( absint( $address->ID ) );
        }

		$this->data_store = Data_Store::load( 'address' );

		// If we have an ID, load the address from the DB.
		if ( $this->get_id() ) {
			try {
				$this->data_store->read( $this );
			} catch ( Exception $e ) {
				$this->set_id( 0 );
				$this->set_object_read( true );
			}
		} else {
			$this->set_object_read( true );
		}
	}

	/**
	 * Merge changes with data and clear.
	 * Overrides WC_Data::apply_changes.
	 * array_replace_recursive does not work well for order items because it merges taxes instead
	 * of replacing them.
	 *
	 * @since 3.2.0
	 */
	public function apply_changes() {
		if ( function_exists( 'array_replace' ) ) {
			$this->data = array_replace( $this->data, $this->changes ); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.array_replaceFound
		} else { // PHP 5.2 compatibility.
			foreach ( $this->changes as $key => $change ) {
				$this->data[ $key ] = $change;
			}
		}
		$this->changes = [];
	}

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_title($context = 'view') {
        return $this->get_prop('title', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_user_id($context = 'view') {
        return $this->get_prop('user_id', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function is_default($context = 'view') {
        return $this->get_prop('is_default', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_lat($context = 'view') {
        return $this->get_prop('lat', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_lng($context = 'view') {
        return $this->get_prop('lng', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_first_name($context = 'view') {
        return $this->get_prop('first_name', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_last_name($context = 'view') {
        return $this->get_prop('last_name', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_company($context = 'view') {
        return $this->get_prop('company', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_address1($context = 'view') {
        return $this->get_prop('address1', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_address2($context = 'view') {
        return $this->get_prop('address2', $context);
    }
    
    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_city($context = 'view') {
        return $this->get_prop('city', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_state($context = 'view') {
        return $this->get_prop('state', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_country($context = 'view') {
        return $this->get_prop('country', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_postcode($context = 'view') {
        return $this->get_prop('postcode', $context);
    }

    /**
     * 
     * @param string $context View or Edit context
     * @return
     */
    public function get_phone($context = 'view') {
        return $this->get_prop('phone', $context);
    }

    /**
     * Set user id
     * 
     * @param int $user_id
     */
    public function set_user_id($user_id = 0) {
        $this->set_prop( 'user_id', $user_id );
    }

    /**
     * Set user id
     * 
     * @param string $title
     */
    public function set_title($title = '') {
        $this->set_prop( 'title', $title );
    }

    /**
     * Set user id
     * 
     * @param string $first_name
     */
    public function set_first_name($first_name = '') {
        $this->set_prop( 'first_name', $first_name );
    }

    /**
     * Set user id
     * 
     * @param string $last_name
     */
    public function set_last_name($last_name = '') {
        $this->set_prop( 'last_name', $last_name );
    }
}