<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/notification/type/NotificationType.class.php');

/**
 * Represents the AnimationNotificationType.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.notification.type
 */

class AnimationNotificationType implements NotificationType{       
    /**
	 * @see NotificationType::send()
	 */
	public function send(User $user, NotificationEvent $event, NotificationEditor $notification) {
		//UserAchievementNotificationEvent
		// get message and save it
		$message = $event->getMessage($this);
		$notification->addMessage('animation', 0, $message);
	}

	/**
	 * @see NotificationType::revoke()
	 */
	public function revoke(User $user, NotificationEvent $event, NotificationEditor $notification) {
		return;
	}

	/**
	 * @see NotificationType::getName()
	 */
	public function getName() {
		return 'animation';
	}

	/**
	 * @see NotificationType::getIcon()
	 */
	public function getIcon() {
		return 'achievement';
	}
}

?>