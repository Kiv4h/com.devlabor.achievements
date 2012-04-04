<?php
//wcf imports
require_once(WCF_DIR.'lib/acp/action/WorkerAction.class.php');

/**
 * Try to award achievements to all users.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	acp.action
 */

class AchievementAwardAction extends WorkerAction{
	public $limit = 100;
	public $action = 'AchievementAward';
	
	/**
	 * @see Action::execute()
	 */
	public function execute(){
		parent::execute();
		
		// count users
		$sql = "SELECT	COUNT(userID) AS count
			FROM	wcf".WCF_N."_user user";
		$row = WCF::getDB()->getFirstRow($sql);
		$count = $row['count'];
		
		if($count <= ($this->limit * $this->loop)){
			$this->calcProgress();
			$this->finish();
		}
	
		AchievementUtil::awardAchievements($this->limit, $this->loop);

		$this->executed();
		$this->calcProgress(($this->limit * $this->loop), $count);
		$this->nextLoop('wcf.acp.worker.progress.working', 'index.php?action='.$this->action.'&limit='.$this->limit.'&loop='.($this->loop + 1).'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
	}
}

?>