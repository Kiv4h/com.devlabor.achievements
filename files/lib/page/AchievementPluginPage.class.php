<?php
//wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/acp/package/Package.class.php');

/**
 * Shows achievement plugin information page.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	page
 */

class AchievementPluginPage extends AbstractPage{
    //system
    public $templateName = 'achievementPlugin';
	
	//items 
	public $package = null;

    /**
     * @see Page::readData()
     */
    public function readData(){
        parent::readData();

		$packageID = WCF::getPackageID('com.devlabor.achievements');
		
		if(!$packageID)
			throw new IllegalLinkException();
			
		$this->package = new Package($packageID);
    }

    /**
     * @see Page::assignVariables()
     */
    public function assignVariables(){
        parent::assignVariables();
		
        WCF::getTpl()->assign(array(
           'package' => $this->package
        ));
    }
	
    /**
     * @see Page::show()
     */
    public function show(){
		if(!MODULE_ACHIEVEMENT_SYSTEM) {
			throw new IllegalLinkException();
		}	
	
		require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
        HeaderMenu::setActiveMenuItem('wcf.header.menu.achievements');
	
        parent::show();
    }	
}
?>