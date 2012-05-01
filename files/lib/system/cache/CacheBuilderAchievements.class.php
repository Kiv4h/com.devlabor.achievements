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

class CacheBuilderAchievements implements CacheBuilder{
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		
		$data = array('objects' => array(), 'achievements' => array(), 'categories' => array(), 'rewards' => array());

		//categories
		$sql = "SELECT
                    object_category.*,
					IF(object.languageCategory IS NULL, 'wcf.achievement', object.languageCategory) AS languageCategory
                FROM wcf".WCF_N."_achievement_object_category object_category
				LEFT JOIN wcf".WCF_N."_achievement_object object ON (object_category.categoryName = object.categoryName)
				INNER JOIN wcf".WCF_N."_package_dependency package_dependency ON (object_category.packageID = package_dependency.dependency)
				WHERE (package_dependency.packageID = ".$packageID.")
				ORDER BY package_dependency.priority, object_category.showOrder";
		$result = WCF::getDB()->sendQuery($sql);

		while($row = WCF::getDB()->fetchArray($result)){
			$data['categories'][$row['categoryID']] = $row;
		}
		
		//achievement-objects
		$sql = "SELECT
                    object.*,
                    package.packageDir
                FROM wcf".WCF_N."_achievement_object object, wcf".WCF_N."_package package, wcf".WCF_N."_package_dependency package_dependency
				WHERE (package.packageID = object.packageID) AND (object.packageID = package_dependency.dependency) AND (package_dependency.packageID = ".$packageID.")
                GROUP BY object.objectID
                ORDER BY package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);

		while($row = WCF::getDB()->fetchArray($result)){
			$data['objects'][$row['objectName']] = $row;
		}
		
		//achievements
		$sql = "SELECT 
					achievement.*,
					achievement_object.categoryName,
					achievement_object.languageCategory
                FROM wcf".WCF_N."_achievement achievement, wcf".WCF_N."_achievement_object achievement_object, wcf".WCF_N."_package_dependency package_dependency  
				WHERE (achievement_object.objectName = achievement.objectName) AND (achievement.packageID = package_dependency.dependency) AND (package_dependency.packageID = ".$packageID.")
				ORDER BY package_dependency.priority, achievement_object.objectName, achievement.objectQuantity";
		$result = WCF::getDB()->sendQuery($sql);

		while($row = WCF::getDB()->fetchArray($result)){
            $data['achievements'][$row['achievementID']] = $row;
		}

		//achievement-rewards
		$sql = "SELECT 
					achievement_reward.*,
					achievement.*
                FROM wcf".WCF_N."_achievement_reward achievement_reward
				INNER JOIN wcf".WCF_N."_achievement achievement ON (achievement_reward.rewardName = achievement.rewardName)
				INNER JOIN wcf".WCF_N."_package_dependency package_dependency ON (achievement_reward.packageID = package_dependency.dependency)
				WHERE (package_dependency.packageID = ".$packageID.")
				ORDER BY achievement.achievementName";
		$result = WCF::getDB()->sendQuery($sql);

		while($row = WCF::getDB()->fetchArray($result)){
            $data['rewards'][$row['rewardID']] = $row;
		}
		
		return $data;
	}
}
?>