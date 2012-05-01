<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * Holds a list of achievements
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement
 */

class AchievementList extends DatabaseObjectList{
	//system
	public $sqlLimit = 1000;
	public $sqlOrderBy = 'achievement.objectName, achievement.objectQuantity';

    //items
    public $achievements = array();
    public $className = 'Achievement';
	
	/**
     * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects(){
		$objects = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'objects');
		
        $sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_achievement achievement 
			LEFT OUTER JOIN wcf".WCF_N."_achievement_object achievement_object ON (achievement_object.objectName = achievement.objectName)
			".$this->sqlJoins."
			WHERE (achievement_object.objectName IN ('".implode("','", array_keys($objects))."')) ".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : "");
		$row = WCF::getDB()->getFirstRow($sql);
        
		return $row['count'];
    }

	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects(){
		$objects = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'objects');
		
        $sql = "SELECT
                    ".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
                    achievement.*,
					achievement_object.objectID,
					achievement_object.languageCategory
                FROM wcf".WCF_N."_achievement achievement
				LEFT OUTER JOIN wcf".WCF_N."_achievement_object achievement_object ON (achievement_object.objectName = achievement.objectName)
                ".$this->sqlJoins."
                WHERE (achievement_object.objectName IN ('".implode("','", array_keys($objects))."')) ".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : "")."
                ".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '')."
                ".(!empty($this->sqlLimit) ? "LIMIT ".$this->sqlOffset.", ".$this->sqlLimit : '');
        $result = WCF::getDB()->sendQuery($sql);

        while($row = WCF::getDB()->fetchArray($result))
            $this->achievements[$row['achievementID']] = new $this->className(null, $row);
    }

	/**
     * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects(){
        return $this->achievements;
    }

    /**
     * Returns the sum of all achievements.
     */
    public function countPoints(){
        $sql = "SELECT SUM(achievement.points) AS sum
			FROM wcf".WCF_N."_achievement achievement
			LEFT OUTER JOIN wcf".WCF_N."_achievement_object achievement_object ON (achievement_object.objectName = achievement.objectName)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : "");
		$row = WCF::getDB()->getFirstRow($sql);
        
		return $row['sum'];
    }
}
?>
