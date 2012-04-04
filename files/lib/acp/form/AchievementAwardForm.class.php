<?php
//wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');

/**
 * Shows achievement awarding form.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	acp.form
 */

class AchievementAwardForm extends ACPForm {
	// system
	public $templateName = 'achievementAward';
	public $activeMenuItem = 'wcf.acp.menu.link.user.achievements.award';
	public $neededPermissions = 'admin.achievements.canAwardAchievements';
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		$this->saved();
		
		WCF::getTPL()->assign(array(
			'pageTitle' => WCF::getLanguage()->get('wcf.acp.menu.link.user.achievements.award'),
			'url' => 'index.php?action=AchievementAward&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED,
			'progress' => 0
		));

		WCF::getTPL()->display('worker');
		exit;
	}
}
?>