ALTER TABLE wcf1_achievement ADD INDEX i_objectName (objectName);
ALTER TABLE wcf1_achievement_object ADD INDEX i_objectName (objectName);
ALTER TABLE wcf1_user_achievement ADD INDEX i_time (time);

-- update group names
UPDATE wcf1_group SET groupName = CONCAT('wcf.achievement.group.', groupName)
WHERE (groupName = 'signature.rank') OR 
	  (groupName = 'achievement.first.rank') OR 
	  (groupName = 'holiday.collection.rank');
	  
-- update rank titles
UPDATE wcf1_user_rank SET rankTitle = 'wcf.achievement.reward.signature.rank' WHERE (rankTitle = 'wcf.group.wcf.achievement.reward.signature.rank');
UPDATE wcf1_user_rank SET rankTitle = 'wcf.achievement.reward.achievement.first.rank' WHERE (rankTitle = 'wcf.group.wcf.achievement.reward.achievement.first.rank');	  