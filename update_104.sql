-- update rank titles
UPDATE wcf1_user_rank SET rankTitle = 'wcf.achievement.reward.signature.rank' WHERE (rankTitle = 'wcf.group.wcf.achievement.reward.signature.rank');
UPDATE wcf1_user_rank SET rankTitle = 'wcf.achievement.reward.achievement.first.rank' WHERE (rankTitle = 'wcf.group.wcf.achievement.reward.achievement.first.rank');