<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * Installs, updates and uninstalls achievement.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	acp.package.plugin
 */

class AchievementPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin{
    public $tagName = 'achievement';
	public $tableName = 'achievement';

	/**
	 * @see AbstractPackageInstallationPlugin::install()
	 */
    public function install(){
        parent::install();

		if(!$xml = $this->getXML())
			return;

		//get xml data tree.
		$achievementXML = $xml->getElementTree('data');

		//Loop through the array and install or uninstall items.
		foreach ($achievementXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through items and create or update them.
					foreach ($block['children'] as $achievement) {
						// Extract item properties.
						foreach ($achievement['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$achievement[$child['name']] = $child['cdata'];
						}

						// default values
						$name = $object = $icon = $parent = $reward = '';
                        $quantity = $points = $activityPoints = $hidden = 0;

						// get values
						if(isset($achievement['name'])) $name = StringUtil::toLowerCase($achievement['name']);
                        if(isset($achievement['object'])) $object = StringUtil::toLowerCase($achievement['object']);
                        if(isset($achievement['icon'])) $icon = $achievement['icon'];

						if(isset($achievement['quantity'])) $quantity = intval($achievement['quantity']);
                        if(isset($achievement['points'])) $points = intval($achievement['points']);
                        if(isset($achievement['activitypoints'])) $activityPoints = intval($achievement['activitypoints']);
						if(isset($achievement['hidden'])) $hidden = intval($achievement['hidden']);
						if(isset($achievement['parent'])) $parent = StringUtil::toLowerCase($achievement['parent']);
						if(isset($achievement['reward'])) $reward = StringUtil::toLowerCase($achievement['reward']);

						// insert items
						$sql = "INSERT INTO	wcf".WCF_N."_".$this->tableName."(packageID, achievementName, icon, objectQuantity, points, activityPoints, objectName, hidden, parent, rewardName)
                                VALUES	(".$this->installation->getPackageID().", '".escapeString($name)."', '".escapeString($icon)."', ".intval($quantity).", ".intval($points).", ".intval($activityPoints).", '".escapeString($object)."', ".intval($hidden).", '".escapeString($parent)."', '".escapeString($reward)."')
                                ON DUPLICATE KEY UPDATE points = VALUES(points), icon = VALUES(icon), activityPoints = VALUES(activityPoints), objectQuantity = VALUES(objectQuantity), objectName = VALUES(objectName), hidden = VALUES(hidden), parent = VALUES(parent), rewardName = VALUES(rewardName)";
						WCF::getDB()->sendQuery($sql);
					}
				}
				// Handle the delete instructions.
				else if($block['name'] == 'delete' && $this->installation->getAction() == 'update') {
					// Loop through items and delete them.
					$nameArray = array();
					foreach ($block['children'] as $achievement) {
						// Extract item properties.
						foreach ($achievement['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$achievement[$child['name']] = $child['cdata'];
						}

						if (empty($achievement['name'])) {
							throw new SystemException("Required 'name' attribute for ".$this->fieldName." is missing", 13023);
						}
						$nameArray[] = $achievement['name'];
					}

					if(count($nameArray)) {
						$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName."
                                WHERE (packageID = ".$this->installation->getPackageID().") AND
                                        (achievementName IN ('".implode("','", array_map('escapeString', $nameArray))."'))";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
    }
}
?>