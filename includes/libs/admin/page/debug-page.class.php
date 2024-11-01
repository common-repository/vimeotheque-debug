<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-automatic-import
 */

namespace Vimeotheque_Debugger\Admin\Page;

use Vimeotheque\Admin\Page\Page_Interface;
use Vimeotheque\Admin\Page\Page_Abstract;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Debug_Page extends Page_Abstract implements Page_Interface {
	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::get_html()
	 */
	public function get_html(){
		?>
		<div class="wrap">
			<h1><?php _e( 'Logs', 'vimeotheque-debug' );?></h1>
            <table class="cvm-debug widefat">
                <thead>
                <tr>
                    <th><?php _e( 'Query log', 'vimeotheque-debug' );?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $queue = $this->get_queue();
                    if( $queue ):
                ?>
                <tr>
                    <td>

                        <label><?php _e( 'Last queried feed ID', 'vimeotheque-debug' );?> : <?php echo $queue->post_id ;?></label><br />
                        <label><?php _e( 'Last query was made', 'vimeotheque-debug' );?> : <?php echo $this->get_time( $queue->time )?></label><br />
                        <label><?php _e( 'Running an import in the background?', 'vimeotheque-debug' );?> : <?php $this->get_human_answer( $queue->running_update )?></label><br />
                        <label><?php _e( 'Import queue is empty?', 'vimeotheque-debug' );?> : <?php $this->get_human_answer( $queue->empty )?></label><br />
                    </td>
                </tr>
                <?php
                    endif;// if( $queue )
                ?>
                <tr>
                    <td>
						<?php
						$file = VMTQ_DEBUGGER_PATH . 'error_log';
						if( !file_exists( $file ) || filesize( $file ) == 0 ){
							_e( 'There are no registered messages.', 'vimeotheque-debug' );
						}else{
							$handle = fopen( $file , 'r' );
							$content = fread( $handle, filesize( $file ) );
							fclose( $handle );
							?>
                            <textarea id="vimeotheque-debug-box" style="width:100%; height:300px;"><?php echo $content;?></textarea>
                            <?php
                                $url = 'edit.php?post_type=' . \Vimeotheque\Plugin::instance()->get_admin()->get_post_type()->get_post_type() . '&page=' . parent::get_menu_slug();
                            ?>
							<a class="button" href="<?php echo wp_nonce_url( $url, 'cvm_reset_error_log', 'cvm_nonce' );?>">
                                <?php _e( 'Clear log', 'vimeotheque-debug' );?>
                            </a>
                            <label>
                                <input type="checkbox" checked="checked" name="vimeotheque_log_autoscroll" id="vimeotheque_log_autoscroll" />
                                <?php _e( 'Automatically scroll to bottom', 'vimeotheque-debug' );?>
                            </label>
                            <!-- Vimeotheque debug -->
                            <script language="javascript">
                                var textarea = document.getElementById( 'vimeotheque-debug-box' ),
                                    chk = document.getElementById('vimeotheque_log_autoscroll');
                                setInterval( function(){
                                    if( chk.checked ) {
                                        textarea.scrollTop = textarea.scrollHeight;
                                    }
                                }, 500 );
                            </script>
						<?php }?>
                    </td>
                </tr>
                </tbody>
            </table>
		</div>
		<?php
	}

	/**
     * Returns human readable time
     *
	 * @param int $time - timestamp
	 *
	 * @return string|void
	 */
	private function get_time( $time ){
	    if( !$time ){
	        return __('never', 'vimeotheque-debug');
	    }

		return sprintf( __( '%s ago', 'vimeotheque-debug' ), human_time_diff( $time, time() ) );
    }

	/**
     * Returns human readable message
     *
	 * @param boolean $variable
	 * @param bool $echo
	 */
    private function get_human_answer( $variable, $echo = true ){
	    $response = $variable ? _e( 'Yes', 'vimeotheque-debug' ) : _e('No', 'vimeotheque-debug');

	    if( $echo ){
	        echo $response;
	    }

	    return $response;
    }

	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::on_load()
	 */
	public function on_load(){
		wp_enqueue_style(
			'cvm_debug_css',
			VMTQ_DEBUGGER_URL . 'assets/css/style.css'
		);

		// clear log
		if( isset( $_GET['cvm_nonce'] ) ){
			check_admin_referer( 'cvm_reset_error_log', 'cvm_nonce' );
			$file = VMTQ_DEBUGGER_PATH . 'error_log';
			$handle = fopen( $file, 'w' );
			fclose( $handle );

			wp_redirect( 'edit.php?post_type=' . \Vimeotheque\Plugin::instance()->get_admin()->get_post_type()->get_post_type() . '&page=' . parent::get_menu_slug() );
			die();
		}
	}

	/**
	 * @return \Vimeotheque_Pro\Autoimport\Queue
	 */
	public function get_queue(){
	    if( defined('VIMEOTHEQUE_PRO_PATH') ){
	        $queue = \Vimeotheque_Pro\Plugin::instance()->get_importer()->get_queue();
	        return $queue->get_queue_status();
        }
    }
}