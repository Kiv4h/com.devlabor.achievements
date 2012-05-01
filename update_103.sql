ALTER TABLE wcf1_achievement ADD INDEX i_objectName (objectName);
ALTER TABLE wcf1_achievement_object ADD INDEX i_objectName (objectName);
ALTER TABLE wcf1_user_achievement ADD INDEX i_time (time);

-- update group names
UPDATE wcf1_group SET groupName = 'wcf.achievement.group.'||groupName
WHERE (groupName = 'signature.rank') OR 
	  (groupName = 'achievement.first.rank') OR 
	  (groupName = 'holiday.collection.rank');