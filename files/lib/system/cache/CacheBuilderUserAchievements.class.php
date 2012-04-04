<?php
//wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Builds cache for user achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.cache
 */

class CacheBuilderUserAchievements implements CacheBuilder{
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource){
		list($cache, $cache1, $packageID) = explode('-', $cacheResource['cache']); 
        $data = array();
        
		$sql = "SELECT	
                    achievement.achievementName,
					user_achievement.*
                FROM wcf".WCF_N."_user_achievement user_achievement
				INNER JOIN wcf".WCF_N."_achievement achievement ON (user_achievement.achievementID = achievement.achievementID)
				INNER JOIN wcf".WCF_N."_package_dependency package_dependency ON (achievement.packageID = package_dependency.dependency)
				WHERE (package_dependency.packageID = ".$packageID.")
				ORDER BY user_achievement.userID, user_achievement.achievementID";
		$result = WCF::getDB()->sendQuery($sql);

		while($row = WCF::getDB()->fetchArray($result)){
			$data[$row['userID']][$row['achievementID']] = $row;
		}

		return $data;
	}
}
?>