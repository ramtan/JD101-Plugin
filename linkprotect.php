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
		$helper = new NonHelper($this->params);
		$this->callbackFunction = array($helper, 'replaceLinks');
	}

	/**
	* ดัก Event ก่อน Content แสดง
	* $context = context of content pass to plugin
	* $article = article object
	* $params  = article params
	*
	* @return Boolean
	*/
	public function onContentBeforeDisplay($context ,$article, $params){
		$parts = explode(".", $context);
		if($parts[0] != "com_content"){
			return;
		}

		if(stripos($article=>text, '{linkprotect=off}') === true ){
			$article->text = str_ireplace('{linkprotect=off}', '', $article->text);
		}

		$app = JFactory::getApplication();
		$external = $app->input->get('external', NULL);

		if ($external) {
			NonHelper::leaveSite($article, $external);
		}else{
			$pattern = '@href=("|\')(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)("|\')@';
			$article->text = preg_replace_callback($pattern, $this->callbackFunction, $article->text,);
		}




	}



}




