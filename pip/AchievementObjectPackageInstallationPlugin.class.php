<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * Installs, updates and uninstalls achievement objects.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	acp.package.plugin
 */

class AchievementObjectPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin{
    public $tagName = 'achievementobject';
	public $tableName = 'achievement_object';

	/**
	 * @see AbstractPackageInstallationPlugin::install()
	 */
    public function install(){
        parent::install();

		if(!$xml = $this->getXML())
			return;

		//get xml data tree.
		$achievementObjectXML = $xml->getElementTree('data');

		//Loop through the array and install or uninstall items.
		foreach($achievementObjectXML['children'] as $key => $block){
			if(count($block['children'])){
				// Handle the import instructions
				if ($block['name'] == 'import') {
					foreach($block['children'] as $child){
						if($child['name'] == 'categories'){
							// loop through all categories
							foreach($child['children'] as $category){
								// check required category name
								if(!isset($category['attrs']['name'])){
									throw new SystemException("Required 'name' attribute for achievementObject category is missing", 13023); 
								}
								
								// default values
								$categoryName = $parentCategoryName = $permissions = $options = '';
								$showOrder = null;
								
								// make xml tags-names (keys in array) to lower case
								$this->keysToLowerCase($category);
								
								// get category data from children (parent, showorder, icon and menuicon)
								foreach($category['children'] as $data){
									if(!isset($data['cdata'])) continue;
									$category[$data['name']] = $data['cdata'];
								}
								
								// get and secure values
								$categoryName = escapeString($category['attrs']['name']);
								if(isset($category['permissions'])) $permissions = $category['permissions'];
								if(isset($category['options'])) $options = $category['options'];
								if(isset($category['parent'])) $parentCategoryName =  escapeString($category['parent']);
								if(isset($category['showorder'])) $showOrder = intval($category['showorder']);
								if($showOrder !== null || $this->installation->getAction() != 'update') {
									$showOrder = $this->getShowOrder($showOrder, $parentCategoryName, 'parentCategoryName', '_category');
								}
								
								if($parentCategoryName != ''){
									$sql = "SELECT	COUNT(categoryID) AS count
											FROM	wcf".WCF_N."_".$this->tableName."_category
											WHERE	categoryName = '".escapeString($parentCategoryName)."'";
									$parentCategoryCount = WCF::getDB()->getFirstRow($sql);

									if($parentCategoryCount['count'] == 0)
										throw new SystemException("Unable to find parent 'option category' with name '".$parentCategoryName."' for category with name '".$categoryName."'.", 13011);
								}
								
								$sql = "INSERT INTO	wcf".WCF_N."_".$this->tableName."_category(packageID, categoryName, parentCategoryName".($showOrder !== null ? ", showOrder" : "").", permissions, options)
										VALUES (".$this->installation->getPackageID().", '".escapeString($categoryName)."', '".escapeString($parentCategoryName)."', ".($showOrder !== null ? $showOrder."," : "")." '".escapeString($permissions)."', '".escapeString($options)."')
										ON DUPLICATE KEY UPDATE parentCategoryName = VALUES(parentCategoryName), ".($showOrder !== null ? "showOrder = VALUES(showOrder)," : "")." permissions = VALUES(permissions), options = VALUES(options)";
								WCF::getDB()->sendQuery($sql);
							}
						}
						// handle options
						elseif($child['name'] == 'objects'){
							// Loop through items and create or update them.
							foreach($child['children'] as $achievementObject) {
								// Extract item properties.
								foreach($achievementObject['children'] as $child) {
									if(!isset($child['cdata'])) continue;
									$achievementObject[$child['name']] = $child['cdata'];
								}

								// default values
								$name = $parentCategoryName = $classFile = $eventClassName = $eventName = '';
								$listenerClassFile = 'lib/system/event/listener/EarnAchievementListener.class.php';
								
								$this->keysToLowerCase($achievementObject);
								
								// get values
								if(isset($achievementObject['name'])) $name = StringUtil::toLowerCase($achievementObject['name']);
								if(isset($achievementObject['parent'])) $parentCategoryName = StringUtil::toLowerCase($achievementObject['parent']);
								if(isset($achievementObject['classfile'])) $classFile = $achievementObject['classfile'];
								if(isset($achievementObject['languagecategory'])) $languageCategory = $achievementObject['languagecategory'];
								
								//event listener
								if(isset($achievementObject['eventclassname'])) $eventClassName = $achievementObject['eventclassname'];
								if(isset($achievementObject['eventname'])) $eventName = $achievementObject['eventname'];
								
								$sql = "INSERT INTO	wcf".WCF_N."_event_listener(packageID, environment, eventClassName, eventName, listenerClassFile, inherit, niceValue)
										VALUES (".$this->installation->getPackageID().", 'user', '".escapeString($eventClassName)."', '".escapeString($eventName)."', '".escapeString($listenerClassFile)."', 1, 0)
										ON DUPLICATE KEY UPDATE environment = VALUES(environment), inherit = VALUES(inherit), listenerClassFile = VALUES(listenerClassFile), niceValue = VALUES(niceValue), eventClassName = VALUES(eventClassName), eventName = VALUES(eventName)";
								WCF::getDB()->sendQuery($sql);

								// insert items
								$sql = "INSERT INTO	wcf".WCF_N."_".$this->tableName."(packageID, categoryName, objectName, eventClassName, eventName, classFile, languageCategory)
										VALUES	(".$this->installation->getPackageID().",'".escapeString($parentCategoryName)."', '".escapeString($name)."', '".escapeString($eventClassName)."', '".escapeString($eventName)."', '".escapeString($classFile)."', '".escapeString($languageCategory)."')
										ON DUPLICATE KEY UPDATE categoryName = VALUES(categoryName), eventClassName = VALUES(eventClassName), eventName = VALUES(eventName), classFile = VALUES(classFile), languageCategory = VALUES(languageCategory)";
								WCF::getDB()->sendQuery($sql);
							}
						}
						// Handle the delete instructions.
						else if($child['name'] == 'delete' && $this->installation->getAction() == 'update'){
							// Loop through items and delete them.
							$nameArray = array();
							$eventListenerArray = array();
							foreach ($child['children'] as $achievementObject) {
								// Extract item properties.
								foreach ($achievementObject['children'] as $child) {
									if (!isset($child['cdata'])) continue;
									$achievementObject[$child['name']] = $child['cdata'];
								}

								if(empty($achievementObject['name']))
									throw new SystemException("Required 'name' attribute for ".$this->fieldName." is missing", 13023);

								$nameArray[] = $achievementObject['name'];
								$eventListenerArray[] = array('className' => $achievementObject['eventClassName'], 'eventName' => $achievementObject['eventName']);
							}

							if(count($eventListenerArray)) {
								foreach($eventListenerArray as $listenerItem){
									$sql = "DELETE FROM	wcf".WCF_N."_event_listener
											WHERE (packageID = ".$this->installation->getPackageID().") AND
													(eventClassName = '".escapeString($listenerItem['eventClassName'])."') AND 
													(eventName = '".escapeString($listenerItem['eventName'])."')";
									WCF::getDB()->sendQuery($sql);
								}
							}
							
							if(count($nameArray)){
								$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName."
										WHERE (packageID = ".$this->installation->getPackageID().") AND
												(objectName IN ('".implode("','", array_map('escapeString', $nameArray))."'))";
								WCF::getDB()->sendQuery($sql);
							}
						}		
					}
				}
			}
		}
    }
	
	/**
	 * @see AbstractPackageInstallationPlugin::uninstall()
	 */
	public function uninstall(){
		parent::uninstall();
		
		$sql = "DELETE FROM wcf".WCF_N."_event_listener 
				WHERE packageID = ".$this->installation->getPackageID();
		WCF::getDB()->sendQuery($sql);
		
		$sql = "DELETE FROM wcf".WCF_N."_".$this->tableName."_category
				WHERE packageID = ".$this->installation->getPackageID();
		WCF::getDB()->sendQuery($sql);
	}
}
?>