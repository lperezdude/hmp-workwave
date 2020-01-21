<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;
class WorkWaveSettingsSubmission extends BaseController
{
	public function register() {
		add_action( 'wp_ajax_action_WorkWaveSettingsSubmission', array( $this, 'WorkWaveSettingsSubmission' ) );
	}
	
	function WorkWaveSettingsSubmission() {

		if (isset($_POST['workwave_key'])) {$workwave_key = $_POST['workwave_key'];} 
		else {echo "Workwave Key is Required!"; die();}

		if (isset($_POST['teritory_id'])) {$teritory_id = $_POST['teritory_id'];} 
		else {echo "Teritory ID is Required!"; die();}

		if (isset($_POST['order_status'])) {$order_status_arr = $_POST['order_status'];} 
		else {echo "Order Status is Required!"; die();}

		// CHECK KEY AUTH
        $url = "https://wwrm.workwave.com/api/v1/callback?key=" . $workwave_key;
		$response = wp_remote_request( $url );
		$responce_code = trim($response['response']['code']);
		
		if ($responce_code == "200") {
			// CHECK TERRITORY ID AUTH
			$url_territory = "https://wwrm.workwave.com/api/v1/territories?key=" . $workwave_key;
			$response_territory = wp_remote_request( $url_territory );

			$territories_obj = json_decode( $response_territory['body']);
			if ($territories_obj->errorCode == "-1") {
				echo "Too many requests in a short amount of time. Slow down!";
			}
			else{
				$territories_arr =  $territories_obj->territories;
				$teritory_ids_arr = array( );
				foreach ($territories_arr as $teritory_id_key => $value) {
					array_push($teritory_ids_arr, $teritory_id_key);
				}
				if (in_array($teritory_id, $teritory_ids_arr)) {
					$data_array = array(
			            'WorkWave_workwave_key'   => $workwave_key,
			            'WorkWave_teritory_id'   => $teritory_id,
			            'WorkWave_order_status_arr'   => $order_status_arr
			        );
			        add_option('hmp_WorkWaveSavedSettings', $data_array);
			        update_option('WorkWave_workwave_key', $workwave_key);
			        update_option('WorkWave_teritory_id', $teritory_id);
			        update_option('WorkWave_order_status_arr', $order_status_arr);
			        echo "OK";
				}
				else{
					echo "Territory ID missing or incorrect.";
					$data_array = array(
			            'WorkWave_workwave_key'   => '',
			        	    'WorkWave_teritory_id'   => '',
			            'WorkWave_order_status_arr'   => array()
			        );
			        add_option('hmp_WorkWaveSavedSettings', $data_array);
			        update_option( 'WorkWave_workwave_key', "" );
			        update_option( 'WorkWave_teritory_id', "" );
			        update_option( 'WorkWave_order_status_arr', array() );
				}
			}
			
		}
		elseif ($responce_code == "400") {
			echo "400 Bad Request: The request cannot be accepted.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	        	    'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
		elseif ($responce_code == "401") {
			echo "401 Unauthorized: Authentication credentials are missing or incorrect.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	            'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
		elseif ($responce_code == "404") {
			echo "404 Not Found: The URI requested is invalid or the resource requested does not exists.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	            'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
		elseif ($responce_code == "429") {
			echo "429 Too Many Requests: Rate limit exceeded. Wait before retrying and reduce the rate of requests.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	            'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
		elseif ($responce_code == "500") {
			echo "500 Server Error: Something bad and unexpected happened, typically a bug or an unplanned maintenance update. The WorkWave support team is automatically alerted when this error happens but you may wish to contact them directly to provide additional details to help in the resolution.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	            'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
		elseif ($responce_code == "502") {
			echo "502 Bad Gateway: WorkWave Route Manager service is temporarily down or being upgraded.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	            'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
		elseif ($responce_code == "503") {
			echo "503 Service Unavailable: The WorkWave Route Manager servers are up, but overloaded with requests. Try again later.";
			$data_array = array(
	            'WorkWave_workwave_key'   => '',
	            'WorkWave_teritory_id'   => '',
	            'WorkWave_order_status_arr'   => array()
	        );
	        add_option('hmp_WorkWaveSavedSettings', $data_array);
	        update_option( 'WorkWave_workwave_key', "" );
	        update_option( 'WorkWave_teritory_id', "" );
	        update_option( 'WorkWave_order_status_arr', array() );
		}
	wp_die();
	}
}