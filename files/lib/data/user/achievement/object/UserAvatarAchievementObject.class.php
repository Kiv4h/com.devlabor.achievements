<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/DefaultAchievementObject.class.php');

/**
 * Represents the avatar achievementobject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class UserAvatarAchievementObject extends DefaultAchievementObject{
	public $workerExecution = true;

	/**
	 * @see AbstractAchievementObject::getProgress()
	 */
	public function getProgress(){
		return (int)($this->user->getAvatar() !== null);
	}	
}
?>
