<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Add cache resource.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class SessionFactoryAchievementListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName){
		if(!MODULE_ACHIEVEMENT_SYSTEM) 
			return;
	
		if($eventName != 'didInit')
			return;

		WCF::getCache()->addResource('achievements-'.PACKAGE_ID, WCF_DIR.'cache/cache.achievements-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAchievements.class.php');
		WCF::getCache()->addResource('achievement-receivers-'.PACKAGE_ID, WCF_DIR.'cache/cache.achievement-receivers-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAchievementReceivers.class.php');
        WCF::getCache()->addResource('user-achievements-'.PACKAGE_ID, WCF_DIR.'cache/cache.user-achievements-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderUserAchievements.class.php');
	}
}
?>