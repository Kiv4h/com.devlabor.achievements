<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Earn achievement on change username.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class SystemUsernameAchievementObject extends AbstractAchievementObject{
    /**
     * @see AbstractAchievementObject::execute
     */
    public function execute($eventObj){
        parent::execute($eventObj);

		if($eventObj === NULL) return;
		
		if((StringUtil::toLowerCase($eventObj->username) == StringUtil::toLowerCase(WCF::getUser()->username)) || !$eventObj->canChangeUsername || !UserRegistrationUtil::isValidUsername($eventObj->username))
			return;
		
		foreach($this->availableAchievements as $achievement){
			$achievement->awardToUser($this->user->userID);
		}
    }
}
?>