<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;
class NewOrderObserver extends BaseController
{
    public function register() {
        add_action("woocommerce_new_order", array($this, 'NewOrderObserverFunc'));
        add_action("woocommerce_thankyou", array($this, 'NewOrderObserverFunc'));

    }

    function NewOrderObserverFunc($order_id) {
        global $wpdb;
        if (!$order_id) {
            return;
        }
        // ALLOW CODE EXECUTION ONLY ONCE FOR NEW ORDER
        if (!get_post_meta($order_id, '_workwave_new_order_action_done', true)) {
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
            // CHECK IF DELIVERYDATE EXISTS, IF EXISTS MAKE PROPER FORMAT FOR WORKWAVE
            if (!empty($order_delivery_date)) {
                $order_delivery_date = date("Ymd", strtotime($order_delivery_date));
            }


            // CHECK IF ORDDER HAS DELIVERY DATE, WORKWAVE PLUGIN STATUS IS ENABLED, ORDER STATUS EXISTS IN SAVED ORDER STATUSES ARRAY, AND WORKWAVE HAS KEY
            if ( !empty($order_delivery_date) && $saved_pluginStatusEnabled == "true" && in_array($order_status, $saved_order_status_arr) && !empty($saved_workwave_key) ) {
                // BODY FOR ADDING ORDER TO WORKWAVE POST
                $order_details =   '{
                                      "orders": [
                                        {
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

                // URL FOR ADDING ORDER TO WORKWAVE POST:
                $url = 'https://wwrm.workwave.com/api/v1/territories/'. $saved_teritory_id .'/orders?key='. $saved_workwave_key;
                // ARGUMENTS FOR ADDING ORDER TO WORKWAVE POST:
                $args = array(
                    'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                    'method'      => 'POST',
                    'body'        =>  $order_details,
                    'data_format' => 'body',
                );

                $response = wp_remote_request( $url, $args );
                $response_body = (array)json_decode($response['body']);
                // CHECK IF RESPONCE CONTAINS REQUEST ID 
                if ( array_key_exists('requestId', $response_body) ) {
                    $requestId = $response_body['requestId']; 
                    $workwave_requests_table = $wpdb->prefix . 'workwave_requests';
                    $sql = "INSERT INTO $workwave_requests_table (order_id, request_id) VALUES ( '$order_id', '$requestId' )";
                    $wpdb->get_results($sql);

                    // Flag the action as done (to avoid repetitions on reload for example)
                    $order->update_meta_data('_workwave_new_order_action_done', true);
                    $order->save();
                }           
                else {
                    echo $response_body['errorMessage'];
                }
            }
        }
    }
}