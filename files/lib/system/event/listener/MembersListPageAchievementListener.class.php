<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/AchievementUser.class.php');
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Extends memberslist with achievementpoints.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	system.event.listener
 */

class MembersListPageAchievementListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(!MODULE_ACHIEVEMENT_SYSTEM) return;
		
		//OptionTypeMemberslistcolumns
		if($eventName == 'construct'){
			$eventObj->staticColumns['achievementPoints'] = 'wcf.user.achievement.points';
		}
		//MembersListPage
		elseif($eventName == 'readParameters'){
			$eventObj->specialSortFields[] = 'achievementPoints';
		}	
		elseif($eventName == 'readData'){	
			if($eventObj->sortField == 'achievementPoints'){
				$eventObj->userTable = 'wcf'.WCF_N.'_user';
				$eventObj->userTableAlias = 'user';
				//hard crap, better solution?
				$eventObj->sqlSelects = StringUtil::replace('wcf_user.*,', '', $eventObj->sqlSelects);
				$eventObj->sqlJoins = StringUtil::replace('LEFT JOIN wcf'.WCF_N.'_user wcf_user
						ON (wcf_user.userID = user.userID)', '', $eventObj->sqlJoins);
			}
		}
		elseif($eventName == 'assignVariables') {
			if(in_array('achievementPoints', $eventObj->activeFields)){
				foreach($eventObj->members as $key => $memberData){
					$user = $memberData['user'];
					$eventObj->members[$key]['achievementPoints'] = '<a href="index.php?page=UserAchievementList&amp;userID='.$user->userID.SID_ARG_2ND.'" title="'.WCF::getLanguage()->get('wcf.user.achievement.points').'">'.StringUtil::formatInteger(intval($user->achievementPoints)).'</a>';
				}
			}
		}
	}
}
?>