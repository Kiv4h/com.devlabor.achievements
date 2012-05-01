<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');
require_once(WCF_DIR.'lib/data/user/notification/NotificationHandler.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/reward/AchievementReward.class.php');

/**
 * Represents an achievement.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement
 */

class Achievement extends DatabaseObject{
	//cache data
	public static $cache = null;
	
	public $largeIcon = '';
	
	public $firstAchiever = null;
	public $relatedAchievements = null;
	
	public $childrens = array();
	public $receivers = array();
	
	public $reward = null;

	/**
	 * @see DatabaseObject::__construct()
	 */	
	public function __construct($achievementID, $row = null){
		if($achievementID !== null) $row = self::getAchievementByID($achievementID);
		if($row !== null) parent::__construct($row);
	}
	
	/**
	 * Gets achievement by given id.
	 */
	public static function getAchievementByID($achievementID){
		if(self::$cache === null){
			self::$cache = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'achievements');
		}
		
		if(!isset(self::$cache[$achievementID]))
			return;
		
		return self::$cache[$achievementID];
	}
	
	/**
	 * Returns list of related achievements.
	 */
	public function getRelatedAchievements(){
		if($this->relatedAchievements === null){
			if(self::$cache === null)
				self::$cache = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'achievements');
				
			foreach(self::$cache as $achievementID => $achievementData){
				if($achievementData['objectName'] == $this->objectName)
					if($achievementData['hidden'] < 1)
						$this->relatedAchievements[$achievementID] = $achievementData;
			}
			
			unset($this->relatedAchievements[$this->achievementID]);
		}
		
		return $this->relatedAchievements;
	}

    /**
     * Allocate an achievement to user.
     *
     * @param <integer> $userID
     */
    public function awardToUser($userID, $suppressNotification = false){
        $user = new AchievementUser($userID);

		if($user->earnAchievementSuspended) return;
        if(in_array($this->achievementID, array_keys($user->getEarnedAchievements()))) return;
		
		//exists?
		$sql = "SELECT 
					COUNT(*) as count
				FROM wcf".WCF_N."_user_achievement
				WHERE (userID = ".$userID.") AND (achievementID = ".$this->achievementID.")";
		$row = WCF::getDB()->getFirstRow($sql);
		
		if($row['count'] > 0) return;

		$sql = "INSERT IGNORE INTO wcf".WCF_N."_user_achievement(userID, achievementID, time)
				VALUES(".intval($userID).", ".$this->achievementID.", ".TIME_NOW.")";
		WCF::getDB()->sendQuery($sql);

        if($this->points > 0)
			AchievementUtil::recalcUserPoints($user->userID);
		
		AchievementUser::clearCache();
		
		if(ACHIEVEMENT_SYSTEM_ENABLE_ACTIVITY_POINTS){
			require_once(WCF_DIR.'lib/data/user/rank/UserRank.class.php');
			UserRank::updateActivityPoints($userID, $this->activityPoints);
		}
		
        //notifier user
        if(MODULE_USER_NOTIFICATION && !$suppressNotification)
			NotificationHandler::fireEvent('unlockAchievement', 'userAchievement', $this->achievementID, $userID, array('achievement' => $this));
		
		//reward
		if($this->getReward() !== null)
			$this->getReward()->execute($this, $userID);
			
		EventHandler::fireAction($this, 'awardToUser');
    }
	
    /**
     * Reads all achievement receiver.
     */
    public function readReceivers(){
		$data = WCF::getCache()->get('achievement-receivers-'.PACKAGE_ID);

		if(isset($data[$this->achievementID]))
			$this->receivers = $data[$this->achievementID];
    }	
		
    /**
     * Counts all achievement receivers.
     */
    public function countReceivers(){
		if(!count($this->receivers))
			$this->readReceivers();

		return count($this->receivers);
    }	
	
	/**
	 * Returns true, if this achievement is achieved by someone.
	 */
	public function isAchieved(){
		return ($this->countReceivers() > 0);
	}
	
	/**
	 * Returns true, if this achievement is achieved by given user.
	 */	
	public function hasAchieved($userID = 0){
		if($userID == 0) $userID = WCF::getUser()->userID;
		if(!count($this->receivers)) $this->readReceivers();
		
		return (isset($this->receivers[$userID]));
	}
	
	/**
	 * Returns userdata of first achiever.
	 */
	public function getFirstAchiever(){
		if($this->firstAchiever === null){
			$sql = "SELECT 
						user.*
					FROM wcf".WCF_N."_user_achievement user_achievement
					INNER JOIN wcf".WCF_N."_user user ON (user_achievement.userID = user.userID)
					WHERE (user_achievement.achievementID = ".$this->achievementID.") AND 
							(user_achievement.userID > 0)
					ORDER BY user_achievement.time";
			$row = WCF::getDB()->getFirstRow($sql);
			
			$this->firstAchiever = new UserProfile(null, $row);
		}
		
		return $this->firstAchiever;
	}
	
	/**
	 * Returns time of first achieveing.
	 */
	public function getFirstTime(){
		$sql = "SELECT 
					user_achievement.time
				FROM wcf".WCF_N."_user_achievement user_achievement
				WHERE (user_achievement.achievementID = ".$this->achievementID.") AND 
						(user_achievement.userID > 0)
				ORDER BY user_achievement.time";
		$row = WCF::getDB()->getFirstRow($sql);
		
		return $row['time'];
	}	
	
	/**
	 * Returns achievement object
	 */
	public function getObject(){	
		$objects = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'objects');
		
		if(isset($objects[$this->objectName])){
			if(empty($objects[$this->objectName]['classFile'])){
				require_once(WCF_DIR.'lib/data/user/achievement/object/DefaultAchievementObject.class.php');
				$className = 'DefaultAchievementObject';
			}
			else{
				if(!file_exists(WCF_DIR.$objects[$this->objectName]['packageDir'].$objects[$this->objectName]['classFile']))
					return;	
					
				require_once(WCF_DIR.$objects[$this->objectName]['packageDir'].$objects[$this->objectName]['classFile']);
				$className = StringUtil::getClassName($objects[$this->objectName]['classFile']);
			}
			
			return new $className(null, $objects[$this->objectName]);
		}
	}
	
	/**
	 * Returns translated title.
	 */
	public function getTitle(){
		return WCF::getLanguage()->get($this->languageCategory.'.'.$this->achievementName);
	}
	
	/**
	 * Returns translated description.
	 */
	public function getDescription(){
		return WCF::getLanguage()->get($this->languageCategory.'.'.$this->achievementName.'.description');
	}	
	
	/**
	 * Returns current progress of given achievementID
	 */
	public function getProgress(){
		return $this->getObject()->getProgress();
	}	
	
	/**
	 * Returns receivers.
	 */
	public function getReceivers(){
		if(!count($this->receivers))
			$this->readReceivers();
	
		return $this->receivers;
	} 
	
	/** 
	 * Returns percent based width.
	 */
	public function getProgressWidth(){
		$width = ceil((100/$this->objectQuantity)*$this->getProgress());
		
		if($width > 100) return 100;
		 
		return $width;
	}
	
	/**
	 * Returns list of child achievements.
	 */
	public function getChilds(){
		if(!count($this->childrens)){
			$sql = "SELECT 
						achievement.achievementID 
					FROM wcf".WCF_N."_achievement achievement
					WHERE (achievement.parent = '".escapeString($this->achievementName)."')";
			$result = WCF::getDB()->sendQuery($sql);
			
			while($row = WCF::getDB()->fetchArray()){
				$this->childrens[$row['achievementID']] = new Achievement($row['achievementID']);
			}
		}
	
		return $this->childrens;
	}
	
	/**
	 * Returns reward object.
	 */
	public function getReward(){
		if(empty($this->data['rewardName']))
			return;
		
		if($this->reward === null)
			$this->reward = AchievementReward::getRewardObjectByName($this->rewardName);
		
		return $this->reward;
	}
	
	/**
	 * Return level of achievements.
	 */
	public function getLevel(){
		$sql = "SELECT 
					COUNT(achievement.achievementID) AS count 
				FROM wcf".WCF_N."_achievement achievement
				WHERE (achievement.objectName = '".$this->objectName."') AND 
						(achievement.objectQuantity <= ".$this->objectQuantity.") AND
						(achievement.hidden < 1)";
		$row = WCF::getDB()->getFirstRow($sql);
	
		return $row['count'];
	}
	
	/**
	 * Returns large icon if exists
	 */
	public function getLargeIcon(){			
		if(file_exists(StyleManager::getStyle()->getIconPath(str_replace('M.png', 'L.png', $this->icon))))
			return StyleManager::getStyle()->getIconPath(str_replace('M.png', 'L.png', $this->icon));

		return StyleManager::getStyle()->getIconPath($this->icon);
	}
}

?>