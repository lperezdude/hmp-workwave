<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;
class WorkWavePluginStatusOnOff extends BaseController
{
	public function register() {
		add_action( 'wp_ajax_action_WorkWavePluginStatusOnOff', array( $this, 'WorkWavePluginStatusOnOff' ) );
	}
	
	function WorkWavePluginStatusOnOff() {
		if (isset($_POST['pluginStatusEnabled'])) {$pluginStatusEnabled = $_POST['pluginStatusEnabled'];} 
		else {echo "<br /> Plugin Status is Required! <br /> "; die();}		
		$data_array = array(
            'hmp_WorkWavePluginEnabledStatus'   => $pluginStatusEnabled
        );
        add_option('hmp_WorkWavePluginEnabledStatus', $data_array);
        update_option('hmp_WorkWavePluginEnabledStatus', $pluginStatusEnabled);
        $pluginStatusEnabled =  get_option( "hmp_WorkWavePluginEnabledStatus");
        echo $pluginStatusEnabled;
	wp_die();		
	}
}