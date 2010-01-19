-- phpMyAdmin SQL Dump
-- version 2.9.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Czas wygenerowania: 21 Wrz 2007, 23:17
-- Wersja serwera: 4.0.27
-- Wersja PHP: 4.4.2
-- 
-- Baza danych: `dellas_orodlin`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `adodb_logsql`
-- 

CREATE TABLE `adodb_logsql` (
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `sql0` varchar(250) NOT NULL default '',
  `sql1` text NOT NULL,
  `params` text NOT NULL,
  `tracer` text NOT NULL,
  `timer` decimal(16,6) NOT NULL default '0.000000'
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `adodb_logsql`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `aktywacja`
-- 

CREATE TABLE `aktywacja` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(15) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `aktyw` int(11) NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `data` date NOT NULL default '0000-00-00',
  `lang` char(3) NOT NULL default 'pl',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=2000 ;


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `alchemy_mill`
-- 

CREATE TABLE `alchemy_mill` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  `illani` int(11) NOT NULL default '0',
  `illanias` int(11) NOT NULL default '0',
  `nutari` int(11) NOT NULL default '0',
  `cost` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `status` char(1) NOT NULL default 'S',
  `dynallca` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default 'pl',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=30 ;

-- 
-- Zrzut danych tabeli `alchemy_mill`
-- 

INSERT INTO `alchemy_mill` VALUES (1, 'mikstura illani', 0, 1, 0, 0, 2000, 1, 'S', 0, 'pl');


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `amarket`
-- 

CREATE TABLE `amarket` (
  `id` int(11) NOT NULL auto_increment,
  `seller` int(11) NOT NULL default '0',
  `type` char(2) NOT NULL default '',
  `number` tinyint(2) NOT NULL default '0',
  `amount` int(3) NOT NULL default '1',
  `cost` int(11) unsigned NOT NULL default '0',
  KEY `type` (`type`),
  KEY `number` (`number`),
  KEY `cost` (`cost`),
  KEY `id` (`id`),
  KEY `seller` (`seller`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `amarket`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `astral`
-- 

CREATE TABLE `astral` (
  `owner` int(11) NOT NULL default '0',
  `type` char(2) NOT NULL default '',
  `number` tinyint(2) NOT NULL default '0',
  `amount` int(3) NOT NULL default '1',
  `location` char(1) NOT NULL default 'V',
  KEY `owner` (`owner`),
  KEY `type` (`type`),
  KEY `location` (`location`),
  KEY `number` (`number`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `astral`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `astral_bank`
-- 

CREATE TABLE `astral_bank` (
  `owner` int(11) NOT NULL default '0',
  `level` tinyint(2) NOT NULL default '0',
  `location` char(1) NOT NULL default 'V',
  KEY `owner` (`owner`),
  KEY `location` (`location`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `astral_bank`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `astral_machine`
-- 

CREATE TABLE `astral_machine` (
  `owner` int(11) NOT NULL default '0',
  `used` int(11) NOT NULL default '0',
  `directed` int(11) NOT NULL default '0',
  `aviable` char(1) NOT NULL default 'N',
  KEY `owner` (`owner`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `astral_machine`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `astral_plans`
-- 

CREATE TABLE `astral_plans` (
  `owner` int(11) NOT NULL default '0',
  `name` char(2) NOT NULL default '',
  `amount` int(11) NOT NULL default '0',
  `location` char(1) NOT NULL default 'V',
  KEY `owner` (`owner`),
  KEY `location` (`location`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `astral_plans`
-- 
-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `bad_words`
-- 

CREATE TABLE `bad_words` (
  `bword` varchar(255) NOT NULL default '',
  KEY `bword` (`bword`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `bad_words`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `ban`
-- 

CREATE TABLE `ban` (
  `type` varchar(10) NOT NULL default '',
  `amount` varchar(50) NOT NULL default '',
  KEY `type` (`type`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `ban`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `ban_mail`
-- 

CREATE TABLE `ban_mail` (
  `id` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL default '0',
  KEY `owner` (`owner`),
  KEY `id` (`id`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `ban_mail`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `bows`
-- 

CREATE TABLE `bows` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `power` int(11) NOT NULL default '0',
  `type` char(1) NOT NULL default 'B',
  `cost` int(11) NOT NULL default '0',
  `minlev` int(2) NOT NULL default '1',
  `zr` int(11) NOT NULL default '0',
  `szyb` int(11) NOT NULL default '0',
  `maxwt` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default 'pl',
  `repair` int(11) NOT NULL default '10',
  KEY `type` (`type`),
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=31 ;

-- 
-- Zrzut danych tabeli `bows`
-- 

INSERT INTO `bows` VALUES (1, 'Łuk ćwiczebny z leszczyny', 0, 'B', 100, 1, 0, 1, 40, 'pl', 2);
INSERT INTO `bows` VALUES (16, 'Strzały ćwiczebne', 1, 'R', 50, 1, 0, 0, 20, 'pl', 0);


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `bridge`
-- 

CREATE TABLE `bridge` (
  `id` int(11) NOT NULL auto_increment,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `lang` char(3) NOT NULL default 'pl',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- 
-- Zrzut danych tabeli `bridge`
-- 

INSERT INTO `bridge` VALUES (1, 'Stolica Abanasyni', 'Łubu-dubu', 'pl');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `bugreport`
-- 

CREATE TABLE `bugreport` (
  `id` int(11) NOT NULL auto_increment,
  `sender` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `type` varchar(20) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `resolution` tinyint(2) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `bugreport`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `bugtrack`
-- 

CREATE TABLE `bugtrack` (
  `id` int(11) NOT NULL auto_increment,
  `type` int(5) NOT NULL default '0',
  `info` varchar(255) NOT NULL default '',
  `amount` int(11) NOT NULL default '1',
  `file` varchar(255) NOT NULL default '',
  `line` int(11) NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `bugtrack`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `categories`
-- 

CREATE TABLE `categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `desc` varchar(255) NOT NULL default '',
  `lang` char(3) NOT NULL default 'pl',
  `perm_write` varchar(255) NOT NULL default 'All;',
  `perm_visit` varchar(255) NOT NULL default 'All;',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `categories`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `changelog`
-- 

CREATE TABLE `changelog` (
  `id` int(11) NOT NULL auto_increment,
  `author` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `lang` char(2) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `changelog`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `chat`
-- 

CREATE TABLE `chat` (
  `id` int(11) NOT NULL auto_increment,
  `lang` char(3) NOT NULL default '',
  `text` varchar(255) NOT NULL default '',
  `sender` varchar(67) NOT NULL default '',
  `senderid` int(11) NOT NULL default '0',
  `ownerid` int(11) NOT NULL default '0',
  `time` double(22,6) NOT NULL default '0.000000',
  `room` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `chat`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `chat_config`
-- 

CREATE TABLE `chat_config` (
  `id` int(11) NOT NULL auto_increment,
  `cisza` char(2) NOT NULL default 'Y',
  `gracz` int(11) NOT NULL default '0',
  `resets` int(11) NOT NULL default '0',
  `room` varchar(30) NOT NULL,
  UNIQUE KEY `gracz` (`gracz`),
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `chat_config`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `chat_users`
-- 

CREATE TABLE `chat_users` (
  `userid` int(11) NOT NULL default '0',
  `time` double(22,6) NOT NULL default '0.000000',
  `room` varchar(10) NOT NULL,
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `chat_users`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `coresbase`
-- 

CREATE TABLE `coresbase` (
  `id` int(11) NOT NULL auto_increment,
  `value` int(11) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `picture` varchar(30) NOT NULL default '',
  `cost` int(11) NOT NULL default '0',
  `atuta` tinyint(1) default NULL,
  `atutd` tinyint(1) default NULL,
  `atuts` tinyint(1) default NULL,
  `maturity` int(11) default NULL,
  `attack` double(11,3) default NULL,
  `defence` double(11,3) default NULL,
  `speed` double(11,3) default NULL,
  `hp` int(11) default NULL,
  `arena` char(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=31 ;

-- 
-- Zrzut danych tabeli `coresbase`
-- 

INSERT INTO `coresbase` VALUES (1, 1, 'lasica', '1.jpg', 1, 0, 0, 1, 1, 10.000, 10.000, 120.000, 20, 'Z');


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `coresplayers`
-- 

CREATE TABLE `coresplayers` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `base` int(11) NOT NULL default '0',
  `petname` varchar(30) default '',
  `attack` double(11,3) default NULL,
  `defence` double(11,3) default NULL,
  `speed` double(11,3) default NULL,
  `status` char(1) default 'N',
  `fights` int(11) default '0',
  `wins` int(11) default '0',
  `loses` int(11) default '0',
  `age` int(11) default '0',
  `rest` int(11) default '0',
  `gender` char(1) default 'M',
  `prize` int(11) default NULL,
  `max_hp` double(11,3) default NULL,
  `hp` double(11,3) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `coresplayers`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `court`
-- 

CREATE TABLE `court` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `lang` char(2) NOT NULL default 'pl',
  `type` varchar(20) NOT NULL default 'case',
  `date` date NOT NULL default '0000-00-00',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `court`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `court_cases`
-- 

CREATE TABLE `court_cases` (
  `id` int(11) NOT NULL auto_increment,
  `textid` int(11) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `court_cases`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `czary`
-- 

CREATE TABLE `czary` (
  `id` int(11) NOT NULL auto_increment,
  `nazwa` varchar(30) NOT NULL default '',
  `gracz` int(11) NOT NULL default '0',
  `cena` int(11) NOT NULL default '0',
  `poziom` int(11) NOT NULL default '1',
  `typ` char(1) NOT NULL default 'B',
  `obr` double(11,2) NOT NULL default '1.00',
  `status` char(1) NOT NULL default 'S',
  `lang` char(3) NOT NULL default 'pl',
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=120 ;

-- 
-- Zrzut danych tabeli `czary`
-- 

INSERT INTO `czary` VALUES (1, 'Piekielne płomienie', 0, 2500000, 65, 'B', 1.65, 'S', 'pl');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `donators`
-- 

CREATE TABLE `donators` (
  `name` varchar(30) NOT NULL default '',
  KEY `name` (`name`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `donators`
-- 

INSERT INTO `donators` VALUES ('Dobroczyńca');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `equipment`
-- 

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `power` int(11) NOT NULL default '0',
  `status` char(1) NOT NULL default 'U',
  `type` char(1) NOT NULL default 'W',
  `cost` int(11) unsigned NOT NULL default '0',
  `minlev` int(2) NOT NULL default '1',
  `zr` int(11) NOT NULL default '0',
  `wt` int(11) NOT NULL default '0',
  `szyb` int(11) NOT NULL default '0',
  `maxwt` int(11) NOT NULL default '0',
  `magic` char(1) NOT NULL default 'N',
  `poison` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '1',
  `twohand` char(1) NOT NULL default 'N',
  `lang` char(3) NOT NULL default 'pl',
  `ptype` char(1) NOT NULL default '',
  `repair` int(11) NOT NULL default '10',
  `location` varchar(20) NOT NULL default 'Altara',
  `wzmocnienie` char(1) NOT NULL default 'N',
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `owner` (`owner`),
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `equipment`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `events`
-- 

CREATE TABLE `events` (
  `id` int(11) NOT NULL auto_increment,
  `text` text NOT NULL,
  `lang` char(3) NOT NULL default 'pl',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `events`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `farm`
-- 

CREATE TABLE `farm` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '0',
  `name` varchar(20) default NULL,
  `age` int(3) NOT NULL default '0',
  `place` varchar(16) NOT NULL default '',
  KEY `owner` (`owner`),
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `farm`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `farms`
-- 

CREATE TABLE `farms` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `lands` int(11) NOT NULL default '0',
  `glasshouse` int(11) NOT NULL default '0',
  `irrigation` int(11) NOT NULL default '0',
  `creeper` int(11) NOT NULL default '0',
  `place` varchar(16) NOT NULL default '',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `farms`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `fight_logs`
-- 

CREATE TABLE `fight_logs` (
  `owner` int(11) NOT NULL default '0',
  `logs` text NOT NULL,
  KEY `owner` (`owner`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `fight_logs`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `halloffame`
-- 

CREATE TABLE `halloffame` (
  `id` int(11) NOT NULL auto_increment,
  `heroid` int(11) NOT NULL default '0',
  `oldname` varchar(100) NOT NULL default '',
  `herorace` varchar(100) NOT NULL default '',
  `newid` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `halloffame`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `herbs`
-- 

CREATE TABLE `herbs` (
  `id` int(11) NOT NULL auto_increment,
  `gracz` int(11) NOT NULL default '0',
  `illani` int(11) NOT NULL default '0',
  `illanias` int(11) NOT NULL default '0',
  `nutari` int(11) NOT NULL default '0',
  `dynallca` int(11) NOT NULL default '0',
  `illani_seeds` int(11) NOT NULL default '0',
  `illanias_seeds` int(11) NOT NULL default '0',
  `nutari_seeds` int(11) NOT NULL default '0',
  `dynallca_seeds` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gracz`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `herbs`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `hmarket`
-- 

CREATE TABLE `hmarket` (
  `id` int(11) NOT NULL auto_increment,
  `seller` int(11) NOT NULL default '0',
  `ilosc` int(11) NOT NULL default '0',
  `cost` int(11) unsigned NOT NULL default '0',
  `nazwa` varchar(30) NOT NULL default '',
  `lang` char(3) NOT NULL default 'pl',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `hmarket`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `houses`
-- 

CREATE TABLE `houses` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `size` int(11) NOT NULL default '1',
  `value` int(11) NOT NULL default '1',
  `bedroom` char(1) NOT NULL default 'N',
  `wardrobe` int(11) NOT NULL default '0',
  `points` int(11) NOT NULL default '10',
  `name` varchar(60) default NULL,
  `used` int(11) NOT NULL default '0',
  `build` int(11) NOT NULL default '0',
  `locator` int(11) NOT NULL default '0',
  `cost` int(11) unsigned NOT NULL default '0',
  `seller` int(11) NOT NULL default '0',
  `location` varchar(20) NOT NULL default 'Altara',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `houses`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `jail`
-- 

CREATE TABLE `jail` (
  `id` int(11) NOT NULL auto_increment,
  `prisoner` int(11) NOT NULL default '0',
  `duration` int(3) NOT NULL default '0',
  `data` date NOT NULL default '0000-00-00',
  `verdict` text NOT NULL,
  `cost` int(11) unsigned NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `jail`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `jeweller`
-- 

CREATE TABLE `jeweller` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `type` char(1) NOT NULL default 'I',
  `cost` int(11) NOT NULL default '0',
  `level` smallint(4) NOT NULL default '0',
  `bonus` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default 'pl',
  KEY `id` (`id`),
  KEY `owner` (`owner`),
  KEY `name` (`name`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;

-- 
-- Zrzut danych tabeli `jeweller`
-- 

INSERT INTO `jeweller` VALUES (1, 0, 'pierścień', 'I', 50, 1, 0, 'pl');


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `jeweller_work`
-- 

CREATE TABLE `jeweller_work` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `n_energy` float(10,2) NOT NULL default '0.00',
  `u_energy` float(10,2) NOT NULL default '0.00',
  `bonus` varchar(30) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `jeweller_work`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `lib_comments`
-- 

CREATE TABLE `lib_comments` (
  `id` int(11) NOT NULL auto_increment,
  `textid` int(11) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  `time` date default NULL,
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Zrzut danych tabeli `lib_comments`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `library`
-- 

CREATE TABLE `library` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `added` char(1) NOT NULL default 'N',
  `type` varchar(20) NOT NULL default '',
  `lang` char(2) NOT NULL default 'pl',
  `author` varchar(50) NOT NULL default '',
  `author_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `library`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `links`
-- 

CREATE TABLE `links` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `file` varchar(255) NOT NULL default '',
  `text` varchar(100) NOT NULL default '',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `links`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `log`
-- 

CREATE TABLE `log` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `log` text NOT NULL,
  `unread` char(1) NOT NULL default 'F',
  `czas` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `log`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `logs`
-- 

CREATE TABLE `logs` (
  `owner` int(11) NOT NULL default '0',
  `log` varchar(255) NOT NULL default '',
  `czas` date NOT NULL default '0000-00-00'
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `logs`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `lost_pass`
-- 

CREATE TABLE `lost_pass` (
  `number` varchar(32) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `newpass` varchar(32) NOT NULL default '',
  `id` int(11) NOT NULL default '0',
  `newemail` varchar(100) NOT NULL default '',
  KEY `number` (`number`),
  KEY `email` (`email`),
  KEY `id` (`id`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `lost_pass`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `lumberjack`
-- 

CREATE TABLE `lumberjack` (
  `owner` int(11) NOT NULL default '0',
  `level` tinyint(2) NOT NULL default '0',
  KEY `owner` (`owner`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `lumberjack`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `mage_items`
-- 

CREATE TABLE `mage_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `power` int(11) NOT NULL default '0',
  `type` char(1) NOT NULL default 'B',
  `cost` int(11) NOT NULL default '0',
  `minlev` int(2) NOT NULL default '1',
  `lang` char(3) NOT NULL default 'pl',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=13 ;

-- 
-- Zrzut danych tabeli `mage_items`
-- 

INSERT INTO `mage_items` VALUES (1, 'elfia różdżka', 6, 'T', 1000, 1, 'pl');


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `mail`
-- 

CREATE TABLE `mail` (
  `id` int(11) NOT NULL auto_increment,
  `sender` varchar(20) NOT NULL default '',
  `senderid` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL default '0',
  `subject` varchar(50) NOT NULL default '',
  `body` text NOT NULL,
  `unread` char(1) NOT NULL default 'F',
  `zapis` char(1) NOT NULL default 'N',
  `send` int(11) NOT NULL default '0',
  `date` datetime default NULL,
  KEY `id` (`id`),
  KEY `owner` (`owner`),
  KEY `unread` (`unread`),
  KEY `zapis` (`zapis`),
  KEY `send` (`send`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `mail`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `mill`
-- 

CREATE TABLE `mill` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  `cost` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '0',
  `level` tinyint(4) NOT NULL default '0',
  `lang` char(2) NOT NULL default 'pl',
  `twohand` char(1) NOT NULL default 'N',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM AUTO_INCREMENT=31 ;

-- 
-- Zrzut danych tabeli `mill`
-- 

INSERT INTO `mill` VALUES (1, 0, 'Łuk ćwiczebny', 'B', 500, 2, 1, 'pl', 'Y');


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `mill_work`
-- 

CREATE TABLE `mill_work` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `n_energy` smallint(4) NOT NULL default '0',
  `u_energy` smallint(4) NOT NULL default '0',
  `mineral` char(1) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `mill_work`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `minerals`
-- 

CREATE TABLE `minerals` (
  `owner` int(11) NOT NULL default '0',
  `copperore` int(11) NOT NULL default '0',
  `zincore` int(11) NOT NULL default '0',
  `tinore` int(11) NOT NULL default '0',
  `ironore` int(11) NOT NULL default '0',
  `coal` int(11) NOT NULL default '0',
  `copper` int(11) NOT NULL default '0',
  `bronze` int(11) NOT NULL default '0',
  `brass` int(11) NOT NULL default '0',
  `iron` int(11) NOT NULL default '0',
  `steel` int(11) NOT NULL default '0',
  `pine` int(11) NOT NULL default '0',
  `hazel` int(11) NOT NULL default '0',
  `yew` int(11) NOT NULL default '0',
  `elm` int(11) NOT NULL default '0',
  `crystal` int(11) NOT NULL default '0',
  `adamantium` int(11) NOT NULL default '0',
  `meteor` int(11) NOT NULL default '0',
  KEY `owner` (`owner`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `minerals`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `mines`
-- 

CREATE TABLE `mines` (
  `owner` int(11) NOT NULL default '0',
  `copper` int(11) NOT NULL default '0',
  `zinc` int(11) NOT NULL default '0',
  `tin` int(11) NOT NULL default '0',
  `iron` int(11) NOT NULL default '0',
  `coal` int(11) NOT NULL default '0',
  KEY `owner` (`owner`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `mines`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `mines_search`
-- 

CREATE TABLE `mines_search` (
  `player` int(11) NOT NULL default '0',
  `days` tinyint(2) NOT NULL default '0',
  `mineral` varchar(30) NOT NULL default '',
  `searchdays` tinyint(2) NOT NULL default '0',
  KEY `player` (`player`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `mines_search`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `monsters`
-- 

CREATE TABLE `monsters` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(25) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `hp` int(11) NOT NULL default '0',
  `agility` double(11,2) NOT NULL default '0.00',
  `strength` double(11,2) NOT NULL default '0.00',
  `speed` double(11,2) NOT NULL default '0.00',
  `endurance` double(11,2) NOT NULL default '0.00',
  `exp1` int(11) NOT NULL default '0',
  `exp2` int(11) NOT NULL default '0',
  `credits1` int(11) NOT NULL default '0',
  `credits2` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `avatar` varchar(36) NOT NULL default '',
  `lang` char(3) NOT NULL default 'pl',
  `location` varchar(20) NOT NULL default 'Altara',
  KEY `id` (`id`),
  KEY `level` (`level`),
  KEY `location` (`location`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=129 ;

-- 
-- Zrzut danych tabeli `monsters`
-- 

INSERT INTO `monsters` VALUES (1, 'Potwór', 1, 15, 5.00, 5.00, 5.00, 5.00, 1, 2, 1, 2, 'opis potwora', 'default.jpg', 'pl', 'Altara');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `news`
-- 

CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `starter` text NOT NULL,
  `title` text NOT NULL,
  `news` text NOT NULL,
  `lang` char(3) NOT NULL default 'pl',
  `added` char(1) NOT NULL default 'Y',
  `show` char(1) NOT NULL default 'Y',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `news`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `news_comments`
-- 

CREATE TABLE `news_comments` (
  `id` int(11) NOT NULL auto_increment,
  `newsid` int(11) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  `time` date default NULL,
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `news_comments`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `newspaper`
-- 

CREATE TABLE `newspaper` (
  `id` int(11) NOT NULL auto_increment,
  `paper_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `lang` char(3) NOT NULL default 'pl',
  `added` char(1) NOT NULL default 'N',
  `author` varchar(50) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `newspaper`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `newspaper_comments`
-- 

CREATE TABLE `newspaper_comments` (
  `id` int(11) NOT NULL auto_increment,
  `textid` int(11) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  `time` date default NULL,
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `newspaper_comments`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `notatnik`
-- 

CREATE TABLE `notatnik` (
  `id` int(11) NOT NULL auto_increment,
  `gracz` int(11) NOT NULL default '0',
  `tekst` text NOT NULL,
  `czas` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `notatnik`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `outpost_monsters`
-- 

CREATE TABLE `outpost_monsters` (
  `id` int(11) NOT NULL auto_increment,
  `outpost` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `power` int(11) NOT NULL default '0',
  `defense` int(11) NOT NULL default '0',
  `moralemod` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `outpost` (`outpost`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `outpost_monsters`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `outpost_veterans`
-- 

CREATE TABLE `outpost_veterans` (
  `id` int(11) NOT NULL auto_increment,
  `outpost` int(11) NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `weapon` varchar(60) default NULL,
  `wpower` int(5) NOT NULL default '0',
  `armor` varchar(60) default NULL,
  `apower` int(5) NOT NULL default '0',
  `helm` varchar(60) default NULL,
  `hpower` int(5) NOT NULL default '0',
  `legs` varchar(60) default NULL,
  `lpower` int(5) NOT NULL default '0',
  `shield` varchar(60) default NULL,
  `spower` int(5) NOT NULL default '0',
  `bow` varchar(60) default NULL,
  `bpower` int(5) NOT NULL default '0',
  `ring1` varchar(60) default NULL,
  `r1power` int(5) NOT NULL default '0',
  `ring2` varchar(60) default NULL,
  `r2power` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `outpost` (`outpost`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `outpost_veterans`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `outposts`
-- 

CREATE TABLE `outposts` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `size` int(11) NOT NULL default '1',
  `warriors` int(11) NOT NULL default '0',
  `archers` int(11) NOT NULL default '0',
  `catapults` int(11) NOT NULL default '0',
  `barricades` int(11) NOT NULL default '0',
  `gold` int(11) NOT NULL default '500',
  `turns` int(11) NOT NULL default '3',
  `battack` tinyint(2) NOT NULL default '0',
  `bdefense` tinyint(2) NOT NULL default '0',
  `btax` tinyint(2) NOT NULL default '0',
  `blost` tinyint(2) NOT NULL default '0',
  `bcost` tinyint(2) NOT NULL default '0',
  `fence` int(11) NOT NULL default '0',
  `barracks` int(11) NOT NULL default '0',
  `fatigue` int(3) NOT NULL default '100',
  `morale` double(11,1) NOT NULL default '0.0',
  `attacks` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `outposts`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `players`
-- 

CREATE TABLE `players` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(15) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `rank` varchar(20) NOT NULL default 'Member',
  `level` int(11) NOT NULL default '1',
  `exp` int(11) NOT NULL default '0',
  `credits` int(11) NOT NULL default '0',
  `energy` double(11,2) NOT NULL default '50.00',
  `max_energy` double(11,2) default '0.00',
  `strength` double(11,3) NOT NULL default '3.000',
  `agility` double(11,3) NOT NULL default '3.000',
  `ap` int(11) NOT NULL default '10',
  `wins` int(11) NOT NULL default '0',
  `losses` int(11) NOT NULL default '0',
  `lastkilled` varchar(60) NOT NULL default '...',
  `lastkilledby` varchar(60) NOT NULL default '...',
  `platinum` int(11) NOT NULL default '50',
  `age` int(11) NOT NULL default '1',
  `logins` int(11) NOT NULL default '0',
  `hp` int(11) unsigned NOT NULL default '15',
  `max_hp` int(11) NOT NULL default '15',
  `bank` int(11) NOT NULL default '1000',
  `lpv` bigint(20) NOT NULL default '0',
  `page` varchar(100) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `ability` double(11,2) NOT NULL default '0.01',
  `tribe` int(11) NOT NULL default '0',
  `profile` text NOT NULL,
  `refs` int(11) NOT NULL default '0',
  `corepass` char(1) NOT NULL default 'N',
  `fight` int(11) NOT NULL default '0',
  `rasa` varchar(20) NOT NULL default '',
  `klasa` varchar(20) NOT NULL default '',
  `inteli` double(11,3) NOT NULL default '3.000',
  `pw` int(11) NOT NULL default '0',
  `atak` double(11,2) NOT NULL default '0.01',
  `unik` double(11,2) NOT NULL default '0.01',
  `magia` double(11,2) NOT NULL default '0.01',
  `immu` char(1) NOT NULL default 'N',
  `pm` int(11) NOT NULL default '3',
  `miejsce` varchar(15) NOT NULL default 'Altara',
  `changed_loc` char(1) NOT NULL default 'N',
  `szyb` double(11,3) NOT NULL default '3.000',
  `wytrz` double(11,3) NOT NULL default '3.000',
  `alchemia` double(11,2) NOT NULL default '0.01',
  `gg` varchar(255) NOT NULL default '0',
  `avatar` varchar(36) NOT NULL default '',
  `wisdom` double(11,3) NOT NULL default '3.000',
  `shoot` double(11,2) NOT NULL default '0.01',
  `tribe_rank` varchar(60) NOT NULL default '',
  `fletcher` double(11,2) NOT NULL default '0.01',
  `deity` varchar(20) default NULL,
  `maps` int(2) NOT NULL default '0',
  `rest` char(1) NOT NULL default 'N',
  `crime` int(11) NOT NULL default '1',
  `gender` char(1) default NULL,
  `bridge` char(1) NOT NULL default 'N',
  `temp` int(11) NOT NULL default '0',
  `style` varchar(100) NOT NULL default 'orodlin.css',
  `leadership` double(11,2) NOT NULL default '0.01',
  `graphic` varchar(255) NOT NULL default 'default',
  `lang` char(3) NOT NULL default 'pl',
  `seclang` char(3) default NULL,
  `forum_time` bigint(20) NOT NULL default '0',
  `tforum_time` bigint(20) NOT NULL default '0',
  `bless` varchar(30) NOT NULL default '',
  `blessval` int(11) NOT NULL default '0',
  `antidote_d` int(2) default NULL,
  `freeze` tinyint(3) NOT NULL default '0',
  `breeding` double(11,2) NOT NULL default '0.01',
  `battleloga` tinyint(1) default '0',
  `battlelogd` tinyint(1) default '0',
  `houserest` char(1) NOT NULL default 'N',
  `poll` char(1) NOT NULL default 'N',
  `mining` double(11,2) NOT NULL default '0.01',
  `lumberjack` double(11,2) NOT NULL default '0.01',
  `herbalist` double(11,2) NOT NULL default '0.01',
  `astralcrime` char(1) NOT NULL default 'Y',
  `changedeity` int(11) NOT NULL default '0',
  `jeweller` double(11,2) NOT NULL default '0.01',
  `graphbar` char(1) NOT NULL default 'N',
  `graphstyle` char(1) NOT NULL default 'Y',
  `ranking` double(9,3) default NULL,
  `antidote_n` int(2) default NULL,
  `antidote_i` int(2) default NULL,
  `opis` varchar(25) NOT NULL default '',
  `resurect` int(2) default NULL,
  `hutnictwo` double(11,2) NOT NULL default '0.01',
  `overlib` tinyint(1) default '1',
  `mailinfo` tinyint(1) default '1',
  `loginfo` tinyint(1) default '1',
  KEY `user` (`user`),
  KEY `email` (`email`),
  KEY `lpv` (`lpv`),
  KEY `page` (`page`),
  KEY `refs` (`refs`),
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `players`
-- 
INSERT INTO `players` (`user`, `email`, `pass`, `lang`, `ip`, `profile`, `rank`) VALUES ("Administrator", "admin@xxx.pl", "e10adc3949ba59abbe56e057f20f883e", "pl", "00.00.00.00", "profil", "Admin");

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `pmarket`
-- 

CREATE TABLE `pmarket` (
  `id` int(11) NOT NULL auto_increment,
  `seller` int(11) NOT NULL default '0',
  `ilosc` int(11) NOT NULL default '0',
  `cost` int(11) unsigned NOT NULL default '0',
  `nazwa` varchar(20) NOT NULL default 'mithril',
  `lang` char(3) NOT NULL default 'pl',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `pmarket`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `polls`
-- 

CREATE TABLE `polls` (
  `id` int(11) NOT NULL default '0',
  `poll` varchar(255) NOT NULL default '',
  `votes` int(11) NOT NULL default '0',
  `lang` char(2) NOT NULL default 'pl',
  `days` smallint(3) NOT NULL default '7',
  `members` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `polls`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `polls_comments`
-- 

CREATE TABLE `polls_comments` (
  `id` int(11) NOT NULL auto_increment,
  `pollid` int(11) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  `time` date default NULL,
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `polls_comments`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `potions`
-- 

CREATE TABLE `potions` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  `efect` varchar(30) NOT NULL default '',
  `status` char(1) NOT NULL default 'S',
  `power` int(3) NOT NULL default '100',
  `amount` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default 'pl',
  `cost` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=30 ;

-- 
-- Zrzut danych tabeli `potions`
-- 

INSERT INTO `potions` VALUES (1, 0, 'mikstura', 'M', 'działanie miktury', 'A', 500, 2400, 'pl', 0);


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `questaction`
-- 

CREATE TABLE `questaction` (
  `id` int(11) NOT NULL auto_increment,
  `player` int(11) NOT NULL default '0',
  `quest` int(11) NOT NULL default '0',
  `action` varchar(20) NOT NULL default '',
  KEY `id` (`id`),
  KEY `player` (`player`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `questaction`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `quests`
-- 

CREATE TABLE `quests` (
  `id` int(11) NOT NULL auto_increment,
  `qid` int(11) NOT NULL default '2',
  `location` varchar(20) NOT NULL default 'grid.php',
  `name` varchar(20) NOT NULL default '',
  `option` varchar(20) NOT NULL default '0',
  `text` text NOT NULL,
  `lang` char(3) NOT NULL default 'pl',
  KEY `id` (`id`),
  KEY `qid` (`qid`),
  KEY `location` (`location`),
  KEY `name` (`name`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=173 ;

-- 
-- Zrzut danych tabeli `quests`
-- 

INSERT INTO `quests` VALUES (1, 1, 'grid.php', 'start', '0', 'Podążasz ostrożnie ciemnym, wąskim korytarzem. Co chwila opadają na ciebie strzępy pajęczyn, które strzepujesz z siebie z wielkim obrzydzeniem. Twoje kroki dźwięczą głucho niepokojąc twoje zmysły. \r\n- <i>Jeśli ja słysze to pewnie słyszy to także każde inne stworzenie w okolicy</i> - wzdrygasz się na samą myśł o spotkaniu z potworem. Jednak wiesz, że musisz iść dalej. Trzymasz broń w pogotowiu na wypadek zasadzki i dalej ruszasz powoli w mrok korytarza. Twoja pochodnia rozświetla pobliskie ściany, na których gdzie nigdzie widać kawałki nisternie zdobionego tynku. Masz wrażenie, że korytasz ciągnie się kilometrami... ', 'pl');
INSERT INTO `quests` VALUES (2, 1, 'grid.php', '1', '0', ' ', 'pl');
INSERT INTO `quests` VALUES (3, 1, 'grid.php', '2', '0', 'Po kilku godzinach błądzenia nie napotykasz niczego inetresującego.', 'pl');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `replies`
-- 

CREATE TABLE `replies` (
  `id` int(11) NOT NULL auto_increment,
  `starter` varchar(30) NOT NULL default '',
  `topic_id` text NOT NULL,
  `body` text NOT NULL,
  `gracz` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default 'pl',
  `w_time` bigint(20) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `replies`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `reputation`
-- 

CREATE TABLE `reputation` (
  `id` int(11) NOT NULL auto_increment,
  `player_id` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `points` int(5) NOT NULL default '0',
  `date` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `player_id` (`player_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `reputation`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `reset`
-- 

CREATE TABLE `reset` (
  `id` int(11) NOT NULL auto_increment,
  `player` int(11) NOT NULL default '0',
  `code` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `reset`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `rings`
-- 

CREATE TABLE `rings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `amount` int(11) NOT NULL default '0',
  `lang` char(2) NOT NULL default 'pl',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

-- 
-- Zrzut danych tabeli `rings`
-- 

INSERT INTO `rings` VALUES (1, 'pierścień nowicjusza siły', 28, 'pl');
INSERT INTO `rings` VALUES (2, 'pierścień nowicjusza zręczności', 28, 'pl');
INSERT INTO `rings` VALUES (3, 'pierścień nowicjusza inteligencji', 28, 'pl');
INSERT INTO `rings` VALUES (4, 'pierścień nowicjusza siły woli', 28, 'pl');
INSERT INTO `rings` VALUES (5, 'pierścień nowicjusza szybkości', 28, 'pl');
INSERT INTO `rings` VALUES (6, 'pierścień nowicjusza wytrzymałości', 28, 'pl');


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `sessions`
-- 

CREATE TABLE `sessions` (
  `sesskey` varchar(32) binary NOT NULL default '',
  `expiry` int(11) unsigned NOT NULL default '0',
  `expireref` varchar(64) default NULL,
  `data` longtext,
  PRIMARY KEY  (`sesskey`),
  KEY `expiry` (`expiry`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `sessions`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `settings`
-- 

CREATE TABLE `settings` (
  `setting` varchar(255) NOT NULL default '',
  `value` varchar(255) default NULL,
  KEY `setting` (`setting`),
  KEY `value` (`value`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `settings`
-- 

INSERT INTO `settings` VALUES ('maps', '20');
INSERT INTO `settings` VALUES ('item', NULL);
INSERT INTO `settings` VALUES ('player', NULL);
INSERT INTO `settings` VALUES ('open', 'Y');
INSERT INTO `settings` VALUES ('reset', 'N');
INSERT INTO `settings` VALUES ('close_reason', '');
INSERT INTO `settings` VALUES ('copper', '35');
INSERT INTO `settings` VALUES ('iron', '71');
INSERT INTO `settings` VALUES ('coal', '32');
INSERT INTO `settings` VALUES ('mithril', '0');
INSERT INTO `settings` VALUES ('adamantium', '10');
INSERT INTO `settings` VALUES ('meteor', '35');
INSERT INTO `settings` VALUES ('crystal', '10');
INSERT INTO `settings` VALUES ('illani', '35');
INSERT INTO `settings` VALUES ('illanias', '35');
INSERT INTO `settings` VALUES ('nutari', '35');
INSERT INTO `settings` VALUES ('dynallca', '35');
INSERT INTO `settings` VALUES ('register', 'N');
INSERT INTO `settings` VALUES ('close_register', 'z przyczyn technicznych');
INSERT INTO `settings` VALUES ('poll', 'N');
INSERT INTO `settings` VALUES ('age', '1');
INSERT INTO `settings` VALUES ('day', '266');
INSERT INTO `settings` VALUES ('copperore', '36');
INSERT INTO `settings` VALUES ('zincore', '71');
INSERT INTO `settings` VALUES ('tinore', '71');
INSERT INTO `settings` VALUES ('ironore', '71');
INSERT INTO `settings` VALUES ('bronze', '71');
INSERT INTO `settings` VALUES ('brass', '71');
INSERT INTO `settings` VALUES ('steel', '71');
INSERT INTO `settings` VALUES ('pine', '71');
INSERT INTO `settings` VALUES ('hazel', '71');
INSERT INTO `settings` VALUES ('yew', '71');
INSERT INTO `settings` VALUES ('elm', '71');
INSERT INTO `settings` VALUES ('illani_seeds', '0');
INSERT INTO `settings` VALUES ('illanias_seeds', '0');
INSERT INTO `settings` VALUES ('nutari_seeds', '0');
INSERT INTO `settings` VALUES ('dynallca_seeds', '0');
INSERT INTO `settings` VALUES ('caravan', 'N');
INSERT INTO `settings` VALUES ('metakeywords', NULL);
INSERT INTO `settings` VALUES ('metadescr', NULL);
INSERT INTO `settings` VALUES ('tribe', NULL);

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `smelter`
-- 

CREATE TABLE `smelter` (
  `owner` int(11) NOT NULL default '0',
  `level` tinyint(2) NOT NULL default '0',
  KEY `owner` (`owner`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `smelter`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `smith`
-- 

CREATE TABLE `smith` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  `cost` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '0',
  `level` tinyint(4) NOT NULL default '0',
  `lang` char(2) NOT NULL default 'pl',
  `twohand` char(1) NOT NULL default 'N',
  KEY `id` (`id`),
  KEY `owner` (`owner`)
) TYPE=MyISAM AUTO_INCREMENT=78 ;

-- 
-- Zrzut danych tabeli `smith`
-- 

INSERT INTO `smith` VALUES (2, 0, 'Zbroja', 'A', 4000, 8, 3, 'pl', 'N');
INSERT INTO `smith` VALUES (18, 0, 'Tarcza', 'S', 500, 1, 1, 'pl', 'N');
INSERT INTO `smith` VALUES (33, 0, 'Hełm', 'H', 500, 1, 1, 'pl', 'N');
INSERT INTO `smith` VALUES (48, 0, 'Nagolenniki', 'L', 500, 1, 1, 'pl', 'N');
INSERT INTO `smith` VALUES (63, 0, 'Miecz', 'W', 500, 1, 1, 'pl', 'N');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `smith_work`
-- 

CREATE TABLE `smith_work` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `n_energy` smallint(4) NOT NULL default '0',
  `u_energy` smallint(4) NOT NULL default '0',
  `mineral` varchar(10) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `smith_work`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `team`
-- 

CREATE TABLE `team` (
  `tid` int(11) NOT NULL auto_increment,
  `gameid` int(11) NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `function` text NOT NULL,
  `avatar` varchar(70) NOT NULL default '',
  `contact` varchar(70) NOT NULL default '',
  KEY `tid` (`tid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `team`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `topics`
-- 

CREATE TABLE `topics` (
  `id` int(11) NOT NULL auto_increment,
  `topic` text NOT NULL,
  `body` text NOT NULL,
  `starter` varchar(30) NOT NULL default '',
  `gracz` int(11) NOT NULL default '0',
  `cat_id` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default 'pl',
  `w_time` bigint(20) NOT NULL default '0',
  `sticky` char(1) NOT NULL default 'N',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `topics`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `trained_stats`
-- 

CREATE TABLE `trained_stats` (
  `id` int(11) NOT NULL default '0',
  `strength` double(11,3) NOT NULL default '0.000',
  `agility` double(11,3) NOT NULL default '0.000',
  `inteli` double(11,3) NOT NULL default '0.000',
  `szyb` double(11,3) NOT NULL default '0.000',
  `wytrz` double(11,3) NOT NULL default '0.000',
  `wisdom` double(11,3) NOT NULL default '0.000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `trained_stats`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_mag`
-- 

CREATE TABLE `tribe_mag` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `efect` varchar(30) NOT NULL default '',
  `power` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '0',
  `type` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `klan` (`owner`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_mag`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_oczek`
-- 

CREATE TABLE `tribe_oczek` (
  `id` int(11) NOT NULL auto_increment,
  `gracz` int(11) NOT NULL default '0',
  `klan` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gracz`),
  KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_oczek`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_perm`
-- 

CREATE TABLE `tribe_perm` (
  `id` int(11) NOT NULL auto_increment,
  `tribe` int(11) NOT NULL default '0',
  `player` int(11) NOT NULL default '0',
  `messages` smallint(2) NOT NULL default '0',
  `wait` smallint(2) NOT NULL default '0',
  `kick` smallint(2) NOT NULL default '0',
  `army` smallint(2) NOT NULL default '0',
  `attack` smallint(2) NOT NULL default '0',
  `loan` smallint(2) NOT NULL default '0',
  `armory` smallint(2) NOT NULL default '0',
  `warehouse` smallint(2) NOT NULL default '0',
  `bank` smallint(2) NOT NULL default '0',
  `herbs` smallint(2) NOT NULL default '0',
  `forum` smallint(2) NOT NULL default '0',
  `mail` smallint(2) NOT NULL default '0',
  `ranks` smallint(2) NOT NULL default '0',
  `info` smallint(2) NOT NULL default '0',
  `astralvault` smallint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_perm`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_rank`
-- 

CREATE TABLE `tribe_rank` (
  `id` int(11) NOT NULL auto_increment,
  `tribe_id` int(11) NOT NULL default '0',
  `rank1` varchar(60) NOT NULL default '',
  `rank2` varchar(60) NOT NULL default '',
  `rank3` varchar(60) NOT NULL default '',
  `rank4` varchar(60) NOT NULL default '',
  `rank5` varchar(60) NOT NULL default '',
  `rank6` varchar(60) NOT NULL default '',
  `rank7` varchar(60) NOT NULL default '',
  `rank8` varchar(60) NOT NULL default '',
  `rank9` varchar(60) NOT NULL default '',
  `rank10` varchar(60) NOT NULL default '',
  `tag_sufix` varchar(5) NOT NULL default '',
  `tag_prefix_1` varchar(4) NOT NULL default '',
  `tag_prefix_2` varchar(4) NOT NULL default '',
  `tag_prefix_3` varchar(4) NOT NULL default '',
  `tag_prefix_4` varchar(4) NOT NULL default '',
  `tag_prefix_5` varchar(4) NOT NULL default '',
  `tag_prefix_6` varchar(4) NOT NULL default '',
  `tag_prefix_7` varchar(4) NOT NULL default '',
  `tag_prefix_8` varchar(4) NOT NULL default '',
  `tag_prefix_9` varchar(4) NOT NULL default '',
  `tag_prefix_10` varchar(4) NOT NULL default '',
  UNIQUE KEY `tribe_id` (`tribe_id`),
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_rank`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_replies`
-- 

CREATE TABLE `tribe_replies` (
  `id` int(11) NOT NULL auto_increment,
  `starter` varchar(30) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `body` text NOT NULL,
  `w_time` bigint(20) NOT NULL default '0',
  `pid` int(11) NOT NULL default '0',
  UNIQUE KEY `id` (`id`),
  KEY `topic_id` (`topic_id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_replies`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_topics`
-- 

CREATE TABLE `tribe_topics` (
  `id` int(11) NOT NULL auto_increment,
  `topic` text NOT NULL,
  `body` text NOT NULL,
  `starter` varchar(30) NOT NULL default '',
  `tribe` int(11) NOT NULL default '0',
  `w_time` bigint(20) NOT NULL default '0',
  `sticky` char(1) NOT NULL default 'N',
  `pid` int(11) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_topics`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribe_zbroj`
-- 

CREATE TABLE `tribe_zbroj` (
  `id` int(11) NOT NULL auto_increment,
  `klan` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `power` int(11) NOT NULL default '0',
  `wt` int(11) NOT NULL default '0',
  `maxwt` int(11) NOT NULL default '0',
  `zr` int(11) NOT NULL default '0',
  `szyb` int(11) NOT NULL default '0',
  `minlev` int(11) NOT NULL default '0',
  `type` char(1) NOT NULL default '',
  `magic` char(1) NOT NULL default 'N',
  `poison` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '1',
  `twohand` char(1) NOT NULL default 'N',
  `ptype` char(1) NOT NULL default '',
  `repair` int(11) NOT NULL default '10',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `klan` (`klan`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribe_zbroj`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `tribes`
-- 

CREATE TABLE `tribes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  `credits` int(11) NOT NULL default '0',
  `platinum` int(11) NOT NULL default '0',
  `public_msg` text NOT NULL,
  `private_msg` text NOT NULL,
  `hospass` char(1) NOT NULL default 'N',
  `atak` char(1) NOT NULL default 'N',
  `wygr` int(11) NOT NULL default '0',
  `przeg` int(11) NOT NULL default '0',
  `zolnierze` int(11) NOT NULL default '0',
  `forty` int(11) NOT NULL default '0',
  `copper` int(11) NOT NULL default '0',
  `illani` int(11) NOT NULL default '0',
  `illanias` int(11) NOT NULL default '0',
  `nutari` int(11) NOT NULL default '0',
  `logo` varchar(36) NOT NULL default '',
  `www` varchar(60) NOT NULL default '',
  `dynallca` int(11) NOT NULL default '0',
  `ilani_seeds` int(11) NOT NULL default '0',
  `illanias_seeds` int(11) NOT NULL default '0',
  `nutari_seeds` int(11) NOT NULL default '0',
  `dynallca_seeds` int(11) NOT NULL default '0',
  `copperore` int(11) NOT NULL default '0',
  `zincore` int(11) NOT NULL default '0',
  `tinore` int(11) NOT NULL default '0',
  `ironore` int(11) NOT NULL default '0',
  `coal` int(11) NOT NULL default '0',
  `bronze` int(11) NOT NULL default '0',
  `brass` int(11) NOT NULL default '0',
  `iron` int(11) NOT NULL default '0',
  `steel` int(11) NOT NULL default '0',
  `pine` int(11) NOT NULL default '0',
  `hazel` int(11) NOT NULL default '0',
  `yew` int(11) NOT NULL default '0',
  `elm` int(11) NOT NULL default '0',
  `crystal` int(11) NOT NULL default '0',
  `adamantium` int(11) NOT NULL default '0',
  `meteor` int(11) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `tribes`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `upd_comments`
-- 

CREATE TABLE `upd_comments` (
  `id` int(11) NOT NULL auto_increment,
  `updateid` int(11) NOT NULL default '0',
  `author` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  `time` date default NULL,
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `upd_comments`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `updates`
-- 

CREATE TABLE `updates` (
  `id` int(11) NOT NULL auto_increment,
  `starter` text NOT NULL,
  `title` text NOT NULL,
  `updates` text NOT NULL,
  `time` date default NULL,
  `lang` char(3) NOT NULL default 'pl',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `updates`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `vault`
-- 

CREATE TABLE `vault` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(7) NOT NULL default '0',
  `type` tinyint(2) NOT NULL default '0',
  `time` tinyint(2) NOT NULL default '0',
  `amount` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `vault`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `warehouse`
-- 

CREATE TABLE `warehouse` (
  `reset` smallint(3) NOT NULL default '0',
  `mineral` varchar(30) NOT NULL default '',
  `sell` bigint(22) NOT NULL default '0',
  `buy` bigint(22) NOT NULL default '0',
  `cost` double(20,3) NOT NULL default '0.000',
  `amount` bigint(22) NOT NULL default '0',
  KEY `reset` (`reset`),
  KEY `mineral` (`mineral`)
) TYPE=MyISAM;

-- 
-- Zrzut danych tabeli `warehouse`
-- 

INSERT INTO `warehouse` VALUES (9, 'illani_seeds', 0, 0, 6212335749318.900, 125);
INSERT INTO `warehouse` VALUES (1, 'zincore', 0, 0, 49152.000, 0);
INSERT INTO `warehouse` VALUES (1, 'tinore', 0, 0, 49154.000, 0);
INSERT INTO `warehouse` VALUES (1, 'ironore', 0, 0, 49158.000, 0);
INSERT INTO `warehouse` VALUES (1, 'copper', 0, 0, 324.960, 0);
INSERT INTO `warehouse` VALUES (1, 'bronze', 0, 0, 49165.000, 0);
INSERT INTO `warehouse` VALUES (1, 'brass', 0, 0, 49169.000, 0);
INSERT INTO `warehouse` VALUES (1, 'iron', 0, 0, 49162.000, 0);
INSERT INTO `warehouse` VALUES (1, 'steel', 0, 0, 49197.000, 0);
INSERT INTO `warehouse` VALUES (1, 'coal', 0, 0, 223.738, 0);
INSERT INTO `warehouse` VALUES (1, 'adamantium', 0, 0, 45.632, 0);
INSERT INTO `warehouse` VALUES (1, 'meteor', 0, 0, 528.446, 0);
INSERT INTO `warehouse` VALUES (1, 'crystal', 0, 0, 58.233, 0);
INSERT INTO `warehouse` VALUES (1, 'pine', 0, 0, 49154.000, 0);
INSERT INTO `warehouse` VALUES (1, 'hazel', 0, 0, 49157.000, 0);
INSERT INTO `warehouse` VALUES (1, 'yew', 0, 0, 49159.000, 0);
INSERT INTO `warehouse` VALUES (1, 'elm', 0, 0, 49157.000, 0);
INSERT INTO `warehouse` VALUES (1, 'mithril', 0, 10026, 35.848, 0);
INSERT INTO `warehouse` VALUES (1, 'illani', 0, 0, 359.112, 0);
INSERT INTO `warehouse` VALUES (1, 'illanias', 0, 0, 343.461, 0);
INSERT INTO `warehouse` VALUES (1, 'nutari', 0, 0, 361.959, 0);
INSERT INTO `warehouse` VALUES (1, 'dynallca', 0, 0, 364.362, 0);
INSERT INTO `warehouse` VALUES (1, 'illanias_seeds', 0, 0, 22707294487.947, 10);
INSERT INTO `warehouse` VALUES (1, 'copperore', 0, 0, 417.291, 0);
INSERT INTO `warehouse` VALUES (10, 'zincore', 0, 0, 14336.000, 0);
INSERT INTO `warehouse` VALUES (10, 'tinore', 0, 0, 14338.000, 0);
INSERT INTO `warehouse` VALUES (10, 'ironore', 0, 0, 14342.000, 0);
INSERT INTO `warehouse` VALUES (10, 'copper', 0, 0, 100.960, 0);
INSERT INTO `warehouse` VALUES (10, 'bronze', 0, 0, 14349.000, 0);
INSERT INTO `warehouse` VALUES (10, 'brass', 0, 0, 14353.000, 0);
INSERT INTO `warehouse` VALUES (10, 'iron', 0, 0, 14346.000, 0);
INSERT INTO `warehouse` VALUES (10, 'steel', 0, 0, 14381.000, 0);
INSERT INTO `warehouse` VALUES (10, 'coal', 0, 0, 63.738, 0);
INSERT INTO `warehouse` VALUES (10, 'adamantium', 0, 0, 36.242, 0);
INSERT INTO `warehouse` VALUES (10, 'meteor', 0, 0, 304.446, 0);
INSERT INTO `warehouse` VALUES (10, 'crystal', 0, 0, 49.754, 0);
INSERT INTO `warehouse` VALUES (10, 'pine', 0, 0, 14338.000, 0);
INSERT INTO `warehouse` VALUES (10, 'hazel', 0, 0, 14341.000, 0);
INSERT INTO `warehouse` VALUES (10, 'yew', 0, 0, 14343.000, 0);
INSERT INTO `warehouse` VALUES (10, 'elm', 0, 0, 14341.000, 0);
INSERT INTO `warehouse` VALUES (10, 'mithril', 0, 0, 35.211, 10026);
INSERT INTO `warehouse` VALUES (10, 'illani', 0, 0, 135.112, 0);
INSERT INTO `warehouse` VALUES (10, 'illanias', 0, 0, 119.461, 0);
INSERT INTO `warehouse` VALUES (10, 'nutari', 0, 0, 137.959, 0);
INSERT INTO `warehouse` VALUES (10, 'dynallca', 0, 0, 140.362, 0);
INSERT INTO `warehouse` VALUES (10, 'illani_seeds', 0, 0, 6543204208946.700, 125);
INSERT INTO `warehouse` VALUES (10, 'nutari_seeds', 0, 0, 2615248478772.300, 80167);
INSERT INTO `warehouse` VALUES (10, 'dynallca_seeds', 0, 0, 4397549562876.900, 65320);
INSERT INTO `warehouse` VALUES (10, 'copperore', 0, 0, 145.291, 0);
INSERT INTO `warehouse` VALUES (9, 'zincore', 0, 0, 16384.000, 0);
INSERT INTO `warehouse` VALUES (9, 'tinore', 0, 0, 16386.000, 0);
INSERT INTO `warehouse` VALUES (9, 'ironore', 0, 0, 16390.000, 0);
INSERT INTO `warehouse` VALUES (9, 'copper', 0, 0, 116.960, 0);
INSERT INTO `warehouse` VALUES (9, 'bronze', 0, 0, 16397.000, 0);
INSERT INTO `warehouse` VALUES (9, 'brass', 0, 0, 16401.000, 0);
INSERT INTO `warehouse` VALUES (9, 'iron', 0, 0, 16394.000, 0);
INSERT INTO `warehouse` VALUES (9, 'steel', 0, 0, 16429.000, 0);
INSERT INTO `warehouse` VALUES (9, 'coal', 0, 0, 71.738, 0);
INSERT INTO `warehouse` VALUES (9, 'meteor', 0, 0, 320.446, 0);
INSERT INTO `warehouse` VALUES (9, 'pine', 0, 0, 16386.000, 0);
INSERT INTO `warehouse` VALUES (9, 'hazel', 0, 0, 16389.000, 0);
INSERT INTO `warehouse` VALUES (9, 'yew', 0, 0, 16391.000, 0);
INSERT INTO `warehouse` VALUES (9, 'elm', 0, 0, 16389.000, 0);
INSERT INTO `warehouse` VALUES (9, 'illani', 0, 0, 151.112, 0);
INSERT INTO `warehouse` VALUES (9, 'illanias', 0, 0, 135.461, 0);
INSERT INTO `warehouse` VALUES (9, 'nutari', 0, 0, 153.959, 0);
INSERT INTO `warehouse` VALUES (9, 'dynallca', 0, 0, 156.362, 0);
INSERT INTO `warehouse` VALUES (9, 'illanias_seeds', 0, 0, 22703365280.451, 10);
INSERT INTO `warehouse` VALUES (9, 'nutari_seeds', 0, 0, 2603187745335.300, 80167);
INSERT INTO `warehouse` VALUES (9, 'dynallca_seeds', 0, 0, 4397763597154.400, 65320);
INSERT INTO `warehouse` VALUES (8, 'zincore', 0, 0, 18432.000, 0);
INSERT INTO `warehouse` VALUES (8, 'tinore', 0, 0, 18434.000, 0);
INSERT INTO `warehouse` VALUES (8, 'ironore', 0, 0, 18438.000, 0);
INSERT INTO `warehouse` VALUES (8, 'copper', 0, 0, 132.960, 0);
INSERT INTO `warehouse` VALUES (8, 'bronze', 0, 0, 18445.000, 0);
INSERT INTO `warehouse` VALUES (8, 'brass', 0, 0, 18449.000, 0);
INSERT INTO `warehouse` VALUES (8, 'iron', 0, 0, 18442.000, 0);
INSERT INTO `warehouse` VALUES (8, 'steel', 0, 0, 18477.000, 0);
INSERT INTO `warehouse` VALUES (8, 'coal', 0, 0, 79.738, 0);
INSERT INTO `warehouse` VALUES (8, 'meteor', 0, 0, 336.446, 0);
INSERT INTO `warehouse` VALUES (8, 'crystal', 0, 0, 52.658, 0);
INSERT INTO `warehouse` VALUES (8, 'pine', 0, 0, 18434.000, 0);
INSERT INTO `warehouse` VALUES (8, 'hazel', 0, 0, 18437.000, 0);
INSERT INTO `warehouse` VALUES (8, 'yew', 0, 0, 18439.000, 0);
INSERT INTO `warehouse` VALUES (8, 'elm', 0, 0, 18437.000, 0);
INSERT INTO `warehouse` VALUES (8, 'mithril', 0, 0, 36.272, 10026);
INSERT INTO `warehouse` VALUES (8, 'illani', 0, 0, 167.112, 0);
INSERT INTO `warehouse` VALUES (8, 'illanias', 0, 0, 151.461, 0);
INSERT INTO `warehouse` VALUES (8, 'nutari', 0, 0, 169.959, 0);
INSERT INTO `warehouse` VALUES (8, 'dynallca', 0, 0, 172.362, 0);
INSERT INTO `warehouse` VALUES (8, 'illani_seeds', 0, 0, 6154911171795.200, 125);
INSERT INTO `warehouse` VALUES (8, 'illanias_seeds', 0, 0, 22706107080.670, 10);
INSERT INTO `warehouse` VALUES (8, 'nutari_seeds', 0, 0, 2593162908112.300, 80167);
INSERT INTO `warehouse` VALUES (8, 'copperore', 0, 0, 177.291, 0);
INSERT INTO `warehouse` VALUES (7, 'zincore', 0, 0, 20480.000, 0);
INSERT INTO `warehouse` VALUES (7, 'tinore', 0, 0, 20482.000, 0);
INSERT INTO `warehouse` VALUES (7, 'ironore', 0, 0, 20486.000, 0);
INSERT INTO `warehouse` VALUES (7, 'copper', 0, 0, 148.960, 0);
INSERT INTO `warehouse` VALUES (7, 'bronze', 0, 0, 20493.000, 0);
INSERT INTO `warehouse` VALUES (7, 'brass', 0, 0, 20497.000, 0);
INSERT INTO `warehouse` VALUES (7, 'iron', 0, 0, 20490.000, 0);
INSERT INTO `warehouse` VALUES (7, 'steel', 0, 0, 20525.000, 0);
INSERT INTO `warehouse` VALUES (8, 'adamantium', 0, 0, 37.679, 0);
INSERT INTO `warehouse` VALUES (7, 'adamantium', 0, 0, 40.385, 0);
INSERT INTO `warehouse` VALUES (7, 'meteor', 0, 0, 352.446, 0);
INSERT INTO `warehouse` VALUES (7, 'crystal', 0, 0, 52.953, 0);
INSERT INTO `warehouse` VALUES (7, 'pine', 0, 0, 20482.000, 0);
INSERT INTO `warehouse` VALUES (7, 'hazel', 0, 0, 20485.000, 0);
INSERT INTO `warehouse` VALUES (7, 'yew', 0, 0, 20487.000, 0);
INSERT INTO `warehouse` VALUES (7, 'elm', 0, 0, 20485.000, 0);
INSERT INTO `warehouse` VALUES (7, 'mithril', 0, 0, 36.220, 10026);
INSERT INTO `warehouse` VALUES (7, 'illani', 0, 0, 183.112, 0);
INSERT INTO `warehouse` VALUES (7, 'illanias', 0, 0, 167.461, 0);
INSERT INTO `warehouse` VALUES (7, 'nutari', 0, 0, 185.959, 0);
INSERT INTO `warehouse` VALUES (1, 'nutari_seeds', 0, 0, 2575372408813.300, 80167);
INSERT INTO `warehouse` VALUES (9, 'adamantium', 0, 0, 36.001, 0);
INSERT INTO `warehouse` VALUES (7, 'dynallca_seeds', 0, 0, 4398836178110.400, 65320);
INSERT INTO `warehouse` VALUES (6, 'copperore', 0, 0, 225.291, 0);
INSERT INTO `warehouse` VALUES (6, 'zincore', 0, 0, 24576.000, 0);
INSERT INTO `warehouse` VALUES (6, 'tinore', 0, 0, 24578.000, 0);
INSERT INTO `warehouse` VALUES (6, 'ironore', 0, 0, 24582.000, 0);
INSERT INTO `warehouse` VALUES (6, 'copper', 0, 0, 164.960, 0);
INSERT INTO `warehouse` VALUES (6, 'bronze', 0, 0, 24589.000, 0);
INSERT INTO `warehouse` VALUES (6, 'brass', 0, 0, 24593.000, 0);
INSERT INTO `warehouse` VALUES (6, 'iron', 0, 0, 24586.000, 0);
INSERT INTO `warehouse` VALUES (6, 'steel', 0, 0, 24621.000, 0);
INSERT INTO `warehouse` VALUES (9, 'mithril', 0, 0, 34.387, 10026);
INSERT INTO `warehouse` VALUES (9, 'crystal', 0, 0, 49.093, 0);
INSERT INTO `warehouse` VALUES (6, 'meteor', 0, 0, 368.446, 0);
INSERT INTO `warehouse` VALUES (6, 'crystal', 0, 0, 53.233, 0);
INSERT INTO `warehouse` VALUES (6, 'pine', 0, 0, 24578.000, 0);
INSERT INTO `warehouse` VALUES (6, 'hazel', 0, 0, 24581.000, 0);
INSERT INTO `warehouse` VALUES (6, 'yew', 0, 0, 24583.000, 0);
INSERT INTO `warehouse` VALUES (6, 'elm', 0, 0, 24581.000, 0);
INSERT INTO `warehouse` VALUES (6, 'mithril', 0, 0, 36.155, 10026);
INSERT INTO `warehouse` VALUES (6, 'illani', 0, 0, 199.112, 0);
INSERT INTO `warehouse` VALUES (6, 'illanias', 0, 0, 183.461, 0);
INSERT INTO `warehouse` VALUES (6, 'nutari', 0, 0, 201.959, 0);
INSERT INTO `warehouse` VALUES (6, 'dynallca_seeds', 0, 0, 4399198351304.400, 65320);
INSERT INTO `warehouse` VALUES (5, 'zincore', 0, 0, 28672.000, 0);
INSERT INTO `warehouse` VALUES (5, 'tinore', 0, 0, 28674.000, 0);
INSERT INTO `warehouse` VALUES (5, 'ironore', 0, 0, 28678.000, 0);
INSERT INTO `warehouse` VALUES (5, 'copper', 0, 0, 196.960, 0);
INSERT INTO `warehouse` VALUES (5, 'bronze', 0, 0, 28685.000, 0);
INSERT INTO `warehouse` VALUES (5, 'brass', 0, 0, 28689.000, 0);
INSERT INTO `warehouse` VALUES (5, 'iron', 0, 0, 28682.000, 0);
INSERT INTO `warehouse` VALUES (5, 'steel', 0, 0, 28717.000, 0);
INSERT INTO `warehouse` VALUES (5, 'coal', 0, 0, 127.738, 0);
INSERT INTO `warehouse` VALUES (5, 'adamantium', 0, 0, 41.632, 0);
INSERT INTO `warehouse` VALUES (5, 'meteor', 0, 0, 400.446, 0);
INSERT INTO `warehouse` VALUES (5, 'crystal', 0, 0, 54.233, 0);
INSERT INTO `warehouse` VALUES (5, 'pine', 0, 0, 28674.000, 0);
INSERT INTO `warehouse` VALUES (5, 'hazel', 0, 0, 28677.000, 0);
INSERT INTO `warehouse` VALUES (5, 'yew', 0, 0, 28679.000, 0);
INSERT INTO `warehouse` VALUES (5, 'elm', 0, 0, 28677.000, 0);
INSERT INTO `warehouse` VALUES (5, 'mithril', 0, 0, 36.081, 10026);
INSERT INTO `warehouse` VALUES (5, 'illani', 0, 0, 231.112, 0);
INSERT INTO `warehouse` VALUES (5, 'illanias', 0, 0, 215.461, 0);
INSERT INTO `warehouse` VALUES (5, 'nutari', 0, 0, 233.959, 0);
INSERT INTO `warehouse` VALUES (5, 'dynallca', 0, 0, 236.362, 0);
INSERT INTO `warehouse` VALUES (5, 'copperore', 0, 0, 257.291, 0);
INSERT INTO `warehouse` VALUES (4, 'copperore', 0, 0, 289.291, 0);
INSERT INTO `warehouse` VALUES (4, 'zincore', 0, 0, 32768.000, 0);
INSERT INTO `warehouse` VALUES (4, 'tinore', 0, 0, 32770.000, 0);
INSERT INTO `warehouse` VALUES (4, 'ironore', 0, 0, 32774.000, 0);
INSERT INTO `warehouse` VALUES (4, 'copper', 0, 0, 228.960, 0);
INSERT INTO `warehouse` VALUES (4, 'bronze', 0, 0, 32781.000, 0);
INSERT INTO `warehouse` VALUES (4, 'brass', 0, 0, 32785.000, 0);
INSERT INTO `warehouse` VALUES (4, 'iron', 0, 0, 32778.000, 0);
INSERT INTO `warehouse` VALUES (4, 'steel', 0, 0, 32813.000, 0);
INSERT INTO `warehouse` VALUES (4, 'coal', 0, 0, 143.738, 0);
INSERT INTO `warehouse` VALUES (4, 'adamantium', 0, 0, 42.632, 0);
INSERT INTO `warehouse` VALUES (4, 'meteor', 0, 0, 432.446, 0);
INSERT INTO `warehouse` VALUES (4, 'crystal', 0, 0, 55.233, 0);
INSERT INTO `warehouse` VALUES (4, 'pine', 0, 0, 32770.000, 0);
INSERT INTO `warehouse` VALUES (4, 'hazel', 0, 0, 32773.000, 0);
INSERT INTO `warehouse` VALUES (4, 'yew', 0, 0, 32775.000, 0);
INSERT INTO `warehouse` VALUES (4, 'elm', 0, 0, 32773.000, 0);
INSERT INTO `warehouse` VALUES (4, 'mithril', 0, 0, 35.998, 10026);
INSERT INTO `warehouse` VALUES (4, 'illani', 0, 0, 263.112, 0);
INSERT INTO `warehouse` VALUES (4, 'illanias', 0, 0, 247.461, 0);
INSERT INTO `warehouse` VALUES (4, 'nutari', 0, 0, 265.959, 0);
INSERT INTO `warehouse` VALUES (4, 'dynallca', 0, 0, 268.362, 0);
INSERT INTO `warehouse` VALUES (4, 'dynallca_seeds', 0, 0, 4398723893461.400, 65320);
INSERT INTO `warehouse` VALUES (3, 'zincore', 0, 0, 36864.000, 0);
INSERT INTO `warehouse` VALUES (3, 'tinore', 0, 0, 36866.000, 0);
INSERT INTO `warehouse` VALUES (3, 'ironore', 0, 0, 36870.000, 0);
INSERT INTO `warehouse` VALUES (3, 'copper', 0, 0, 260.960, 0);
INSERT INTO `warehouse` VALUES (3, 'bronze', 0, 0, 36877.000, 0);
INSERT INTO `warehouse` VALUES (3, 'brass', 0, 0, 36881.000, 0);
INSERT INTO `warehouse` VALUES (3, 'iron', 0, 0, 36874.000, 0);
INSERT INTO `warehouse` VALUES (3, 'steel', 0, 0, 36909.000, 0);
INSERT INTO `warehouse` VALUES (3, 'coal', 0, 0, 159.738, 0);
INSERT INTO `warehouse` VALUES (3, 'adamantium', 0, 0, 43.632, 0);
INSERT INTO `warehouse` VALUES (3, 'meteor', 0, 0, 464.446, 0);
INSERT INTO `warehouse` VALUES (3, 'crystal', 0, 0, 56.233, 0);
INSERT INTO `warehouse` VALUES (3, 'pine', 0, 0, 36866.000, 0);
INSERT INTO `warehouse` VALUES (3, 'hazel', 0, 0, 36869.000, 0);
INSERT INTO `warehouse` VALUES (3, 'yew', 0, 0, 36871.000, 0);
INSERT INTO `warehouse` VALUES (3, 'elm', 0, 0, 36869.000, 0);
INSERT INTO `warehouse` VALUES (3, 'mithril', 0, 0, 35.907, 10026);
INSERT INTO `warehouse` VALUES (3, 'illani', 0, 0, 295.112, 0);
INSERT INTO `warehouse` VALUES (3, 'illanias', 0, 0, 279.461, 0);
INSERT INTO `warehouse` VALUES (3, 'nutari', 0, 0, 297.959, 0);
INSERT INTO `warehouse` VALUES (3, 'dynallca', 0, 0, 300.362, 0);
INSERT INTO `warehouse` VALUES (3, 'copperore', 0, 0, 321.291, 0);
INSERT INTO `warehouse` VALUES (2, 'zincore', 0, 0, 40960.000, 0);
INSERT INTO `warehouse` VALUES (2, 'tinore', 0, 0, 40962.000, 0);
INSERT INTO `warehouse` VALUES (2, 'ironore', 0, 0, 40966.000, 0);
INSERT INTO `warehouse` VALUES (2, 'copper', 0, 0, 292.960, 0);
INSERT INTO `warehouse` VALUES (2, 'bronze', 0, 0, 40973.000, 0);
INSERT INTO `warehouse` VALUES (2, 'brass', 0, 0, 40977.000, 0);
INSERT INTO `warehouse` VALUES (2, 'iron', 0, 0, 40970.000, 0);
INSERT INTO `warehouse` VALUES (2, 'steel', 0, 0, 41005.000, 0);
INSERT INTO `warehouse` VALUES (2, 'coal', 0, 0, 191.738, 0);
INSERT INTO `warehouse` VALUES (2, 'adamantium', 0, 0, 44.632, 0);
INSERT INTO `warehouse` VALUES (2, 'meteor', 0, 0, 496.446, 0);
INSERT INTO `warehouse` VALUES (2, 'crystal', 0, 0, 57.233, 0);
INSERT INTO `warehouse` VALUES (2, 'pine', 0, 0, 40962.000, 0);
INSERT INTO `warehouse` VALUES (2, 'hazel', 0, 0, 40965.000, 0);
INSERT INTO `warehouse` VALUES (2, 'yew', 0, 0, 40967.000, 0);
INSERT INTO `warehouse` VALUES (2, 'elm', 0, 0, 40965.000, 0);
INSERT INTO `warehouse` VALUES (2, 'mithril', 0, 0, 35.867, 10026);
INSERT INTO `warehouse` VALUES (2, 'illani', 0, 0, 327.112, 0);
INSERT INTO `warehouse` VALUES (2, 'illanias', 0, 0, 311.461, 0);
INSERT INTO `warehouse` VALUES (2, 'nutari', 0, 0, 329.959, 0);
INSERT INTO `warehouse` VALUES (2, 'dynallca', 0, 0, 332.362, 0);
INSERT INTO `warehouse` VALUES (2, 'copperore', 0, 0, 353.291, 0);
INSERT INTO `warehouse` VALUES (7, 'copperore', 0, 0, 193.291, 0);
INSERT INTO `warehouse` VALUES (7, 'illanias_seeds', 0, 0, 22709200164.399, 10);
INSERT INTO `warehouse` VALUES (6, 'adamantium', 0, 0, 40.632, 0);
INSERT INTO `warehouse` VALUES (9, 'copperore', 0, 0, 161.291, 0);
INSERT INTO `warehouse` VALUES (7, 'dynallca', 0, 0, 188.362, 0);
INSERT INTO `warehouse` VALUES (7, 'illani_seeds', 0, 0, 6105096191585.500, 125);
INSERT INTO `warehouse` VALUES (7, 'nutari_seeds', 0, 0, 2584820799731.700, 80167);
INSERT INTO `warehouse` VALUES (7, 'coal', 0, 0, 95.738, 0);
INSERT INTO `warehouse` VALUES (6, 'illani_seeds', 0, 0, 5872959857850.900, 125);
INSERT INTO `warehouse` VALUES (6, 'illanias_seeds', 0, 0, 22711023390.047, 10);
INSERT INTO `warehouse` VALUES (6, 'nutari_seeds', 0, 0, 2577895135098.400, 80167);
INSERT INTO `warehouse` VALUES (6, 'dynallca', 0, 0, 204.362, 0);
INSERT INTO `warehouse` VALUES (6, 'coal', 0, 0, 111.738, 0);
INSERT INTO `warehouse` VALUES (5, 'illani_seeds', 1, 0, 5809356141548.400, 126);
INSERT INTO `warehouse` VALUES (5, 'illanias_seeds', 0, 0, 22709684125.824, 10);
INSERT INTO `warehouse` VALUES (5, 'nutari_seeds', 0, 0, 2572055521673.300, 80167);
INSERT INTO `warehouse` VALUES (5, 'dynallca_seeds', 0, 0, 4398946243002.100, 65320);
INSERT INTO `warehouse` VALUES (2, 'illani_seeds', 0, 0, 5474334432823.600, 126);
INSERT INTO `warehouse` VALUES (4, 'illani_seeds', 0, 0, 5613823187230.200, 126);
INSERT INTO `warehouse` VALUES (4, 'illanias_seeds', 0, 0, 22708507296.799, 10);
INSERT INTO `warehouse` VALUES (4, 'nutari_seeds', 0, 0, 2570352147394.900, 80167);
INSERT INTO `warehouse` VALUES (3, 'illani_seeds', 0, 0, 5540431563391.700, 126);
INSERT INTO `warehouse` VALUES (3, 'illanias_seeds', 0, 0, 22707680077.035, 10);
INSERT INTO `warehouse` VALUES (3, 'nutari_seeds', 0, 0, 2570189815441.400, 80167);
INSERT INTO `warehouse` VALUES (3, 'dynallca_seeds', 0, 0, 4398567347145.800, 65320);
INSERT INTO `warehouse` VALUES (2, 'illanias_seeds', 0, 0, 22707284159.221, 10);
INSERT INTO `warehouse` VALUES (2, 'nutari_seeds', 0, 0, 2571923814751.500, 80167);
INSERT INTO `warehouse` VALUES (2, 'dynallca_seeds', 0, 0, 4398492178502.700, 65320);
INSERT INTO `warehouse` VALUES (1, 'illani_seeds', 0, 0, 5264779971073.500, 126);
INSERT INTO `warehouse` VALUES (10, 'illanias_seeds', 0, 0, 22702187116.507, 10);
INSERT INTO `warehouse` VALUES (1, 'dynallca_seeds', 0, 0, 4398493827867.000, 65320);
INSERT INTO `warehouse` VALUES (8, 'dynallca_seeds', 0, 0, 4398255411366.300, 65320);
