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

ALTER TABLE wcf1_group ADD COLUMN rewardID INT DEFAULT NULL;

-- activity points
UPDATE wcf1_achievement SET activityPoints = points;
UPDATE wcf1_user user_table SET user_table.activityPoints = user_table.activityPoints + (SELECT 
											SUM(achievement.activityPoints) 
										FROM wcf1_achievement achievement 
										INNER JOIN wcf1_user_achievement user_achievement ON (achievement.achievementID = user_achievement.achievementID) 
										WHERE (user_achievement.userID = user_table.userID)
									)
WHERE ((SELECT optionValue FROM wcf1_option WHERE (optionName = 'achievement_system_enable_activity_points')) = '1');

-- update groups
UPDATE wcf1_group SET rewardID = (SELECT reward.rewardID FROM wcf1_achievement_reward reward WHERE (reward.rewardName = 'signature.rank'))
WHERE (groupName = 'wcf.achievement.group.signature.rank');
UPDATE wcf1_group SET rewardID = (SELECT reward.rewardID FROM wcf1_achievement_reward reward WHERE (reward.rewardName = 'achievement.first.rank'))
WHERE (groupName = 'wcf.achievement.group.achievement.first.rank');
UPDATE wcf1_group SET rewardID = (SELECT reward.rewardID FROM wcf1_achievement_reward reward WHERE (reward.rewardName = 'holiday.collection.rank'))
WHERE (groupName = 'wcf.achievement.group.holiday.collection.rank');
UPDATE wcf1_group SET rewardID = (SELECT reward.rewardID FROM wcf1_achievement_reward reward WHERE (reward.rewardName = 'board.collection.rank'))
WHERE (groupName = 'wcf.achievement.group.board.collection.rank');