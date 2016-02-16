<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.joomla
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class NonHelper{

	public $params = null;

	public function __construct($params = null){
		$this->params = $params;
	}

	//Replace Link ที่ตรงกัน แล้วมาแปลงๆ
	public function replaceLinks($matches){
		$link = $matches[2];
		if(strpos($link, JUri::root())){
			return $link;
		}else{
			$warningPage = $this->params->get('warning_page');
			$external = base64_encode($link);
			$newLink = 'href="'.JRoute::_(ContentHelperRoute::getArticleRoute($warningPage).'&external='.$external).'"';
			return $newLink;
		}
	}

	//Replace link ที่จะออกจากเว็บ
	// เมื่อเข้าหน้า warning page จะกำหนด keyword => {linkprotecturl}  แล้วก้แปลงกลับเป็น link url ที่จะออกไป
	public static function leaveSite($article, $external){
		//ดึงค่า input ชื่อ new_windows และเชค
		$target = $this->params->get('new_window') ? 'target="_blank"' : '' ;

		//ถอดรหัส
		$link = base64_decode($external);

		$article->text = str_ireplace('{linkprotecturl}', '<a href="'.$link.'" '.$target.'>'.$link.'</a>', $article->text);
	}

}

