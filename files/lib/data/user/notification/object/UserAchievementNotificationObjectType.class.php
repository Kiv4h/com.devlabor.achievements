<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/AbstractNotificationObjectType.class.php');
require_once(WCF_DIR.'lib/data/user/notification/object/UserAchievementNotificationObject.class.php');

/**
 * Represents the UserAchievementNotificationObjectType.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.notification.object
 */

class UserAchievementNotificationObjectType extends AbstractNotificationObjectType{

	/**
	 * @see NotificationObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID){
		// get object
		$achievement = new UserAchievementNotificationObject($objectID);

		if(!$achievement->achievementID)
            return null;

		// return object
		return $achievement;
	}

	/**
	 * @see NotificationObjectType::getObjectByObject()
	 */
	public function getObjectByObject($object) {
		// build object using its data array
		$achievement = new UserAchievementNotificationObject(null, $object);

		if(!$achievement->achievementID)
            return null;

        // return object
		return $achievement;
	}

	/**
	 * @see NotificationObjectType::getObjectsByIDArray()
	 */
	public function getObjectsByIDArray($objectIDArray) {
		$achievements = array();
		$sql = "SELECT		*
		FROM 		wcf".WCF_N."_achievement
		WHERE 		achievementID IN (".implode(',', $objectID).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$achievements[$row['achievementID']] = new UserAchievementNotificationObject(null, $row);
		}

		// return objects
		return $achievements;
	}

	/**
	 * @see NotificationObjectType::getPackageID()
	 */
	public function getPackageID() {
		return WCF::getPackageID('com.devlabor.achievements');
	}
}
?>