<?php
//wcf imports
require_once(WCF_DIR.'lib/data/page/location/UserLocation.class.php');

/**
 * Represents an achievement page location.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.page.location
 */

class AchievementListLocation extends UserLocation {
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
		$categoryID = $match[1];
		$categories = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'categories');
		
		if(!isset($categories[$categoryID]))
			return '';
		
		return WCF::getLanguage()->getDynamicVariable($location['locationName'], array('categoryID' => $categoryID, 'category' => $categories[$categoryID]));
	}
}
?>