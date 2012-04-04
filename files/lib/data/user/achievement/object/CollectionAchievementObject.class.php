<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Represents the collection achievementobject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class CollectionAchievementObject extends AbstractAchievementObject{
    /**
	 * @see AbstractAchievementObject::execute
	 */
    public function execute($eventObj){
        parent::execute($eventObj);
	
		if($eventObj === NULL) 
			return;
	
		foreach($this->availableAchievements as $achievement){
			if(in_array($achievement->getChilds(), $this->user->getEarnedAchievements()))
				$achievement->awardToUser($this->user->userID);
		}
    }
}
?>