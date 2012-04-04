<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 *  Removes the achievements tab.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */
 
class UserProfileFrameAchievementsTabListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if($eventName == 'init'){
			$eventObj->sqlSelects .= "(SELECT COUNT(*) FROM wcf".WCF_N."_user_achievement WHERE userID = user.userID) AS achievementsCount,";
		}
		else if($eventName == 'assignVariables'){
			if((!WCF::getUser()->getPermission('user.achievements.canViewAchievementList') || $eventObj->getUser()->hideAchievements || !$eventObj->getUser()->achievementsCount) && (!$eventObj->getUser()->userID == WCF::getUser()->userID)){
				// remove tab
				foreach(UserProfileMenu::getInstance()->menuItems as $parentMenuItem => $items){
					foreach($items as $key => $item){
						if($item['menuItem'] == 'wcf.user.profile.menu.link.achievements')
							unset(UserProfileMenu::getInstance()->menuItems[$parentMenuItem][$key]);
					}
				}
			}
		}
	}
}
?>