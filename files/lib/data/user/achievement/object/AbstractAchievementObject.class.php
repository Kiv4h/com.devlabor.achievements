<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/AchievementUser.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * Represents the abstract achievementobject.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.object
 */

abstract class AbstractAchievementObject extends DatabaseObject{
	//system
	public $showProgress = true;
	public $workerExecution = false;

    //items
    public $user = null;
    public $cacheData = null;
    public $achievements = null;
    public $availableAchievements = array();

    public function __construct($objectID, $row = null){
        if($objectID !== null){
            //from cache
        }

        parent::__construct($row);

        $this->user = new AchievementUser(WCF::getUser()->userID);
        $this->loadAchievements();
    }

    /**
     * Gets all achievement for this object.
     */
    public function loadAchievements(){
        $this->cacheData = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'achievements');

        foreach($this->cacheData as $achievementID => $achievementData){
            if($achievementData['objectName'] == $this->objectName)
                $this->achievements[$achievementID] = new Achievement(null, $achievementData);
        }
		
		$this->compareAchievements();
    }
	
	/**
	 * 
	 */
	public function compareAchievements(){
		$this->availableAchievements = array_diff_key($this->achievements, $this->user->getEarnedAchievements());
	}

    /**
     * Executes achievement.
     *
     * @param <DatabaseObject> $eventObj
     */
    public function execute($eventObj){
		EventHandler::fireAction($this, 'execute');
    }

	/**
	 * Returns user progress.
	 */
	public function getProgress(){
		EventHandler::fireAction($this, 'getProgress');
		
		$sql = "SELECT 
					invoke 
				FROM wcf".WCF_N."_user_achievement_event_invoke
				WHERE (className = '".$this->eventClassName."') AND (userID = ".$this->user->userID.") AND (eventName = '".$this->eventName."')";
		$row = WCF::getDB()->getFirstRow($sql);
		
		return $row['invoke'];
	}	

	/**
	 * Execution from worker
	 */	
	public function worker(){
		AbstractAchievementObject::execute(NULL);
		
		foreach($this->availableAchievements as $achievement){
			if($this->getProgress() >= $achievement->objectQuantity)
				$achievement->awardToUser($this->user->userID, true);
        }
	}
}
?>
