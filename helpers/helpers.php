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

	// เช็ค Link ที่เข้ามาว่าเป็น Link External มั้ย
	// ถ้าใช่ ก็แปลง Link แล้วเข้ารหัส
	public function replaceLinks($matches){
		// เลือก Link ที่ 3
		$link = $matches[2];

		// ถ้า $link เป็น link เดียวกันกับ  JUri::root() ก็ไม่ต้องทำไร return $link ได้เลย
		if(strpos($link, JUri::root())){
			return $link;
		}else{

			// เก็บ id ของ article ที่เราเลือกเป็น warning page
			$warningPage = $this->params->get('warning_page');

			// แปลง $link โดยเข้ารหัส 
			$external = base64_encode($link);

			// ContentHelperRoute::getArticleRoute($warningPage) => คืนค่าเป็น route ของ article 
			// คืนค่ามา => index.php?option=com_content&view=article&id=3&Itemid=101&external=aHR0cDovL3d3dy5nb29nbGUuY29t

			// JRoute::_ จะแปลง link อีกทีให้ดูอ่านง่ายขึ้น
			// คืนมาเป็นแบบนี้ => /aaa2/index.php?id=3&external=aHR0cDovL3d3dy5nb29nbGUuY29t
			$newLink = 'href="'.JRoute::_(ContentHelperRoute::getArticleRoute($warningPage).'&external='.$external).'"';

			// echo "<pre>";
			// print_r(ContentHelperRoute::getArticleRoute($warningPage).'&external='.$external);
			// echo "</pre>";
			// exit;

			return $newLink;
		}
	}

	// Replace link ที่จะออกจากเว็บ
	// เมื่อเข้าหน้า warning page จะกำหนด keyword => {linkprotecturl}  แล้วก้แปลงกลับเป็น link url ที่จะออกไป
	public function leaveSite($article, $external){

		//ดึงค่า input ชื่อ new_windows และเชค
		$target = $this->params->get('new_window') ? 'target="_blank"' : '' ;

		//ถอดรหัส ตัว External Link
		$link = base64_decode($external);

		// Replace Link ออก แทนที่ {linkprotecturl}
		$article->text = str_ireplace('{linkprotecturl}', '<a href="'.$link.'" '.$target.'>'.$link.'</a>', $article->text);
		
		// echo "<pre>";
		// print_r($article);
		// echo "</pre>";
		// exit;
	}

}

