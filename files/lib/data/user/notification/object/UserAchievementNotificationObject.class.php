<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/NotificationObject.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * Represents the UserAchievementNotificationObject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.notification.object
 */

class UserAchievementNotificationObject extends Achievement implements NotificationObject{
	/**
	 * @see Achievement:__construct
	 */
	public function __construct($achievementID, $row = null){
		if(is_object($row))	$row = $row->data;
		parent::__construct($achievementID, $row);
	}

	/**
	 * @see NotificationObject::getObjectID()
	 */
	public function getObjectID(){
		return $this->achievementID;
	}

	/**
	 * @see NotificationObject::getTitle()
	 */
	public function getTitle(){
        return $this->achievementName;
	}

	/**
	 * @see NotificationObject::getURL()
	 */
	public function getURL(){
		return 'index.php?page=Achievement&achievementID='.$this->achievementID.SID_ARG_2ND;
	}

	/**
	 * @see NotificationObject::getIcon()
	 */
	public function getIcon(){
		return 'achievement';
	}
}
?>