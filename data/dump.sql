SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(8) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` text,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `user_id` (`user_id`),
  KEY `completed_at` (`completed_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

INSERT INTO `tasks` VALUES(1, 'a93847e4', NULL, 'foobar', NULL, 'medium', '2013-04-07', '2013-03-31 16:32:49', NULL);
INSERT INTO `tasks` VALUES(2, 'a4fb1264', 1, 'Write summary', 'Write last meeting summary', 'critical', '2013-03-30', '2013-03-31 16:34:11', NULL);
INSERT INTO `tasks` VALUES(12, '8e221440', NULL, 'test', '', 'critical', '2013-04-13', '2013-04-01 10:39:21', NULL);

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `password` binary(32) NOT NULL,
  `role` enum('worker','admin') NOT NULL DEFAULT 'worker',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `users` VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');
INSERT INTO `users` VALUES(2, 'worker', 'b61822e8357dcaff77eaaccf348d9134', 'worker');
