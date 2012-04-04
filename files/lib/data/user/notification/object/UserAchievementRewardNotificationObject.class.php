<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/NotificationObject.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/AchievementReward.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/AvatarAchievementReward.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/UserRankAchievementReward.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/GroupOptionAchievementReward.class.php');

/**
 * Represents the UserAchievementRewardNotificationObject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.notification.object
 */

class UserAchievementRewardNotificationObject extends AchievementReward implements NotificationObject{

	/**
	 * @see Achievement:__construct
	 */
	public function __construct($rewardID, $row = null) {
		// construct from old data if possible
		if (is_object($row)) {
			$row = $row->data;
		}
		parent::__construct($rewardID, $row);
	}

	/**
	 * @see NotificationObject::getObjectID()
	 */
	public function getObjectID(){
		return $this->rewardID;
	}

	/**
	 * @see NotificationObject::getTitle()
	 */
	public function getTitle(){
        return $this->rewardName;
	}

	/**
	 * @see NotificationObject::getURL()
	 */
	public function getURL(){
		return '';
	}

	/**
	 * @see NotificationObject::getIcon()
	 */
	public function getIcon() {
		return 'achievementReward';
	}
}
?>