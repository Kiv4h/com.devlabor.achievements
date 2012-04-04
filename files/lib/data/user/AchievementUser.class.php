<?php
//wcf imports
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * User with achievement data.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user
 */

class AchievementUser extends UserProfile{
	protected static $cache = null;
    protected $earnedAchievements = array();
	
	/**
	 * UserSession::handleData()
	 */
	protected function handleData($data){
		parent::handleData($data);
		
		$this->readAchievements();
	}

    /**
     * Reads all user achievements.
     */
    public function readAchievements(){
		if(self::$cache === null)
			self::$cache = WCF::getCache()->get('user-achievements-'.PACKAGE_ID);
	
		if(isset(self::$cache[$this->userID])){
			foreach(self::$cache[$this->userID] as $achievementData)
				$this->earnedAchievements[$achievementData['achievementID']] = new Achievement(null, $achievementData);
		}
    }

    /**
     * Returns all user achievements indexed by achievementID.
     *
     * @return <array> $availableAchievements
     */
    public function getEarnedAchievements(){
        return $this->earnedAchievements;
    }
	
	/**
	 * Clears cache.
	 */
	public static function clearCache(){
		WCF::getCache()->clearResource('achievement-receivers-'.PACKAGE_ID);
		WCF::getCache()->clearResource('user-achievements-'.PACKAGE_ID);
	
		self::$cache = null;
	}
	
	/**
	 * Recalculates the achievement points
	 */
	public function recalcPoints(){
		$sql = "UPDATE wcf".WCF_N."_user user
				SET user.achievementPoints = (SELECT 
												SUM(achievement.points) 
											FROM wcf".WCF_N."_achievement achievement
											INNER JOIN wcf".WCF_N."_user_achievement user_achievement ON (achievement.achievementID = user_achievement.achievementID)
											WHERE user_achievement.userID = user.userID) 
				WHERE user.userID = ".$this->userID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>