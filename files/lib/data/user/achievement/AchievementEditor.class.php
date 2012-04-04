<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * The achievement editor.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.user.achievement
 */
class AchievementEditor extends Achievement{

    /**
     * Creates an new achievement.
     *
     * @param <string> $title
     * @param <array> $additionalFields
     * @return <AchievementEditor>
     */
	public static function create($title, $additionalFields = array()){
       $achievementID = self::insert($title, $additionalFields);

       return new AchievmentEditor($achievementID);
	}

    /**
     * Creates an insert statement and executes it.
     *
     * @param <string> $title
     * @param <array> $fields
     * @return <integer>
     */
	public static function insert($title, $fields){
        $fieldNames = $values = '';

		foreach ($fields as $key => $value) {
			$fieldNames .= ','.$key;
			if (is_int($value)) $values .= ",".$value;
			else $values .= ",'".escapeString($value)."'";
		}

		$sql = "INSERT INTO	wcf".WCF_N."_achievement
					(title".$fieldNames.")
                VALUES('".escapeString($title)."'".$values.")";
		WCF::getDB()->sendQuery($sql);

		return WCF::getDB()->getInsertID();
	}

    /**
     * Updates the current object.
     *
     * @param <string> $title
     * @param <array> $additionalFields
     */
	public function update($title, $additionalFields = array()){
        $updates = '';
		foreach ($additionalFields as $key => $value) {
			$updates .= ','.$key.' = '.(is_int($value) ? intval($value):'\''.escapeString($value).'\'');
		}

		$sql = "UPDATE wcf".WCF_N."_achievement
                SET title = '".escapeString($title)."'
					".$updates."
                WHERE achievementID = ".$this->achievementID;
		WCF::getDB()->sendQuery($sql);
    }

	/**
     * Deletes the current object.
     */
	public function delete(){
        $sql = "DELETE FROM wcf".WCF_N."_achievement WHERE achievementID = ".$this->achievementID;
        WCF::getDB()->sendQuery($sql);

        $sql = "DELETE FROM wcf".WCF_N."_user_achievement WHERE achievementID = ".$this->achievementID;
        WCF::getDB()->sendQuery($sql);
    }
}

?>