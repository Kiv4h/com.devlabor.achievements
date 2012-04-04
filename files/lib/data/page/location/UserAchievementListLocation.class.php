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

class UserAchievementListLocation extends UserLocation{
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
		if($this->users == null)
			$this->readUsers();
		
		$userID = $match[1];
		if(!isset($this->users[$userID]))
			return '';
		
		return WCF::getLanguage()->getDynamicVariable($location['locationName'], array('userID' => $userID, 'username' => $this->users[$userID]));
	}
}
?>