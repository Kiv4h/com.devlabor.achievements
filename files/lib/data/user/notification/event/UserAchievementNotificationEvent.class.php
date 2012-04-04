<?php
//wcf import
require_once(WCF_DIR.'lib/data/user/notification/event/AbstractNotificationEvent.class.php');

/**
 * User achievement notification.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2010 guilded.eu
 * @package     com.devlabor.achievements
 * @subpackage	data.user.notification.event
 */

class UserAchievementNotificationEvent extends AbstractNotificationEvent{
    /**
	* @see NotificationEvent::getLanguageVariable()
	*/
    public function getLanguageVariable($var, $additionalVariables = array()) {
            return $this->getLanguage()->getDynamicVariable($var, array_merge($additionalVariables, $this->additionalData,
                     array(
                            'event' => $this
                     )
             ));
    }
}

?>