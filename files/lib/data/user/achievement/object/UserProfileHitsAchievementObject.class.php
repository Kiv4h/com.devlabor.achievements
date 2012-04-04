<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Represents the userprofilehits achievementobject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class UserProfileHitsAchievementObject extends AbstractAchievementObject{
	public $workerExecution = true;
	
    /**
     * @see AbstractAchievementObject::execute
     */
    public function execute($eventObj){
        parent::execute($eventObj);
		
		if($eventObj === NULL) return;
		
		//triggers foreign achievment
		$user = new AchievementUser($eventObj->frame->getUser()->userID);
		$availableAchievements = array_diff_key($this->achievements, $user->getEarnedAchievements());
		
        foreach($availableAchievements as $achievement){
            if($eventObj->frame->getUser()->profileHits >= $achievement->objectQuantity){
                $achievement->awardToUser($eventObj->frame->getUser()->userID);
            }
        }
    }
	
	/**
	 * @see AbstractAchievementObject::getProgress()
	 */
	public function getProgress(){
		return WCF::getUser()->profileHits;
	}	
	
	/**
	 * @see AbstractAchievementObject::worker()
	 */	
	public function worker(){
		foreach($availableAchievements as $achievement){
            if($this->user->profileHits >= $achievement->objectQuantity){
                $achievement->awardToUser($this->user->userID);
            }
        }
	}
}
?>
