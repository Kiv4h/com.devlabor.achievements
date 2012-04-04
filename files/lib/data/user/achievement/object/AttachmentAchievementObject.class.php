<?php
//wcf imports
require_once(WCF_DIR.'lib/data/attachment/AttachmentList.class.php');
require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/object/AbstractAchievementObject.class.php');

/**
 * Earn achievement on new attachment.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

class AttachmentAchievementObject extends AbstractAchievementObject{
	public $workerExecution = true;
    /**
     * @see AbstractAchievementObject::execute
     */
    public function execute($eventObj){
        parent::execute($eventObj);
		
		if($eventObj === null) return;
		if(!$eventObj->showAttachments) return;
		
        foreach($this->availableAchievements as $achievement){
			if($this->getProgress() >= $achievement->objectQuantity)
				$achievement->awardToUser($this->user->userID);
        }
    }

    /**
     * @see AbstractAchievementObject::getProgress
     */
    public function getProgress(){	
		parent::getProgress();
		
		$userAttachmentList = new AttachmentList();
		if(!empty($userAttachmentList->sqlConditions)) $this->sqlConditions .= ' AND ';
		$userAttachmentList->sqlConditions .= '(attachment.userID = '.$this->user->userID.')';
				
		return $userAttachmentList->countObjects();
	}

    /**
     * @see AbstractAchievementObject::worker
     */	
	public function worker(){
		foreach($this->availableAchievements as $achievement){
			if($this->getProgress() >= $achievement->objectQuantity)
				$achievement->awardToUser($this->user->userID);
        }
	}	
}
?>
