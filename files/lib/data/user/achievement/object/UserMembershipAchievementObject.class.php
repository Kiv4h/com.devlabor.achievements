<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Represents the membership achievementobject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class UserMembershipAchievementObject extends AbstractAchievementObject{
	public $workerExecution = true;
    /**
     * @see AbstractAchievementObject::execute
     */
    public function execute($eventObj){
        parent::execute($eventObj);
		
		if(WCF::getUser()->userID == 0)	return;
		
		$sql = "SELECT 
					userID, registrationDate 
				FROM wcf".WCF_N."_user
				ORDER BY userID";
		$result = WCF::getDB()->sendQuery($sql);
		
		while($row = WCF::getDB()->fetchArray($result)){
			$this->user = new AchievementUser(null, $row);
			$this->availableAchievements = array_diff_key($this->achievements, $this->user->getEarnedAchievements());
			
			foreach($this->availableAchievements as $achievement){
				if($this->getProgress() >= $achievement->objectQuantity){
					$achievement->awardToUser($user->userID);
				}
			}
		}
    }
	
	/**
	 * @see AbstractAchievementObject::getProgress()
	 */
	public function getProgress(){
		$diff = DateUtil::diff($this->user->registrationDate, TIME_NOW, '');
		
		return ($diff['years'] * 12 + $diff['months']);
	}
}
?>
