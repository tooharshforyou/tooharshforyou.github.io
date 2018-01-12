CREATE TABLE IF NOT EXISTS `ads` (
  `adId` int(9) NOT NULL AUTO_INCREMENT,
  `adType` int(1) NOT NULL DEFAULT '1',
  `adImage` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `adTitle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `adText` text COLLATE utf8_bin,
  `adUrl` varchar(255) COLLATE utf8_bin NOT NULL,
  `adStartDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `adEndDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isActive` int(1) NOT NULL DEFAULT '0',
  `dateCreated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`adId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comments` (
  `commentId` int(9) NOT NULL AUTO_INCREMENT,
  `confessId` int(9) NOT NULL,
  `userId` int(9) NOT NULL DEFAULT '0',
  `firstName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `comments` text CHARACTER SET utf8 NOT NULL,
  `commentDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isActive` int(1) NOT NULL DEFAULT '1',
  `commentIp` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `confessions` (
  `confessId` int(9) NOT NULL AUTO_INCREMENT,
  `userId` int(9) NOT NULL DEFAULT '0',
  `firstName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `confessText` text CHARACTER SET utf8 NOT NULL,
  `postDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hasImage` int(1) NOT NULL DEFAULT '0',
  `isActive` int(1) NOT NULL DEFAULT '0',
  `postIp` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`confessId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `dislikes` (
  `dislikeId` int(9) NOT NULL AUTO_INCREMENT,
  `confessId` int(9) NOT NULL,
  `dislikeDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dislikeIp` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`dislikeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `likes` (
  `likeId` int(9) NOT NULL AUTO_INCREMENT,
  `confessId` int(9) NOT NULL,
  `likeDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `likeIp` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`likeId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mailinglist` (
  `listId` int(9) NOT NULL AUTO_INCREMENT,
  `emailAddress` varchar(255) COLLATE utf8_bin NOT NULL,
  `signupDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hash` varchar(32) COLLATE utf8_bin NOT NULL,
  `isActive` int(1) NOT NULL DEFAULT '1',
  `signupIp` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`listId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `modorators` (
  `modId` int(9) NOT NULL AUTO_INCREMENT,
  `userId` int(9) NOT NULL DEFAULT '0',
  `isActive` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`modId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `sitesettings` (
  `installUrl` varchar(100) COLLATE utf8_bin NOT NULL,
  `localization` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'en',
  `siteName` varchar(255) COLLATE utf8_bin NOT NULL,
  `siteEmail` varchar(255) COLLATE utf8_bin NOT NULL,
  `analyticsCode` text COLLATE utf8_bin,
  `uploadPath` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'uploads/',
  `fileTypesAllowed` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'jpg,png,svg',
  `adsPath` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'ads/',
  `adTypesAllowed` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'jpg,png,svg',
  `moderation` int(1) NOT NULL DEFAULT '1',
  `useFilter` int(1) NOT NULL DEFAULT '1',
  `allowRegistrations` int(1) NOT NULL DEFAULT '1',
  `allowUploads` int(1) NOT NULL DEFAULT '1',
  `enableAds` int(1) NOT NULL DEFAULT '1',
  `enableSubscriptions` int(1) NOT NULL DEFAULT '1',
  `aboutUs` longtext COLLATE utf8_bin,
  `siteRules` longtext COLLATE utf8_bin,
  PRIMARY KEY (`installUrl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `uploads` (
  `uploadId` int(9) NOT NULL AUTO_INCREMENT,
  `confessId` int(9) NOT NULL,
  `uploadUrl` varchar(255) COLLATE utf8_bin NOT NULL,
  `uploadDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uploadId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(9) NOT NULL AUTO_INCREMENT,
  `isAdmin` int(1) NOT NULL DEFAULT '0',
  `userEmail` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `userFirst` varchar(255) COLLATE utf8_bin NOT NULL,
  `userLast` varchar(255) COLLATE utf8_bin NOT NULL,
  `joinDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recEmails` int(1) NOT NULL DEFAULT '0',
  `isActive` int(1) NOT NULL DEFAULT '0',
  `hash` varchar(32) COLLATE utf8_bin NOT NULL,
  `lastVisited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `views` (
  `viewId` int(9) NOT NULL AUTO_INCREMENT,
  `confessId` int(9) NOT NULL,
  `viewIp` varchar(20) CHARACTER SET utf8 NOT NULL,
  `viewDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`viewId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
