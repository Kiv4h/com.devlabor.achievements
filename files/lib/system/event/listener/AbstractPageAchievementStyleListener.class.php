<?php
//wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementList.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/AchievementHandler.class.php');

/**
 * Extends specialStyles with achievement-style.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class AbstractPageAchievementStyleListener implements EventListener{
    /**
     * @see EventListener::execute
     */
    public function execute($eventObj, $className, $eventName){
        if($eventName == 'assignVariables'){
            WCF::getTpl()->append('specialStyles', '<link href="'.RELATIVE_WCF_DIR.'style/style-achievements.css" type="text/css" rel="stylesheet"/>');
        }
    }
}
?>
