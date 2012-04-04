<?php
//wcf imports
require_once(WCF_DIR.'lib/data/message/bbcode/BBCode.class.php');
require_once(WCF_DIR.'lib/data/user/achievement/Achievement.class.php');

/**
 * Parses the [achievement] bbcode tag.
 *
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright	2011-2012 devlabor.com
 * @package     	com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	data.message.bbcode
 */
 
class AchievementBBCode implements BBCode{
	/**
	 * @see BBCode::getParsedTag()
	 */
	public function getParsedTag($openingTag, $content, $closingTag, BBCodeParser $parser){
		if(intval($content)){
			$achievementID = intval($content);
			$achievement = new Achievement($achievementID);
			
			if ($parser->getOutputType() == 'text/html') {
				// show template
				WCF::getTPL()->assign(array(
						'achievement' => $achievement
					));
					
				return WCF::getTPL()->fetch('achievementBBCodeTag');
			}
			elseif($parser->getOutputType() == 'text/plain')
				return $achievement->achievementName;
		}
		
		return '';
	}
}
?>