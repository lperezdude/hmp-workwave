<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;
class CallbackAction extends BaseController
{
    public function register() {
        add_action("rest_api_init", array($this, 'callBackActionFunc'));
    }

    function callBackActionFunc($server) {

        $server->register_route( 'workwave_callback', '/workwave', array(
            'methods'  => 'POST',
            'callback' => function () {
                $json_response = file_get_contents('php://input');
                $response = json_decode($json_response);
                $requestId = $response->requestId;
                $createdObj = $response->data->created[0];
                if (!file_exists("workwave_log_file.txt")) {   
                   fopen("workwave_log_file.txt", "w");
                }
                $myfile = "workwave_log_file.txt";
                $errorMessage = "";
                $workwave_order_id = "";

                if (property_exists($createdObj, 'error')) {
                    $errorMessage = $response->data->created[0]->error->errorMessage;
                }
                else if(property_exists($createdObj, 'id')){
                    $workwave_order_id = $createdObj->id;
                }
                if(!empty($requestId)){
                    if( empty($workwave_order_id) && empty($errorMessage) ){
                        $createdObj = $response->data;
                        if (property_exists($createdObj, 'errorMessage')) {
                            $errorMessage = $createdObj->errorMessage;
                        }
                        else if(property_exists($createdObj, 'id')){
                            $workwave_order_id = $createdObj->id;
                        }
                    }
                    // ADDING IN LOG-FILE -- LOCATION: http://SITE_NAME.COM/workwave_log_file.txt
                    file_put_contents($myfile, '-=-=-=-=-=-=-'.PHP_EOL, FILE_APPEND);
                    file_put_contents($myfile, date("M,d,Y h:i:s A").PHP_EOL, FILE_APPEND);
                    file_put_contents($myfile, '-=-=-=-=-=-=-'.PHP_EOL, FILE_APPEND);
                    file_put_contents($myfile, "REQUEST ID: ".$requestId.PHP_EOL, FILE_APPEND);
                    if (!empty($errorMessage)){
                        $errorMessage = str_replace('.', ' ', $errorMessage);
                        
                        $errorMessage = str_replace('orders[0]', '', $errorMessage);
                        file_put_contents($myfile, "ERROR: ".$errorMessage.PHP_EOL, FILE_APPEND);   
                    }
                    if (!empty($workwave_order_id)){
                        file_put_contents($myfile, "WorkWave OrderId: ".$workwave_order_id.PHP_EOL, FILE_APPEND);
                    }
                    file_put_contents($myfile, PHP_EOL, FILE_APPEND);   
                    // ADDING IN LOG-FILE -- LOCATION: http://SITE_NAME.COM/workwave_log_file.txt

                    // ADDING DATA INTO DATABASE TABLE 
                    global $wpdb;
                    $workwave_requests_table = $wpdb->prefix . 'workwave_requests';
                    $sql = "UPDATE $workwave_requests_table SET workwave_order_id = '$workwave_order_id', error_message = '$errorMessage' WHERE request_id = '$requestId'";
                    $wpdb->get_results($sql);
                    // ADDING DATA INTO DATABASE TABLE
                }
            }
        ));
    }
}


// PUT THIS CODE IN THEME'S FUNCTIONS.PHP
//
// //// ADDING CALLBACK FOR WORKWAVE
// add_action( 'rest_api_init', function ( $server ) {
//     $server->register_route( 'workwave_callback', '/workwave', array(
//         'methods'  => 'POST',
//         'callback' => function () {
//             $json_response = file_get_contents('php://input');
//             $response = json_decode($json_response);
//             $requestId = $response->requestId;
//             $createdObj = $response->data->created[0];
//             $myfile = "workwave_log_file.txt";
//             $errorMessage = "";
//             $workwave_order_id = "";

//             if (property_exists($createdObj, 'error')) {
//                 $errorMessage = $response->data->created[0]->error->errorMessage;
//             }
//             else if(property_exists($createdObj, 'id')){
//                 $workwave_order_id = $createdObj->id;
//             }
//             if(!empty($requestId)){
//                 if( empty($workwave_order_id) && empty($errorMessage) ){
//                     $createdObj = $response->data;
//                     if (property_exists($createdObj, 'errorMessage')) {
//                         $errorMessage = $createdObj->errorMessage;
//                     }
//                     else if(property_exists($createdObj, 'id')){
//                         $workwave_order_id = $createdObj->id;
//                     }
//                 }
//                 // ADDING IN LOG-FILE -- LOCATION: http://SITE_NAME.COM/workwave_log_file.txt
//                 file_put_contents($myfile, '-=-=-=-=-=-=-'.PHP_EOL, FILE_APPEND);
//                 file_put_contents($myfile, date("M,d,Y h:i:s A").PHP_EOL, FILE_APPEND);
//                 file_put_contents($myfile, '-=-=-=-=-=-=-'.PHP_EOL, FILE_APPEND);
//                 file_put_contents($myfile, "REQUEST ID: ".$requestId.PHP_EOL, FILE_APPEND);
//                 if (!empty($errorMessage)){
//                     $errorMessage = str_replace('.', ' ', $errorMessage);
                    
//                     $errorMessage = str_replace('orders[0]', '', $errorMessage);
//                     file_put_contents($myfile, "ERROR: ".$errorMessage.PHP_EOL, FILE_APPEND);   
//                 }
//                 if (!empty($workwave_order_id)){
//                     file_put_contents($myfile, "WorkWave OrderId: ".$workwave_order_id.PHP_EOL, FILE_APPEND);
//                 }
//                 file_put_contents($myfile, PHP_EOL, FILE_APPEND);   
//                 // ADDING IN LOG-FILE -- LOCATION: http://SITE_NAME.COM/workwave_log_file.txt

//                 // ADDING DATA INTO DATABASE TABLE 
//                 global $wpdb;
//                 $workwave_requests_table = $wpdb->prefix . 'workwave_requests';
//                 $sql = "UPDATE $workwave_requests_table SET workwave_order_id = '$workwave_order_id', error_message = '$errorMessage' WHERE request_id = '$requestId'";
//                 $wpdb->get_results($sql);
//                 // ADDING DATA INTO DATABASE TABLE
//             }
//         } // CALLBACK FUNC. CLOSING
//     ));
// });
// // ADDING CALLBACK FOR WORKWAVE