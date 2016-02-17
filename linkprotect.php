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

	private $callbackFunction;

	public function __construct(&$subject,$config = array()){
		parent::__construct($subject, $config);

		//ดึง file  Helper เข้ามาใช้
		require_once __DIR__ . '/helpers/helpers.php';

		// $this->params ดึงมาจากไฟล์ linkprotect.xml ตามที่ extends JPlugin
		$helper = new NonHelper($this->params);

		// echo "<pre>";
		// print_r($helper);
		// echo "</pre>";
		// exit();

		// เรียกใช้ replaceLinks() ใน helper
		$this->callbackFunction = array($helper, 'replaceLinks');
	}

	/**
	* ดัก Event ก่อน Content แสดง
	* $context =  type of content , แบบของ content ที่ส่งมา ex. com_content.category   
	* $article = article object
	* $params  = article params
	*
	* @return Boolean
	*/
	public function onContentBeforeDisplay($context ,$article, $params){

		// แยกคำของ $context 
		$parts = explode(".", $context);

		// เชคว่าเปน com_content รึเปล่า
		if($parts[0] != "com_content"){
			return;
		}

		// เชค content ถ้ามีสั่ง {linkprotect=off} ในเนื้อหา => plugin ไม่ต้องทำงาน
		if(stripos($article->text, '{linkprotect=off}') === true ){
			// replace '{linkprotect=off}' เป็นว่างๆ
			$article->text = str_ireplace('{linkprotect=off}', '', $article->text);
		}

		$app = JFactory::getApplication();
		$external = $app->input->get('external', NULL);

		// ถ้าเป็น external link ไปข้างนอก ใช้ทำ leaveSite()
		if ($external) {
			NonHelper::leaveSite($article, $external);
		}else{
			$pattern = '@href=("|\')(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)("|\')@';
			
			// หาเฉพาะ Link ที่เป็น external link ที่ match กับ Pattern ด้านบน = มี href=""
			// หาคำเหมือนแล้วแปลง ส่งไปที่ callbackFunction  = replaceLinks() ใน helper นั่นเอง
			$article->text = preg_replace_callback($pattern, $this->callbackFunction, $article->text);
		
		// 	echo "<pre>";
		// print_r($article);
		// echo "</pre>";
		// exit;
		}
	}
}




