CREATE TABLE IF NOT EXISTS `phpvms_airmail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `who_to` varchar(20) NOT NULL,
  `who_from` varchar(20) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `read_state` smallint(1) unsigned NOT NULL DEFAULT '0',
  `deleted_state` smallint(4) NOT NULL DEFAULT '0',
  `sent_state` smallint(4) NOT NULL DEFAULT '0',
  `subject` varchar(50) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `notam` smallint(6) NOT NULL DEFAULT '0',
  `sender_folder` int(11) DEFAULT '0',
  `receiver_folder` int(11) DEFAULT '0',
  `thread_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `subject` (`subject`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `phpvms_airmail_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilot_id` int(11) NOT NULL,
  `email` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `phpvms_airmail_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilot_id` int(11) NOT NULL,
  `folder_title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;