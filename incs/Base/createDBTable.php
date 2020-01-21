<?php 
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

use Incs\Base\BaseController;

/**
* 
*/
class createDBTable extends BaseController
{
	public function register() {
		global $wpdb;

		// Creating 'label_templates_data' Table
		$db_table_name = $wpdb->prefix . 'workwave_requests'; // table name
		if ($wpdb->get_var("SHOW TABLES LIKE '{$db_table_name}'") != $db_table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $db_table_name (
                id int(11) NOT NULL auto_increment,
                order_id int(11) NOT NULL,
                request_id varchar(600) NOT NULL,
                workwave_order_id varchar(600) NULL,
                error_message varchar(600) NULL,
                UNIQUE KEY id (id)
  	        ) $charset_collate;";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta($sql);
		}
	}
}