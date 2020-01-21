<?php
/**
 * @package  HMPWorkWave
 */
namespace Incs\Pages;

use Incs\Api\SettingsApi;
use Incs\Base\BaseController;
use Incs\Api\Callbacks\AdminCallbacks;

/**
*
*/
class Admin extends BaseController
{
	public $settings;

	public $callbacks;

	public $pages = array();

	public $subpages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->setPages();

		$this->setSubpages();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
	}

	public function setPages()
	{
		$this->pages = array(
			array(
				'page_title' => 'WorkWave',
				'menu_title' => 'WorkWave',
				'capability' => 'manage_options',
				'menu_slug' => 'hmp_workwave',
				'callback' => array( $this->callbacks, 'adminDashboard' ),
				'icon_url' => 'dashicons-align-left',
				'position' => 10
			)
		);
	}

	public function setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'testing',
				'page_title' => 'Testing',
				'menu_title' => 'Testing',
				'capability' => 'manage_options',
				'menu_slug' => 'testing',
				'callback' => array( $this->callbacks, 'testing' )
			),
			
		);
	}

}
