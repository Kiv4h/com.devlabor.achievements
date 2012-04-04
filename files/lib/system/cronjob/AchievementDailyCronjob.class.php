<?php
//wcf imports
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');

/**
 * Daily cronjob for awarding achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.cronjob
 */

class AchievementDailyCronjob implements Cronjob{
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data){
		// UserMembership fired on this Event; Its very slooooow..
		//EventHandler::fireAction($this, 'execute');
	}
}
?>