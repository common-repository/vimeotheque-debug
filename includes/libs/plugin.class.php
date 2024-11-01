<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-automatic-import
 */

namespace Vimeotheque_Debugger;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Vimeotheque_Debugger\Admin\Admin;


class Plugin {

	/**
	 * Holds the plugin instance.
	 *
	 * @var Plugin
	 */
	private static $instance = null;

	/**
	 * @var Admin
	 */
	private $admin;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'vimeotheque-debug' ), '2.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'vimeotheque-debug' ), '2.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructors - sets all filters and hooks
	 */
	private function __construct(){
		// start the autoloader
		$this->register_autoloader();

		add_action( 'init', [
			$this,
			'init_admin'
		], -99 );

		// process errors sent by the plugin
		add_action(
			'vimeotheque\debug', [
				$this,
				'register_message'
			], 10, 3 );

		// @todo - add activation and deactivation hooks to run the add-ons manager that you will implement in Vimeotheque Lite
	}

	/**
	 * Register the autoloader
	 */
	private function register_autoloader(){
		require VMTQ_DEBUGGER_PATH . 'includes/libs/autoload.class.php';
		Autoload::run();
	}

	public function init_admin(){
		if( is_admin() ){
			$this->admin = new Admin();
		}
	}

	public function register_message( $message, $separator, $data ){

		$error_log = VMTQ_DEBUGGER_PATH . 'error_log';

		if( filesize( $error_log ) >= pow(1024, 2) ){
			$filename = wp_unique_filename( VMTQ_DEBUGGER_PATH, 'error_log' );
			$result = rename( $error_log , VMTQ_DEBUGGER_PATH . $filename );
			if( !$result ){
				// @todo maybe issue error message
				return;
			}
		}

		$handle = fopen( $error_log, "a" );
		if( false === $handle ){
			return;
		}

		$log_entry = sprintf(
			'[%s] %s',
			date( 'M/d/Y H:i:s' ),
			$message
		);

		fwrite( $handle, $log_entry ."\n" );
		fclose( $handle );
	}
}

Plugin::instance();