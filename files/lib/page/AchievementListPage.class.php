<?php
//wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/AchievementMenu.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementList.class.php');

/**
 * Shows achievement list page.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	page
 */
 
class AchievementListPage extends AbstractPage{
    //system
    public $templateName = 'achievementList';
    public $neededPermissions = 'user.achievements.canViewAchievementList';

    //items
    public $achievementList = null;
	public $achievements = array();
	public $latestAchievements = array();
	public $ranking = array();
    public $receivers = array();

	public $categoryID = 0;
	public $categories = array();
	public $activeCategory = array();
	
	public $earnedCount = 0;
	public $earnedUsers = 0;
	public $earnedPoints = 0;
	
	public $categoryInformation = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters(){
		parent::readParameters();
		
		if(isset($_GET['categoryID'])) $this->categoryID = intval($_GET['categoryID']);
	}
	
    /**
     * @see Page::readData()
     */
    public function readData(){
        parent::readData();

		$this->readCategories();
		
		$this->achievementList = new AchievementList();
		$this->achievementList->sqlConditions .= "(achievement.hidden = 0)";		
		
		if(!$this->categoryID){
			$this->readRanking();
			$this->readLatestAchievements();
		}
		else{
			$this->readAchievements();
		}
		
		if($this->categoryID > 0 && (!isset($this->categories[$this->categoryID])))
			throw new IllegalLinkException();		
		
		// add category informations
		
		//achievements total
		$this->categoryInformation[] = array(
			'title' => WCF::getLanguage()->get('wcf.achievement.list.achievements.total'),
			'value' => $this->achievementList->countObjects()
		);		
		//achievement points total
		$this->categoryInformation[] = array(
			'title' => WCF::getLanguage()->get('wcf.achievement.list.points.total'),
			'value' => $this->achievementList->countPoints()
		);			
		
		$this->readStats();
    }

    /**
     * Reads achievements.
     */
    public function readAchievements(){		
		//category is set?
		if(isset($this->activeCategory['categoryName'])) 
			$this->achievementList->sqlConditions .= "AND (achievement_object.categoryName = '".escapeString($this->activeCategory['categoryName'])."')";
			
		$this->achievementList->readObjects();
		$this->achievements = $this->achievementList->getObjects();
    }
	
	/** 
	 * Reads stats data.
	 */
	public function readStats(){
		$sql = "SELECT 
					COUNT(*) AS count 
				FROM wcf".WCF_N."_user_achievement user_achievement
				INNER JOIN wcf".WCF_N."_user user ON (user_achievement.userID = user.userID)";
		$row = WCF::getDB()->getFirstRow($sql);
		$this->earnedCount = $row['count'];
		
		$sql = "SELECT 
					COUNT(DISTINCT(user_achievement.userID)) AS count 
				FROM wcf".WCF_N."_user_achievement user_achievement
				INNER JOIN wcf".WCF_N."_user user ON (user_achievement.userID = user.userID)";
		$row = WCF::getDB()->getFirstRow($sql);
		$this->earnedUsers = $row['count'];
		
		$sql = "SELECT 
					SUM(achievementPoints) AS sum 
				FROM wcf".WCF_N."_user";
		$row = WCF::getDB()->getFirstRow($sql);
		$this->earnedPoints = $row['sum'];
	}
	
    /**
     * Reads achievement categories from cache.
     */
    public function readCategories(){
        $this->categories = WCF::getCache()->get('achievements-'.PACKAGE_ID, 'categories');
		
		if(($this->categoryID > 0) && (isset($this->categories[$this->categoryID]))){
			$this->activeCategory = $this->categories[$this->categoryID];
			
			AchievementMenu::getInstance()->setActiveMenuItem($this->activeCategory['categoryName']);
		}	
    }	
	
    /**
     * Reads achievements.
     */
    public function readLatestAchievements(){	
		$sql = "SELECT 
					achievement.*,
					user_achievement.userID,
					user_achievement.time,
					achievement_object.categoryName,
					achievement_object.languageCategory,
					user.username
				FROM wcf".WCF_N."_user_achievement user_achievement
				INNER JOIN wcf".WCF_N."_achievement achievement ON (user_achievement.achievementID = achievement.achievementID)
				INNER JOIN wcf".WCF_N."_user user ON (user.userID = user_achievement.userID)
				LEFT OUTER JOIN wcf".WCF_N."_achievement_object achievement_object ON (achievement_object.objectName = achievement.objectName)
				ORDER BY user_achievement.time DESC
				LIMIT 0,".ACHIEVEMENT_SYSTEM_LATEST_ENTRIES;		
		$result = WCF::getDB()->sendQuery($sql);
		
		while($row = WCF::getDB()->fetchArray($result)){
			$row['user'] = new User(null, array('userID' => $row['userID'], 'username' => $row['username']));
			$this->latestAchievements[] = new Achievement(null, $row);
		}
    }	

    /**
	 * Reads achievements.
	 */
    public function readRanking(){
		$sql = "SELECT 
					user.*
				FROM wcf".WCF_N."_user user
				ORDER BY user.achievementPoints DESC
				LIMIT 0, 10";
		$result = WCF::getDB()->sendQuery($sql);
		
		while($row = WCF::getDB()->fetchArray($result)){
			$this->ranking[] = new User(null, $row);
		}
    }		

    /**
     * @see Page::assignVariables()
     */
    public function assignVariables(){
        parent::assignVariables();

        WCF::getTpl()->assign(array(
           'achievements' => $this->achievements,
           'latestAchievements' => $this->latestAchievements,
           'categoryID' => $this->categoryID,
           'categories' => $this->categories,
		   'activeCategory' => $this->activeCategory,
		   'achievementMenu' => AchievementMenu::getInstance(),
		   'categoryInformation' => $this->categoryInformation,
		   'earnedCount' => $this->earnedCount,
		   'earnedUsers' => $this->earnedUsers,
		   'earnedPoints' => $this->earnedPoints,
		   'ranking' => $this->ranking
        ));
    }
	
	/**
	 * @see Page::show()
	 */
	public function show(){
		require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
        HeaderMenu::setActiveMenuItem('wcf.header.menu.achievements');
	
		parent::show();
	} 
}
?>