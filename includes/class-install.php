<?php
/**
 * Install class
 *
 * @package WOOCAM
 * @since   1.0
 */

namespace Dornaweb\WOOCAM;

defined('ABSPATH') || exit;

class Install
{
    /**
	 * Hook in
	 */
	public static function init() {
        add_filter( 'wpmu_drop_tables', array( __CLASS__, 'wpmu_drop_tables' ) );
    }

    public static function install() {
        if ( ! is_blog_installed() ) {
			return;
        }

        wp_mail('aaa@aaa.net', '1', '1');

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'woocam_plugin_installing' ) ) {
			return;
        }

        wp_mail('aaa@aaa.net', '2', '2');

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'woocam_plugin_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		self::create_tables();
		self::setup_stuffs();
		self::create_options();
		self::create_roles();
		self::create_cron_jobs();
		self::create_files();
		self::update_plugin_version();
		self::maybe_update_db_version();
		flush_rewrite_rules();
		delete_transient( 'woocam_plugin_installing' );
        do_action('woocam_plugin_installed');
    }


	/**
	 * Register stuffs like post types, taxonomies, endpoints, ...
	 *
	 * @since 3.2.0
	 */
	private static function setup_stuffs() {
        // Post types and stuff
	}

    /**
     * Create or update database tables
     */
    private static function create_tables() {
        global $wpdb;
        $wpdb->hide_errors();

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( self::get_schema() );
    }

    /**
     * Create options
     */
    public static function create_options() {}

    /**
     * Create user roles with permissions
     */
    public static function create_roles() {}

    /**
     * Create cron jobs
     *
     * @uses WP_Cron
     */
    public static function create_cron_jobs() {}

    /**
     * Create needed files and directories
     */
    public static function create_files() {}

    /**
     * Update plugin version
     */
    public static function update_plugin_version() {
        update_option('woocam_version', '1.0');
    }

    /**
     * update database version
     */
    public static function maybe_update_db_version() {
        update_option( 'woocam_db_version', '1.0' );
    }

    /**
     * Database chema
     * @return string
     */
    private static function get_schema() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
        }

        $tables = "
CREATE TABLE {$wpdb->prefix}woocam_addresses(
    ID bigint(20) UNSIGNED NOT NULL auto_increment,
    title varchar(250),
    user_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
    is_default tinyint(1) NOT NULL DEFAULT '0',
    lat varchar(250) NULL,
    lng varchar(250) NULL,
    first_name varchar(250) NULL,
    last_name varchar(250) NULL,
    company varchar(250) NULL,
    address1 varchar(250) NULL,
    address2 varchar(250) NULL,
    city varchar(250) NULL,
    state varchar(250) NULL,
    country varchar(250) NULL,
    postcode varchar(250) NULL,
    phone varchar(250) NULL,
    PRIMARY KEY (ID),
    KEY user_id (user_id)
) $collate;
CREATE TABLE {$wpdb->prefix}woocam_addressmeta (
  meta_id BIGINT UNSIGNED NOT NULL auto_increment,
  address_id BIGINT UNSIGNED NOT NULL,
  meta_key varchar(255) default NULL,
  meta_value longtext NULL,
  PRIMARY KEY  (meta_id),
  KEY address_id (address_id),
  KEY meta_key (meta_key(32))
) $collate;
        ";        

        return $tables;
    }

	/**
	 * Return a list of Tables
	 *
	 * @return array Plugins tables.
	 */
	public static function get_tables() {
        global $wpdb;

		$tables = array(
			"{$wpdb->prefix}woocam_addresses",
			"{$wpdb->prefix}woocam_addressmeta",
        );

		$tables = apply_filters( 'woocam_install_get_tables', $tables );
		return $tables;
    }

	/**
	 * Drop All tables.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;
		$tables = self::get_tables();
		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
		}
    }

	/**
	 * Uninstall tables when MU blog is deleted.
	 *
	 * @param array $tables List of tables that will be deleted by WP.
	 *
	 * @return string[]
	 */
	public static function wpmu_drop_tables( $tables ) {
		return array_merge( $tables, self::get_tables() );
	}    
}
