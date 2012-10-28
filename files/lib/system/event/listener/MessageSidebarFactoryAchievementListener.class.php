<?php
//wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows the achievementPoints in MessageSidebar.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class MessageSidebarFactoryAchievementListener implements EventListener{
    /**
     * @see EventListener::execute
     */
    public function execute($eventObj, $className, $eventName){
		if(!MODULE_ACHIEVEMENT_SYSTEM) return;
	
		$additionalSidebarContents = '';
	
		foreach($eventObj->messageSidebars as $messageSidebar){
			if($messageSidebar->getUser()->userID == 0)
				continue;
				
			/*$additionalSidebarContents[$messageSidebar->getMessageID()] = '<div class="userCredits achievementMessageSidebar">';
			$additionalSidebarContents[$messageSidebar->getMessageID()].= '<img src="'.StyleManager::getStyle()->getIconPath('achievementS.png').'" title="'.WCF::getLanguage()->get('wcf.user.achievement.points').'" alt="" /> ';
			$additionalSidebarContents[$messageSidebar->getMessageID()].= '<a title="'.WCF::getLanguage()->get('wcf.user.achievement.points').'" href="index.php?page=UserAchievementList&amp;userID='.$messageSidebar->getUser()->userID.SID_ARG_2ND.'">'.$messageSidebar->getUser()->achievementPoints.'</a>';
			$additionalSidebarContents[$messageSidebar->getMessageID()].= '</div>';*/
			
			WCF::getTpl()->assign('user', $messageSidebar->getUser());
			
			$additionalSidebarContents[$messageSidebar->getMessageID()] = WCF::getTpl()->fetch('messageSidebarAchievement');
		}
		
		WCF::getTpl()->append('additionalSidebarContents', $additionalSidebarContents);	
    }
}
?>
