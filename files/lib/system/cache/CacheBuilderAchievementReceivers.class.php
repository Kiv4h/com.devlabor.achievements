<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Builds cache for achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.cache
 */

class CacheBuilderAchievementReceivers implements CacheBuilder{
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $cache1, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();
		
		//achievement-receivers
		$sql = "SELECT 
                    user.userID,
                    user.username,
                    user_achievement.achievementID,
					user_achievement.time
                FROM wcf".WCF_N."_user_achievement user_achievement
                INNER JOIN wcf".WCF_N."_user user ON (user.userID = user_achievement.userID)
				INNER JOIN wcf".WCF_N."_achievement achievement ON (achievement.achievementID = user_achievement.achievementID)
				INNER JOIN wcf".WCF_N."_package_dependency package_dependency ON (achievement.packageID = package_dependency.dependency)
				WHERE (package_dependency.packageID = ".$packageID.")
                ORDER BY user_achievement.achievementID, user.username";
		$result = WCF::getDB()->sendQuery($sql);

		while($row = WCF::getDB()->fetchArray($result)){
            $data[$row['achievementID']][$row['userID']] = $row;
		}
		
		return $data;
	}
}
?>