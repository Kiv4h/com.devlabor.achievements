<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Earn achievement on getting new friends.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class UserFriendsAchievementObject extends AbstractAchievementObject{
	public $workerExecution = true;
	
    /**
     * @see AbstractAchievementObject::execute
     */
    public function execute($eventObj){
        parent::execute($eventObj);
		
		if(!isset($_GET['accept']))
			return;
		
        foreach($this->availableAchievements as $achievement){
			if($this->getProgress() >= $achievement->objectQuantity)
				$achievement->awardToUser($this->user->userID);
        }
    }
	
	/**
	 * Returns user progress.
	 */
	public function getProgress(){
		$sql = "SELECT 
					COUNT(whiteUserID) AS count
				FROM wcf".WCF_N."_user_whitelist
				WHERE (userID = ".$this->user->userID.") AND 
						(confirmed = 1)";
		$row = WCF::getDB()->getFirstRow($sql);				
					
		return $row['count'];
		//return count(explode(',', $this->user->buddies))-1;
	}
}
?>
