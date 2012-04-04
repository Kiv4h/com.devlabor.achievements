<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/AchievementUser.class.php');

/**
 * Achievement utilities.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class AchievementUtil{

    /**
	* Recalculates user achievement points
	*/
    public static function recalcUserPoints($userID = 0){
        $sql = "UPDATE wcf".WCF_N."_user user
                SET achievementPoints = (SELECT
                                            SUM(achievement.points)
                                         FROM wcf".WCF_N."_user_achievement user_achievement
                                         INNER JOIN wcf".WCF_N."_achievement achievement ON (achievement.achievementID = user_achievement.achievementID)
                                         WHERE (user.userID = user_achievement.userID))".
				(($userID > 0) ? 'WHERE (user.userID = '.$userID.')':'');
        WCF::getDB()->sendQuery($sql);
    }
	
	/**
	 * Try awarding achievements to all users.
	 */
	public static function awardAchievements($limit, $loop){
		$userIDs = array();
		
		// get users
		$sql = "SELECT 
					user.*
				FROM wcf".WCF_N."_user user
				ORDER BY user.userID";
		$result = WCF::getDB()->sendQuery($sql, $limit, ($limit * $loop));
		
		while($row = WCF::getDB()->fetchArray($result)){
			$userIDs[$row['userID']] = $row;
		}
	
		$cachedObjects = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'objects');

		foreach($cachedObjects as $object){
			if(empty($object['classFile'])){
				require_once(WCF_DIR.'lib/data/user/achievement/object/DefaultAchievementObject.class.php');
				$className = 'DefaultAchievementObject';
			}
			else{
				if(!file_exists(WCF_DIR.$object['packageDir'].$object['classFile']))
					continue;	
					
				require_once(WCF_DIR.$object['packageDir'].$object['classFile']);
				$className = StringUtil::firstCharToUpperCase($object['objectName']).'AchievementObject';
			}

			$object = new $className(null, $object);
			
			if(!$object->workerExecution) continue;

			foreach($userIDs as $userID => $data){
				$object->user = new AchievementUser(null, $data);
				$object->compareAchievements();

				try{
					$object->worker();
				}
				catch(Exception $e){}
			}
		}
	}
}
?>
