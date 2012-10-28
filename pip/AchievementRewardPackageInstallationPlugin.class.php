<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * Installs, updates and uninstalls achievement rewards.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	acp.package.plugin
 */

class AchievementRewardPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin{
    public $tagName = 'achievementreward';
	public $tableName = 'achievement_reward';

	/**
	 * @see AbstractPackageInstallationPlugin::install()
	 */
    public function install(){
        parent::install();

		if(!$xml = $this->getXML())
			return;

		//get xml data tree.
		$rewardXML = $xml->getElementTree('data');

		//Loop through the array and install or uninstall items.
		foreach ($rewardXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through items and create or update them.
					foreach ($block['children'] as $reward) {
						// Extract item properties.
						foreach ($reward['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$reward[$child['name']] = $child['cdata'];
						}

						// default values
						$name = $type = $option = $value = '';

						// get values
						if(isset($reward['name'])) $name = StringUtil::toLowerCase($reward['name']);
                        if(isset($reward['type'])) $type = $reward['type'];
                        if(isset($reward['option'])) $object = StringUtil::toLowerCase($reward['option']);
                        if(isset($reward['value'])) $value = $reward['value'];

						// insert items
						$sql = "INSERT INTO	wcf".WCF_N."_".$this->tableName."(packageID, rewardName, rewardType, rewardOption, rewardValue)
                                VALUES	(".$this->installation->getPackageID().", '".escapeString($name)."', '".escapeString($type)."', '".escapeString($option)."', '".escapeString($value)."')
                                ON DUPLICATE KEY UPDATE rewardType = VALUES(rewardType), rewardOption = VALUES(rewardOption), rewardValue = VALUES(rewardValue)";
						WCF::getDB()->sendQuery($sql);
					}
				}
				// Handle the delete instructions.
				else if($block['name'] == 'delete' && $this->installation->getAction() == 'update') {
					// Loop through items and delete them.
					$nameArray = array();
					foreach ($block['children'] as $reward) {
						// Extract item properties.
						foreach ($reward['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$reward[$child['name']] = $child['cdata'];
						}

						if (empty($reward['name'])) {
							throw new SystemException("Required 'name' attribute for ".$this->fieldName." is missing", 13023);
						}
						$nameArray[] = $reward['name'];
					}
					
					if(count($nameArray)) {
						// delete user groups
						$sql = "DELETE FROM	wcf".WCF_N."_group user_group
								WHERE user_group.rewardID = (
										SELECT rewardID
										FROM wcf".WCF_N."_".$this->tableName."
										WHERE (packageID = ".$this->installation->getPackageID().") AND
										      (rewardName IN ('".implode("','", array_map('escapeString', $nameArray))."'))
										)";
						WCF::getDB()->sendQuery($sql);

						$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName."
                                WHERE (packageID = ".$this->installation->getPackageID().") AND
                                        (rewardName IN ('".implode("','", array_map('escapeString', $nameArray))."'))";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
    }
}
?>