<?php
//wcf imports
require_once(WCF_DIR.'lib/util/AchievementUtil.class.php');
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/user/AchievementUser.class.php');

/**
 * Deletes  user achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	action
 */

class AchievementDeleteAction extends AbstractSecureAction{
	protected $userID = 0;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters(){
		parent::readParameters();

		if(isset($_REQUEST['userID'])) $this->userID = intval($_REQUEST['userID']);
	}

	/**
	 * @see AbstractAction::execute()
	 */
	public function execute(){
		parent::execute();
		
		if(!MODULE_ACHIEVEMENT_SYSTEM)
			throw new IllegalLinkException();
		
		//check permission
		WCF::getUser()->checkPermission('admin.user.canEditUser');
		
		$sql = "DELETE FROM wcf".WCF_N."_user_achievement 
				WHERE (userID = ".$this->userID.")";
		WCF::getDB()->sendQuery($sql);
		
		AchievementUtil::recalcUserPoints($this->userID);
		AchievementUser::clearCache();		
		
		$this->executed();
		
		HeaderUtil::redirect('index.php?page=User&userID='.$this->userID.SID_ARG_2ND_NOT_ENCODED);
	}

}
?>