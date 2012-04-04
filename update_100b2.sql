ALTER TABLE wcf1_achievement DROP parentID;		

ALTER TABLE wcf1_user_achievement_event_invoke ADD time INT(11) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE wcf1_achievement ADD rewardName	VARCHAR(255);		
ALTER TABLE wcf1_achievement ADD parent	VARCHAR(255);		

DROP TABLE IF EXISTS wcf1_achievement_reward;
CREATE TABLE wcf1_achievement_reward(
    rewardID      		INT NOT NULL AUTO_INCREMENT,
    packageID           INT NOT NULL,
    rewardName			VARCHAR(255),
	rewardType			VARCHAR(255),
    rewardOption		VARCHAR(255),
	rewardValue		    VARCHAR(255),
	enabled				INT DEFAULT 1,
    PRIMARY KEY(rewardID),
    UNIQUE KEY packageID(packageID, rewardName)
) ENGINE=MYISAM;