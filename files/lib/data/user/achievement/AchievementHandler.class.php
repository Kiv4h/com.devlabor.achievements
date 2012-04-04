<?php
/**
 * Handles achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement
 */

class AchievementHandler{
	public static $cacheData = null;
	
    /**
	 * Triggers all given achievements types.
	 */
    public static function fire($objectName, $eventObj){
		if(self::$cacheData === null)
			self::$cacheData = WCF::getCache()->get('achievements-'.PACKAGE_ID);

		if(!isset(self::$cacheData['objects'][$objectName]))
			return;
			
		if(empty(self::$cacheData['objects'][$objectName]['classFile'])){
			require_once(WCF_DIR.'lib/data/user/achievement/object/DefaultAchievementObject.class.php');
			$className = 'DefaultAchievementObject';
		}
		else{
			if(!file_exists(WCF_DIR.self::$cacheData['objects'][$objectName]['packageDir'].self::$cacheData['objects'][$objectName]['classFile']))
				return;	
				
			require_once(WCF_DIR.self::$cacheData['objects'][$objectName]['packageDir'].self::$cacheData['objects'][$objectName]['classFile']);
			$className = StringUtil::getClassName(self::$cacheData['objects'][$objectName]['classFile']);
		}
		
		$object = new $className(null, self::$cacheData['objects'][$objectName]);
		$object->execute($eventObj);
    }
	
	/**
	 * Increase eventlistener access.
	 */
	public static function increaseInvoking($userID, $className, $eventName){
		//flood check
		$sql = "SELECT 
					time 
				FROM wcf".WCF_N."_user_achievement_event_invoke
				WHERE (userID = ".$userID.") AND 
						(className = '".$className."') AND 
						(eventName = '".$eventName."')";
		$row = WCF::getDB()->getFirstRow($sql);
		
		if(($row['time']+ACHIEVEMENT_SYSTEM_FLOODING) > TIME_NOW)
			return;
	
		$sql = "INSERT INTO wcf".WCF_N."_user_achievement_event_invoke(userID, className, eventName, invoke, time)
				VALUES (".intval($userID).", '".escapeString($className)."', '".escapeString($eventName)."', 1, ".TIME_NOW.")
				ON DUPLICATE KEY UPDATE invoke = invoke + 1, time = VALUES(time)";
		WCF::getDB()->sendQuery($sql);
	}
}
?>