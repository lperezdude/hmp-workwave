<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;
class OrderActionDetailsOnOrderPage extends BaseController
{
	public function register() {
		add_action("woocommerce_admin_order_data_after_order_details", array($this, 'OrderActionDetailsOnOrderPage'));
	}

	function OrderActionDetailsOnOrderPage($order) {
		$order_id = $order->get_id();
		global $wpdb;
		$db_table_name = $wpdb->prefix . 'workwave_requests'; // table name
		$sql = "SELECT * FROM {$db_table_name} WHERE order_id = '$order_id'";
		$results       = $wpdb->get_results($sql);
		$results_count = count($results);
		if ($results_count != 0) {
			$workwave_order_id = "";
		    $error_message = "";
			foreach ($results as $result) {
			    $array_re            = (array) $result;
			    $workwave_order_id = $array_re['workwave_order_id'];
			    $error_message = $array_re['error_message'];
			}
			if (!empty($workwave_order_id)) {
				echo "Workwave Details:";
				?>
				<div class = "workwave_action_done">
					<div class="alert alert-success" role="alert">
					  <div>This order has been sent to workwave!</div>
					  <div><?php echo "WorkWave Order# " . $workwave_order_id; ?></div>

					</div>
				</div>
				<?php
			}
			if (!empty($error_message)) {
				echo "Workwave Details:";
				?>
				
				<div class = "workwave_action_done">
					<div class="alert alert-danger" role="alert">
					  <div>This order has not been sent to workwave!</div>
					  <div><?php echo "Error: " . $error_message; ?></div>

					</div>
				</div>
				<?php
			}

		}
	}
}