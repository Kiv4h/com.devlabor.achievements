<?php
//wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementList.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementHandler.class.php');

/**
 * Extends the user profile page with latest achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class UserPageAchievementListener implements EventListener{
    /**
     * @see EventListener::execute
     */
    public function execute($eventObj, $className, $eventName){
        if($eventName != 'assignVariables')	
			return;
			
		if(!MODULE_ACHIEVEMENT_SYSTEM) 
			return;
		
		if(WCF::getUser()->getPermission('admin.user.canEditUser')){
			if($eventObj->frame->getUser()->earnAchievementSuspended)
				WCF::getTPL()->append('additionalAdminOptions', '<li><a href="index.php?action=AchievementSuspend&amp;userID='.$eventObj->frame->getUser()->userID.'&amp;enable=1&amp;t='.SECURITY_TOKEN.SID_ARG_2ND.'">'.WCF::getLanguage()->get('wcf.user.achievement.suspend').'</a></li>');
			else
				WCF::getTPL()->append('additionalAdminOptions', '<li><a href="index.php?action=AchievementSuspend&amp;userID='.$eventObj->frame->getUser()->userID.'&amp;enable=0&amp;t='.SECURITY_TOKEN.SID_ARG_2ND.'">'.WCF::getLanguage()->get('wcf.user.achievement.revoke').'</a></li>');
				
			WCF::getTPL()->append('additionalAdminOptions', '<li><a href="index.php?action=AchievementDelete&amp;userID='.$eventObj->frame->getUser()->userID.'&amp;t='.SECURITY_TOKEN.SID_ARG_2ND.'" onclick="return confirm(\''.WCF::getLanguage()->get('wcf.user.achievement.delete.sure').'\');">'.WCF::getLanguage()->get('wcf.user.achievement.delete').'</a></li>');				
		}
	
		if($eventObj->frame->getUser()->hideAchievements && ($eventObj->frame->getUser()->userID != WCF::getUser()->userID))
			return;
			
		$latestAchievements = new AchievementList;
		$latestAchievements->sqlSelects .= "user_achievement.time";
		$latestAchievements->sqlJoins .= "INNER JOIN wcf".WCF_N."_user_achievement user_achievement ON (user_achievement.achievementID = achievement.achievementID)";
		$latestAchievements->sqlConditions .= "(user_achievement.userID = ".$eventObj->frame->getUser()->userID.")";
		$latestAchievements->sqlOrderBy = "user_achievement.time DESC";
		$latestAchievements->sqlLimit = 5;
		$latestAchievements->readObjects();

		WCF::getTpl()->assign(array(
				'achievements' => $latestAchievements->getObjects(),
				'achievementsCount' => $latestAchievements->countObjects()
			));
		WCF::getTpl()->append('additionalContent3', WCF::getTpl()->fetch('userProfileLastAchievements'));
    }
}
?>
