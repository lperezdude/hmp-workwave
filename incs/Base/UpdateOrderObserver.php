<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;
class UpdateOrderObserver extends BaseController
{
	public function register() {
		add_action("woocommerce_update_order", array($this, 'UpdateOrderObserverFunc'));
	}
    
	function UpdateOrderObserverFunc($order_id) {
		
		global $wpdb;
        if (!$order_id) {
            return;
        }

        $order    = wc_get_order($order_id);
        $shipping_address_1     = $order->get_shipping_address_1();
        $shipping_address_2     = $order->get_shipping_address_2();
        $shipping_city          = $order->get_shipping_city();
        $shipping_state         = $order->get_shipping_state();
        $shipping_postcode      = $order->get_shipping_postcode();
        $shipping_country       = $order->get_shipping_country();
        $shipping_address_final = $shipping_address_1 . " " . $shipping_address_2 . " " . $shipping_city . " " . $shipping_state . " " . $shipping_postcode . " " . $shipping_country;
        $order_status = $order->get_status();
        $saved_pluginStatusEnabled =  get_option( "hmp_WorkWavePluginEnabledStatus");
        $saved_workwave_key =  get_option( "WorkWave_workwave_key"); 
        $saved_teritory_id =  get_option( "WorkWave_teritory_id");
        $saved_order_status_arr =  get_option( "WorkWave_order_status_arr");
        $pending_arr = array("pending");
        $saved_order_status_arr = array_merge($saved_order_status_arr,$pending_arr);
        $DeliveryDateMetaName = get_option( "orddd_delivery_date_field_label", true);
        $order_delivery_date = get_post_meta($order_id, $DeliveryDateMetaName, true);
        if (!empty($order_delivery_date)) {
            $order_delivery_date = date("Ymd", strtotime($order_delivery_date));
        }

        $db_table_name    = $wpdb->prefix . 'workwave_requests'; // table name
		$sql              = "SELECT * FROM $db_table_name WHERE order_id = '$order_id'";
		$results          = $wpdb->get_results($sql);
		$workwave_order_id 		  = "";	
		foreach ($results as $result) {
		    $array_re         = (array) $result;
		    $workwave_order_id        = $array_re['workwave_order_id'];
		}

		// echo "Workwave Order ID: $workwave_order_id <br>";
		// echo "Order Status : $order_status <br>";
        // echo "Order ID: $order_id <br>";
		// echo "Order DD: $order_delivery_date <br>";
		// echo "Shipping Add. : $shipping_address_final <br>";
        
        if ( $order_status == "failed" || $order_status == "on-hold" || $order_status == "cancelled" || $order_status == "refunded" ) {

        	$url = 'https://wwrm.workwave.com/api/v1/territories/'.$saved_teritory_id.'/orders/'.$workwave_order_id.'?key='.$saved_workwave_key;
            $args = array(
                'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                'method'      => 'DELETE',
            );
            $response = wp_remote_request( $url, $args );
            // echo "DELETED";
            $db_table_name    = $wpdb->prefix . 'workwave_requests'; // table name
            // DELETE FROM wp_workwave_requests WHERE order_id = "2605"
            $sql              = "DELETE FROM $db_table_name WHERE order_id = '$order_id'";
            $results          = $wpdb->get_results($sql);

        }else if ( !empty($order_delivery_date) && $saved_pluginStatusEnabled == "true" && in_array($order_status, $saved_order_status_arr) && !empty($saved_workwave_key) ) {            
            $order_details =   '{
                                  "orders": [
                                    {
                                        "id": "'.$workwave_order_id.'",
                                        "name": "Order '.$order_id.'",
                                        "eligibility": {
                                            "type": "on",
                                            "onDates": [
                                              "'.$order_delivery_date.'"
                                            ]
                                        },
                                      "delivery": {
                                        "location": {
                                          "address": "'.$shipping_address_final.'"
                                        },
                                        "customFields": {
                                          "OrderIdentifierField": "wc_order_id_'.$order_id.'"
                                        }
                                      }
                                    }
                                  ]
                                }';
            $url = 'https://wwrm.workwave.com/api/v1/territories/'. $saved_teritory_id .'/orders?key='. $saved_workwave_key;
            $args = array(
                'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                'method'      => 'PUT',
                'body'        =>  $order_details,
                'data_format' => 'body',
            );
            $response = wp_remote_request( $url, $args );
            $response_body = (array)json_decode($response['body']);
        }
        // die();
	}
}