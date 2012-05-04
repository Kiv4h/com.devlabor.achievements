ALTER TABLE wcf1_user ADD achievementPoints INT(10) UNSIGNED NOT NULL default 0;

DROP TABLE IF EXISTS wcf1_achievement_object;
CREATE TABLE wcf1_achievement_object(
    objectID        	INT NOT NULL AUTO_INCREMENT,
    packageID       	INT NOT NULL,
    objectName      	VARCHAR(255),
	categoryName		VARCHAR(255),
	eventClassName 		VARCHAR(80),
	eventName 			VARCHAR(80),
    classFile       	VARCHAR(255),
	languageCategory 	VARCHAR(255),
    PRIMARY KEY(objectID),
    UNIQUE KEY uk_packageID(packageID, objectName),
	INDEX (objectName)
) ENGINE=MYISAM;

DROP TABLE IF EXISTS wcf1_achievement_object_category;
CREATE TABLE wcf1_achievement_object_category(
	categoryID 				INT NOT NULL AUTO_INCREMENT,
	packageID				INT NOT NULL,
	categoryName			VARCHAR(255),
	parentCategoryName		VARCHAR(255),
	showOrder				INT,
	permissions				text,
	options					text,
	PRIMARY KEY(categoryID),
	UNIQUE KEY uk_packageID(packageID, categoryName)
) ENGINE=MYISAM;

DROP TABLE IF EXISTS wcf1_achievement;
CREATE TABLE wcf1_achievement(
    achievementID       INT NOT NULL AUTO_INCREMENT,
    packageID           INT NOT NULL,
    achievementName     VARCHAR(255),
    icon                VARCHAR(255),
    objectName          VARCHAR(255),
    objectQuantity      INT DEFAULT 0,
    points              INT DEFAULT 0,
    activityPoints      INT DEFAULT 0,
	hidden				BOOLEAN DEFAULT 0,
	rewardName			VARCHAR(255),		
    parent            	VARCHAR(255),
    PRIMARY KEY(achievementID),
    UNIQUE KEY uk_packageID(packageID, achievementName),
	INDEX (objectName)
) ENGINE=MYISAM;


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
    UNIQUE KEY uk_packageID(packageID, rewardName)
) ENGINE=MYISAM;

DROP TABLE IF EXISTS wcf1_user_achievement;
CREATE TABLE wcf1_user_achievement(
    userID              INT NOT NULL,
    achievementID       INT NOT NULL,
    time                INT,
    UNIQUE KEY uk_userID(userID, achievementID),
	INDEX (time)
) ENGINE=MYISAM;

DROP TABLE IF EXISTS wcf1_user_achievement_event_invoke;
CREATE TABLE wcf1_user_achievement_event_invoke(
    userID              INT NOT NULL,
    className			VARCHAR(127),
    eventName           VARCHAR(127),
	invoke				INT DEFAULT 0,
	time				INT,
    UNIQUE KEY uk_userID(userID, className, eventName)
) ENGINE=MYISAM;
