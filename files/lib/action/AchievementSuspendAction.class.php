<?php
//wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * Suspends user to award achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	action
 */

class AchievementSuspendAction extends AbstractSecureAction{
	protected $userID = 0;
	protected $user = null;
	protected $enable = 0;	
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters(){
		parent::readParameters();

		if(isset($_REQUEST['userID'])) $this->userID = intval($_REQUEST['userID']);
		
		$this->user = new UserEditor($this->userID);
		
		if(!$this->user->userID)
			throw new IllegalLinkException();
			
		if(isset($_REQUEST['enable'])) 
			$this->enable = intval($_REQUEST['enable']);
		else
			throw new IllegalLinkException();
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
		
		$this->user->updateOptions(array('earnAchievementSuspended' => $this->enable));
		
		$this->executed();
		
		HeaderUtil::redirect('index.php?page=User&userID='.$this->userID.SID_ARG_2ND_NOT_ENCODED);
	}

}
?>