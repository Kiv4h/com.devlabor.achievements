<?php
// wcf imports
require_once(WCF_DIR.'lib/page/util/menu/TreeMenu.class.php');

/**
 * Builds the AchievementMenu.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	page.util.menu
 */
 
class AchievementMenu extends TreeMenu {
	protected static $instance = null;
	
	/**
	 * Returns an instance of the AchievementMenu class.
	 * 
	 * @return	AchievementMenu
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new AchievementMenu();
		}
		
		return self::$instance;
	}
	
	/**
	 * @see TreeMenu::loadCache()
	 */
	protected function loadCache() {
		parent::loadCache();
		
		$categories = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'categories');
		foreach($categories as $category){
			$this->menuItems[$category['parentCategoryName']][] = array(
									'menuItemID' => $category['categoryID'], 
									'menuItem' => $category['categoryName'], 
									'parentMenuItem' => $category['parentCategoryName'], 
									'languageCategory' => $category['languageCategory'], 
									'menuItemLink' => 'index.php?page=AchievementList&categoryID='.$category['categoryID'],
									'menuItemIcon' => '', 
									'permissions' => $category['permissions'], 
									'options'  => $category['options'], 
									'packageDir' => ''
									);
		}
	}
	
	/**
	 * @see TreeMenu::parseMenuItemLink()
	 */
	protected function parseMenuItemLink($link, $path){	
		if (preg_match('~\.php$~', $link)) {
			$link .= SID_ARG_1ST; 
		}
		else {
			$link .= SID_ARG_2ND_NOT_ENCODED;
		}
		
		return $link;
	}
	
	/**
	 * @see TreeMenu::removeEmptyItems()s
	 */
	protected function removeEmptyItems($parentMenuItem = ''){}
	
	/**
	 * @see TreeMenu::parseMenuItemIcon()
	 */
	protected function parseMenuItemIcon($icon, $path) {
		return StyleManager::getStyle()->getIconPath($icon);
	}
}
?>