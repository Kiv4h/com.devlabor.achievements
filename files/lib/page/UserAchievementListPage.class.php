<?php
//wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/user/AchievementUser.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfileFrame.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementList.class.php');

/**
 * Shows user achievements.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	page
 */

class UserAchievementListPage extends SortablePage{
    //system
    public $templateName = 'userAchievementList';
	public $defaultSortField = 'time';
	public $defaultSortOrder = 'DESC';
	public $neededPermissions = 'user.achievements.canViewAchievementList';

    //items
    public $userID = 0;
    public $achievements = array();
    
    /**
	 * user profile frame
	 *
	 * @var UserProfileFrame
	 */
	public $frame = null;
	public $user = null;
    public $allAchievements = null;

    /**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

        if(isset($_GET['userID'])) $this->userID = intval($_GET['userID']);

		// get profile frame
		$this->frame = new UserProfileFrame($this);
        $this->user = new AchievementUser($this->userID);
	}

	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();

		switch ($this->sortField) {
			case 'achievementName':
			case 'points':
			case 'time': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}	
	
    /**
     * @see Page::readData()
     */
    public function readData(){
        parent::readData();

		if($this->frame->getUser()->hideAchievements && ($this->frame->getUser()->userID != WCF::getUser()->userID))
			throw new PermissionDeniedException();
		
		//user achievements
		$this->achievementList = new AchievementList;
		$this->achievementList->sqlSelects .= "user_achievement.time";
		$this->achievementList->sqlJoins .= "INNER JOIN wcf".WCF_N."_user_achievement user_achievement ON (user_achievement.achievementID = achievement.achievementID)";
		$this->achievementList->sqlConditions .= "(user_achievement.userID = ".$this->userID.")";
		$this->achievementList->sqlOrderBy = $this->sortField.' '.$this->sortOrder;
		$this->achievementList->sqlOffset = (($this->pageNo-1) * $this->itemsPerPage);
		$this->achievementList->sqlLimit = $this->itemsPerPage;
		$this->achievementList->readObjects();
		$this->achievements = $this->achievementList->getObjects();

        $this->allAchievements = new AchievementList;
        $this->allAchievements->readObjects();
    }

    /**
     * @see Page::assignVariables()
     */
    public function assignVariables(){
        parent::assignVariables();

        $this->frame->assignVariables();

        WCF::getTpl()->assign(array(
                'user' => $this->user,
                'achievements' => $this->achievements,
                'allAchievements' => $this->allAchievements,
                'progressBarWidth' => (($this->allAchievements->countPoints() > 0) ? (100/$this->allAchievements->countPoints()) * $this->frame->user->achievementPoints : 0)
            ));
    }

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		$this->achievementList = new AchievementList;
		$this->achievementList->sqlSelects .= "user_achievement.time";
		$this->achievementList->sqlJoins .= "INNER JOIN wcf".WCF_N."_user_achievement user_achievement ON (user_achievement.achievementID = achievement.achievementID)";
		$this->achievementList->sqlConditions .= "(user_achievement.userID = ".$this->userID.")";
		$this->achievementList->sqlOrderBy = $this->sortField.' '.$this->sortOrder;
		$this->achievementList->readObjects();
		
		return $this->achievementList->countObjects();
	}	
	
	/**
	 * @see Page::show()
	 */
	public function show(){
		// set active menu item
		UserProfileMenu::getInstance()->setActiveMenuItem('wcf.user.profile.menu.link.achievements');

		parent::show();
	}
}
?>
