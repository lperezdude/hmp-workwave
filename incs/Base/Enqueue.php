<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;

/**
* 
*/
class Enqueue extends BaseController
{
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	
	function enqueue() {
		// enqueue all our scripts
		wp_enqueue_style( 'hmp-workwave-mystyle', $this->plugin_url . 'assets/hmp-workwave-mystyle.css' );
		wp_enqueue_script( 'hmp-workwave-myscript', $this->plugin_url . 'assets/hmp-workwave-myscript.js' );
		wp_enqueue_style( 'hmp-workwave-select2-mystyle', 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css' );
		wp_enqueue_script( 'hmp-workwave-select2-myscript', 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js' );
	}
}