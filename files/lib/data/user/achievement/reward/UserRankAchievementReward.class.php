<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/rank/UserRankEditor.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/AchievementReward.class.php');

/**
 * Creating userrank for given usergroup.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.reward
 */


class UserRankAchievementReward extends AchievementReward{
	/**
	 * @see AchievementReward::execute()
	 */
    public function execute(Achievement $achievement, $userID = 0){
		parent::execute($achievement, $userID);
		
		//checks wether exists
		$sql = "SELECT 
					COUNT(*) AS count 
				FROM wcf".WCF_N."_user_rank
				WHERE (rankTitle = '".escapeString($this->rewardValue)."')";
		$row = WCF::getDB()->getFirstRow($sql);
		
		if($row['count'] > 0)
			return;
		
		UserRankEditor::create($this->rewardValue, '', $this->group->groupID);
	}
}

?>