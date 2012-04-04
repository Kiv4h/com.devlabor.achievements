<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/avatar/AvatarEditor.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/AchievementReward.class.php');

/**
 * Creating avatar for given usergroup.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.reward
 */

class AvatarAchievementReward extends AchievementReward{
	/**
	 * @see AchievementReward::execute()
	 */
    public function execute(Achievement $achievement, $userID = 0){
		parent::execute($achievement, $userID);
		
		AvatarEditor::create(WCF_DIR.$this->value, $achievement->getTitle(), '', 0, $this->group->groupID);
	}
}

?>