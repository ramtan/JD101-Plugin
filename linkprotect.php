<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.joomla
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
* Link Protect Content Plugin
*
* @package 		Joomla.Plugin
* @subpackage 	Content.joomla
* @since 		3.0
*/

class PlgContentLinkprotect extends JPlugin{

	public function __construct(&$subject,$config = array()){
		parent::__construct($subject, $config);

		//ดึง file  Helper เข้ามาใช้
		require_once __DIR__ . '/helpers/helpers.php';

		// $this->params ดึงมาจากไฟล์ linkprotect.xml ตามที่ extends JPlugin
		$helper = new LinkProtectHelper($this->params);
	}



}




