<?php
/**
 * @package  HMPWorkWave
 */
namespace Incs\Api\Callbacks;

use Incs\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminDashboard()
	{
		return require_once( "$this->plugin_path/templates/dashboard.php" );
	}
	public function testing()
	{
		return require_once( "$this->plugin_path/templates/testing.php" );
	}
}
