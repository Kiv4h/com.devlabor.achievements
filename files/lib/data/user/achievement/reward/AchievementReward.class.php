<?php
//wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * Basic implementation of AchievementRewards
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement.reward
 */

class AchievementReward extends DatabaseObject {
	public static $cache = null;
	public $userID = 0;
	public $group = null;

	/**
	 * @see DatabaseObject::__construct()
	 */	
	public function __construct($rewardID, $row = null) {
		if($rewardID !== null) $row = self::getRewardByID($rewardID);
		
		parent::__construct($row);
	}
	
	/**
	 * Reads reward data by id from cache.
	 */
	public static function getRewardByID($rewardID) {
		if(self::$cache === null)
			self::$cache = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'rewards');
		
		if(!isset(self::$cache[$rewardID]))
			return;
		
		return self::$cache[$rewardID];
	}
	
	/**
	 * Reads reward data by id from cache.
	 */
	public static function getRewardObjectByID($rewardID) {
		if(self::$cache === null)
			self::$cache = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'rewards');
		
		if(!isset(self::$cache[$rewardID]))
			return;
		
		return self::getRewardObjectByName(self::$cache[$rewardID]['rewardName']);
	}	
	
	/**
	 * Reads reward data by name from cache.
	 */
	public static function getRewardObjectByName($rewardName) {
		if(self::$cache === null)
			self::$cache = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'rewards');
		
		foreach(self::$cache as $rewardID => $reward){
			if($reward['rewardName'] != $rewardName)
				continue;
				
			$className = $reward['rewardType'].'AchievementReward';			
			
			if(!file_exists(WCF_DIR.'lib/data/user/achievement/reward/'.$className.'.class.php'))
				return null;
			
			require_once(WCF_DIR.'lib/data/user/achievement/reward/'.$className.'.class.php');				
			
			return new $className(null, $reward);
		}
		
		return null;
	}	
	
	/**
	 * Executes reward
	 */
    public function execute(Achievement $achievement, $userID) {
		if(!$userID) $this->userID = WCF::getUser()->userID;
		
		$concatGroupName = $achievement->languageCategory.'.group.'.$this->getName();
		
		foreach(Group::getAllGroups() as $groupID => $groupName){
			if($groupName != $concatGroupName)
				continue;
				
			$this->group = new Group($groupID);
		}
		
		//create new group
		if($this->group === null)
			$this->group = GroupEditor::create($concatGroupName, $this->getGroupOptions());
		
		$user = new UserEditor($this->userID);
		$user->addToGroups($this->group->groupID, false, false);
		
		//notifier user
        if(!MODULE_USER_NOTIFICATION) return;

        NotificationHandler::fireEvent('gainAchievementReward', 'userAchievementReward', $this->rewardID, $this->userID, array('achievement' => $achievement, 'reward' => $this));
	}
	
	/**
	 * Returns name of usergroup
	 */
	public function getName() {
		return $this->rewardName;
	} 
	
	public function __toString() {
		return $this->getName();
	}
	
		
	/**
	 * Gets default values from options.
	 */
	public function getGroupOptions() {
		$defaultValues = array();
		
		$sql = "SELECT	optionID, defaultValue
			FROM	wcf".WCF_N."_group_option";
		$result = WCF::getDB()->sendQuery($sql);
		
		while ($row = WCF::getDB()->fetchArray($result)) {
			$defaultValues[] = array('optionID' => $row['optionID'], 'optionValue' => (is_int($row['defaultValue']) ? 0 : '' ));	
		}

		return $defaultValues;
	}
}
?>
