<?php
/**
 * @package  HMPWorkWave
 */
namespace Incs\Base;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}