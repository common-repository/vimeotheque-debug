<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-automatic-import
 */

namespace Vimeotheque_Debugger\Admin;

use Vimeotheque_Debugger\Admin\Page\Debug_Page;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Admin {

	public function __construct(){
		add_action( 'wp_loaded', [
			$this,
			'init'
		], 0 );
	}

	public function init(){
		$this->register_pages();
	}

	private function register_pages(){
		$admin_menu = \Vimeotheque\Plugin::instance()->get_admin()->get_admin_menu();

		$admin_menu->register_page(
			new Debug_Page(
				\Vimeotheque\Plugin::instance()->get_admin(),
				__( 'Debug log', 'vimeotheque-debug' ),
				__( 'Debug log', 'vimeotheque-debug' ),
				'vmtq_logs',
				'edit.php?post_type=' . \Vimeotheque\Plugin::instance()->get_cpt()->get_post_type(),
				'manage_options'
			)
		);

	}
}