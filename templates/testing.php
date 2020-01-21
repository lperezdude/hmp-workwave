<style type="text/css">
    .notice{
        display: none;
    }
</style>
<?php
        $order_id = "2602";
        // VARIABLES
        $order    = wc_get_order($order_id);
        $saved_workwave_key =  get_option( "WorkWave_workwave_key"); 
        $saved_teritory_id =  get_option( "WorkWave_teritory_id");
        // VARIABLES
        

        $url = 'https://wwrm.workwave.com/api/v1/territories/'. $saved_teritory_id .'/orders?key='. $saved_workwave_key.'&include=all';
        $args = array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'method'      => 'GET'
        );
        $response = wp_remote_request( $url, $args );
        $response_body = (array)json_decode($response['body']);
        foreach ($response_body["orders"] as $workwave_order_id => $value) {
            (array)$value;
            $OrderIdentifierField = $value->delivery->customFields->OrderIdentifierField;
            $currentOrderIdentifierField = "wc_order_id_".$order_id;
            if($currentOrderIdentifierField == $OrderIdentifierField){

            // echo "currentOrderIdentifierField: $currentOrderIdentifierField <br>";
            // echo "workwave_order_id : $workwave_order_id <br>";
            // echo "OrderIdentifierField : $OrderIdentifierField <br>";
            
            }

        }