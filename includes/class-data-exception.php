<?php
/**
 * WOOCAM Data Exception Class
 *
 * Extends Exception to provide additional data.
 *
 * @package WOOCAM\Classes
 * @since   1.0.0
 */

namespace Dornaweb\WOOCAM;

defined( 'ABSPATH' ) || exit;

/**
 * Data exception class.
 */
class Data_Exception extends \Exception {

	/**
	 * Sanitized error code.
	 *
	 * @var string
	 */
	protected $error_code;

	/**
	 * Error extra data.
	 *
	 * @var array
	 */
	protected $error_data;

	/**
	 * Setup exception.
	 *
	 * @param string $code             Machine-readable error code, e.g `points_invalid_product_id`.
	 * @param string $message          User-friendly translated error message, e.g. 'Product ID is invalid'.
	 * @param int    $http_status_code Proper HTTP status code to respond with, e.g. 400.
	 * @param array  $data             Extra error data.
	 */
	public function __construct( $code, $message, $http_status_code = 400, $data = array() ) {
        parent::__construct( $message, $http_status_code );

		$this->error_code = $code;
		$this->error_data = array_merge( array( 'status' => $http_status_code, 'message' => $this->getMessage() ), $data );
	}

	/**
	 * Returns the error code.
	 *
	 * @return string
	 */
	public function getErrorCode() {
		return $this->error_code;
	}

	/**
	 * Returns error data.
	 *
     * @param  string $key
	 * @return array|mixed
	 */
	public function getErrorData($key = '') {
		return $key ? (isset($this->error_data[$key]) ? $this->error_data[$key] : '') : $this->error_data;
	}
}
