<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Visiting on specific date.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class HolidayAchievementObject extends AbstractAchievementObject{
	public $showProgress = false;
	
    /**
     * @see AbstractAchievementObject::execute
     */
    public function execute($eventObj){
        parent::execute($eventObj);
		
		if($eventObj === NULL) return;
		
		foreach($this->availableAchievements as $achievement){
			if(gmdate("md", TIME_NOW) == str_pad($achievement->objectQuantity, 4, "0", STR_PAD_LEFT)){
				$achievement->awardToUser($this->user->userID);
			}
		}
    }
	
    /**
     * @see AbstractAchievementObject::getProgress
     */	
	public function getProgress(){
		return 0;
	} 
}
?>