<?php
//wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');
require_once(WCF_DIR.'lib/data/socialBookmark/SocialBookmarks.class.php');

/**
 * Shows achievement page.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	page
 */

class AchievementPage extends AbstractPage{
    //system
    public $templateName = 'achievement';
    public $neededPermissions = 'user.achievements.canViewAchievement';

    //items
    public $achievementID = 0;
    public $achievement = null;
    public $receivers = array();

    /**
     * @see Page::readParameters()
     */
    public function readParameters(){
        parent::readParameters();

        if(isset($_GET['achievementID'])) $this->achievementID = intval($_GET['achievementID']);
    }

    /**
     * @see Page::readData()
     */
    public function readData(){
        parent::readData();

        $this->readAchievement();
    }

    /**
     * Reads achievement from cache.
     */
    public function readAchievement(){
        $this->achievement = new Achievement($this->achievementID);

        if(!$this->achievement->achievementID)
            throw new IllegalLinkException();
    }
	
    /**
     * @see Page::assignVariables()
     */
    public function assignVariables(){
        parent::assignVariables();

		$socialBookmarks = null;
		if(MODULE_SOCIAL_BOOKMARK){
			$bookmarkURL = PAGE_URL.'/index.php?page=Achievement&achievementID='.$this->achievement->achievementID;
			$socialBookmarks = SocialBookmarks::getInstance()->getSocialBookmarks($bookmarkURL, $this->achievement->getTitle());
		}		
		
		$buddies = array();
		
		if(WCF::getUser()->buddies !== null){
			$buddies = explode(',', WCF::getUser()->buddies);
		}
		
        WCF::getTpl()->assign(array(
           'achievement' => $this->achievement,
           'achievementID' => $this->achievementID,
		   'socialBookmarks' => $socialBookmarks,
           'receivers' => $this->achievement->getReceivers(),
		   'buddies' => $buddies,
		   'maxLevels' => count($this->achievement->getRelatedAchievements())+1
        ));
    }
}
?>