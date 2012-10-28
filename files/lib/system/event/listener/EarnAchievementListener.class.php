<?php
//wcf imports
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementHandler.class.php');

/**
 * Triggers all possible achievements
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class EarnAchievementListener implements EventListener{
    /**
     * @see EventListener::execute
     */
    public function execute($eventObj, $className, $eventName){
		//before session init?
		if(!MODULE_ACHIEVEMENT_SYSTEM || !WCF::getUser()) return;
		
		if(WCF::getUser()->userID == 0) 
			return;
	
		if(!WCF::getUser()->getPermission('user.achievements.canEarnAchievements') || WCF::getUser()->earnAchievementSuspended) 
			return;
	
		//load from cache
		$objects = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'objects');
				
		foreach($objects as $achievementObject){
			if((($achievementObject['eventClassName'] == $className) || (is_subclass_of($eventObj, $achievementObject['eventClassName']))) && ($achievementObject['eventName'] == $eventName)){
				AchievementHandler::increaseInvoking(WCF::getUser()->userID, $className, $eventName);
				AchievementHandler::fire($achievementObject['objectName'], $eventObj);
			}
		}
    }
}
?>
