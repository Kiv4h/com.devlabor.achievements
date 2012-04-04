<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/AbstractNotificationObjectType.class.php');
require_once(WCF_DIR.'lib/data/user/notification/object/UserAchievementRewardNotificationObject.class.php');

/**
 * Represents the UserAchievementRewardNotificationObjectType.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.notification.object
 */

class UserAchievementRewardNotificationObjectType extends AbstractNotificationObjectType{

	/**
	 * @see NotificationObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID){
		// get object
		$achievementReward = new UserAchievementRewardNotificationObject($objectID);

		if(!$achievementReward->rewardID)
            return null;

		// return object
		return $achievementReward;
	}

	/**
	 * @see NotificationObjectType::getObjectByObject()
	 */
	public function getObjectByObject($object) {
		$achievementReward = new UserAchievementRewardNotificationObject(null, $object);

		if(!$achievementReward->rewardID)
            return null;

        // return object
		return $achievementReward;
	}

	/**
	 * @see NotificationObjectType::getObjectsByIDArray()
	 */
	public function getObjectsByIDArray($objectIDArray) {
		$rewards = array();
		$sql = "SELECT		*
		FROM 		wcf".WCF_N."_achievement_reward
		WHERE 		rewardID IN (".implode(',', $objectID).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$rewards[$row['rewardID']] = new UserAchievementRewardNotificationObject(null, $row);
		}

		return $rewards;
	}

	/**
	 * @see NotificationObjectType::getPackageID()
	 */
	public function getPackageID() {
		return WCF::getPackageID('com.devlabor.achievements');
	}
}
?>