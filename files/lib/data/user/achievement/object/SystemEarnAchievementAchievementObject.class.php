<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/DefaultAchievementObject.class.php');

/**
 * Earn achievement on earning first achievement.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class SystemEarnAchievementAchievementObject extends DefaultAchievementObject{	
	
	/**
	 * Returns user progress.
	 */
	public function getProgress(){
		parent::getProgress();
		
		$sql = "SELECT 
					COUNT(*) AS count
				FROM wcf".WCF_N."_user_achievement user_achievement
				WHERE (user_achievement.userID = ".$this->user->userID.") AND (user_achievement.time = (SELECT 
																											user_achievement2.time 
																										FROM wcf".WCF_N."_user_achievement user_achievement2 
																										WHERE (user_achievement2.achievementID = user_achievement.achievementID) 
																										ORDER BY user_achievement2.time
																										LIMIT 0,1))";
		$row = WCF::getDB()->getFirstRow($sql);
		
		return $row['count'];
	}	
}
?>