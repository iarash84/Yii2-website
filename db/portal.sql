/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : portal

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-05-31 14:43:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('superAdmin', '1', null);

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_bin,
  `rule_name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `data` text COLLATE utf8_bin,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('create.user', '1', 0x416C6C6F77204372656174652055736572, null, null, null, null);
INSERT INTO `auth_item` VALUES ('delete.user', '1', 0x416C6C6F772044656C6574652055736572, null, null, null, null);
INSERT INTO `auth_item` VALUES ('superAdmin', '3', 0x416C6C6F7720666F7220616C6C204D616E61676D656E74, null, null, null, null);
INSERT INTO `auth_item` VALUES ('update.user', '1', 0x416C6C6F77205570646174652055736572, null, null, null, null);
INSERT INTO `auth_item` VALUES ('usersManagement', '2', 0x416C6C6F77206D616E616765207573657273, null, null, null, null);

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_bin NOT NULL,
  `child` varchar(64) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('superAdmin', 'usersManagement');
INSERT INTO `auth_item_child` VALUES ('usersManagement', 'create.user');
INSERT INTO `auth_item_child` VALUES ('usersManagement', 'delete.user');
INSERT INTO `auth_item_child` VALUES ('usersManagement', 'update.user');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `data` text COLLATE utf8_bin,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_bin NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', '1456041626');
INSERT INTO `migration` VALUES ('m130524_201442_init', '1456041630');

-- ----------------------------
-- Table structure for tbl_blog_category
-- ----------------------------
DROP TABLE IF EXISTS `tbl_blog_category`;
CREATE TABLE `tbl_blog_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `createDatetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tbl_blog_category_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_blog_category
-- ----------------------------
INSERT INTO `tbl_blog_category` VALUES ('1', '1', 'learn Yii2', '2017-05-30 12:06:36');
INSERT INTO `tbl_blog_category` VALUES ('4', '1', 'learn bootstrap', '2017-05-31 14:20:03');

-- ----------------------------
-- Table structure for tbl_blog_post
-- ----------------------------
DROP TABLE IF EXISTS `tbl_blog_post`;
CREATE TABLE `tbl_blog_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin COMMENT 'خلاصه',
  `content` text COLLATE utf8_bin COMMENT 'متن کامل',
  `keyWord` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'کلمات کلیدی',
  `createDatetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `tbl_blog_post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `tbl_blog_post_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_blog_category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_blog_post
-- ----------------------------
INSERT INTO `tbl_blog_post` VALUES ('4', '1', '4', 'My Company', 0x3C70207374796C653D22746578742D616C69676E3A6A757374696679223E4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E204163637573616D7573206163637573616E7469756D20616C6961732C206170657269616D206172636869746563746F20617373756D656E64612061747175652063757069646974617465206572726F72206578706C696361626F2066616365726520667567697420696C6C6F20696E636964756E742C20697073612C206D6178696D65206E65717565206E65736369756E74206E6F737472756D206F64697420706572737069636961746973207175617320717569612072656D20726570726568656E64657269742073657175692073696E742073756E742076657269746174697320766F6C7570746174657321204163637573616E7469756D20616C697175696420636F6D6D6F646920646562697469732064696374612064697374696E6374696F20656E696D2065756D20686172756D2C206C617564616E7469756D2C206D6F6469206E65636573736974617469627573206E65736369756E74206E6F626973206E6F6E206F64696F20706572666572656E64697320706F72726F20706F7373696D7573207072616573656E7469756D2073656420736571756920746F74616D207665726F212049746171756520726570726568656E64657269742073616570652076657269746174697320766F6C757074617320766F6C7570746174652E204163637573616D757320646F6C6F72656D71756520656120657420657865726369746174696F6E656D20696E636964756E7420697073616D206E617475732C2070726F766964656E742071756173692071756F2073617069656E74652073756E7420766F6C7570746174652E204163637573616E7469756D20617373756D656E646120636F6E73657175756E7475722064656C656374757320696E636964756E7420697073756D206F6D6E69732C207061726961747572207175616520717569732071756F732C207265637573616E6461652073657175692073756E74207375736369706974207665726974617469732E3C2F703E0D0A, 0x3C70207374796C653D22746578742D616C69676E3A6A757374696679223E4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E204163637573616D7573206163637573616E7469756D20616C6961732C206170657269616D206172636869746563746F20617373756D656E64612061747175652063757069646974617465206572726F72206578706C696361626F2066616365726520667567697420696C6C6F20696E636964756E742C20697073612C206D6178696D65206E65717565206E65736369756E74206E6F737472756D206F64697420706572737069636961746973207175617320717569612072656D20726570726568656E64657269742073657175692073696E742073756E742076657269746174697320766F6C7570746174657321204163637573616E7469756D20616C697175696420636F6D6D6F646920646562697469732064696374612064697374696E6374696F20656E696D2065756D20686172756D2C206C617564616E7469756D2C206D6F6469206E65636573736974617469627573206E65736369756E74206E6F626973206E6F6E206F64696F20706572666572656E64697320706F72726F20706F7373696D7573207072616573656E7469756D2073656420736571756920746F74616D207665726F212049746171756520726570726568656E64657269742073616570652076657269746174697320766F6C757074617320766F6C7570746174652E204163637573616D757320646F6C6F72656D71756520656120657420657865726369746174696F6E656D20696E636964756E7420697073616D206E617475732C2070726F766964656E742071756173692071756F2073617069656E74652073756E7420766F6C7570746174652E204163637573616E7469756D20617373756D656E646120636F6E73657175756E7475722064656C656374757320696E636964756E7420697073756D206F6D6E69732C207061726961747572207175616520717569732071756F732C207265637573616E6461652073657175692073756E74207375736369706974207665726974617469732E4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E204163637573616D7573206163637573616E7469756D20616C6961732C206170657269616D206172636869746563746F20617373756D656E64612061747175652063757069646974617465206572726F72206578706C696361626F2066616365726520667567697420696C6C6F20696E636964756E742C20697073612C206D6178696D65206E65717565206E65736369756E74206E6F737472756D206F64697420706572737069636961746973207175617320717569612072656D20726570726568656E64657269742073657175692073696E742073756E742076657269746174697320766F6C7570746174657321204163637573616E7469756D20616C697175696420636F6D6D6F646920646562697469732064696374612064697374696E6374696F20656E696D2065756D20686172756D2C206C617564616E7469756D2C206D6F6469206E65636573736974617469627573206E65736369756E74206E6F626973206E6F6E206F64696F20706572666572656E64697320706F72726F20706F7373696D7573207072616573656E7469756D2073656420736571756920746F74616D207665726F212049746171756520726570726568656E64657269742073616570652076657269746174697320766F6C757074617320766F6C7570746174652E204163637573616D757320646F6C6F72656D71756520656120657420657865726369746174696F6E656D20696E636964756E7420697073616D206E617475732C2070726F766964656E742071756173692071756F2073617069656E74652073756E7420766F6C7570746174652E204163637573616E7469756D20617373756D656E646120636F6E73657175756E7475722064656C656374757320696E636964756E7420697073756D206F6D6E69732C207061726961747572207175616520717569732071756F732C207265637573616E6461652073657175692073756E74207375736369706974207665726974617469732E4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E204163637573616D7573206163637573616E7469756D20616C6961732C206170657269616D206172636869746563746F20617373756D656E64612061747175652063757069646974617465206572726F72206578706C696361626F2066616365726520667567697420696C6C6F20696E636964756E742C20697073612C206D6178696D65206E65717565206E65736369756E74206E6F737472756D206F64697420706572737069636961746973207175617320717569612072656D20726570726568656E64657269742073657175692073696E742073756E742076657269746174697320766F6C7570746174657321204163637573616E7469756D20616C697175696420636F6D6D6F646920646562697469732064696374612064697374696E6374696F20656E696D2065756D20686172756D2C206C617564616E7469756D2C206D6F6469206E65636573736974617469627573206E65736369756E74206E6F626973206E6F6E206F64696F20706572666572656E64697320706F72726F20706F7373696D7573207072616573656E7469756D2073656420736571756920746F74616D207665726F212049746171756520726570726568656E64657269742073616570652076657269746174697320766F6C757074617320766F6C7570746174652E204163637573616D757320646F6C6F72656D71756520656120657420657865726369746174696F6E656D20696E636964756E7420697073616D206E617475732C2070726F766964656E742071756173692071756F2073617069656E74652073756E7420766F6C7570746174652E204163637573616E7469756D20617373756D656E646120636F6E73657175756E7475722064656C656374757320696E636964756E7420697073756D206F6D6E69732C207061726961747572207175616520717569732071756F732C207265637573616E6461652073657175692073756E74207375736369706974207665726974617469732E3C2F703E0D0A, 'Lorem ipsum dolor sit amet', '2017-05-31 14:39:13');

-- ----------------------------
-- Table structure for tbl_carousel
-- ----------------------------
DROP TABLE IF EXISTS `tbl_carousel`;
CREATE TABLE `tbl_carousel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `image` varchar(128) COLLATE utf8_bin NOT NULL,
  `link` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `text` text COLLATE utf8_bin,
  `order_num` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_carousel
-- ----------------------------
INSERT INTO `tbl_carousel` VALUES ('3', '1', 'upload/image/wSeBQN1aBKQDzrEXHk-GEGz8Zn7i5fKT.svg', 'http://127.0.0.1/CertAdmin/backend/web/index.php/carousel/create', 'Lorem ipsum dolor sit amet, consectetur 1', 0x417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F20, '1', '1');
INSERT INTO `tbl_carousel` VALUES ('4', '1', 'upload/image/2SEY50-JMTVUo3SiLrSQzNaHUl6GJv8j.svg', 'http://127.0.0.1/CertAdmin/backend/web/index.php/carousel/create', 'Lorem ipsum dolor sit amet, consectetur 2', 0x417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F, '2', '1');
INSERT INTO `tbl_carousel` VALUES ('5', '1', 'upload/image/uTXLXrurdVrKb6zRh3El4yzQAC025BgJ.svg', '', 'Lorem ipsum dolor sit amet, consectetur 3', 0x417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F, '3', '1');

-- ----------------------------
-- Table structure for tbl_contact_us
-- ----------------------------
DROP TABLE IF EXISTS `tbl_contact_us`;
CREATE TABLE `tbl_contact_us` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phoneNumber` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'شماره تماس',
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `body` text COLLATE utf8_bin,
  `createDateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_contact_us
-- ----------------------------
INSERT INTO `tbl_contact_us` VALUES ('1', 'آرش', '09122335202', 'arash@yahoo.com', 'تست', 0xD985D8AAD986, '2016-02-24 14:21:37');
INSERT INTO `tbl_contact_us` VALUES ('2', 'آرش', '09122335202', 'arash@yahoo.com', 'تست', 0xD985D8AAD986, '2016-02-27 17:35:00');

-- ----------------------------
-- Table structure for tbl_faqs
-- ----------------------------
DROP TABLE IF EXISTS `tbl_faqs`;
CREATE TABLE `tbl_faqs` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(9) unsigned DEFAULT NULL,
  `question` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `respons` text CHARACTER SET utf8 COLLATE utf8_bin,
  `createDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_faqs
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_log
-- ----------------------------
DROP TABLE IF EXISTS `tbl_log`;
CREATE TABLE `tbl_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `success` int(1) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `userAgent` text,
  `createDateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_log
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_opportunity
-- ----------------------------
DROP TABLE IF EXISTS `tbl_opportunity`;
CREATE TABLE `tbl_opportunity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phoneNumber` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `resume` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `createDateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_opportunity
-- ----------------------------
INSERT INTO `tbl_opportunity` VALUES ('1', 'آرش', '09122335202', 'upload/resume/Bu-XIP3kO7ZpGH5Mc5iXZDSDxQSMY5jt.pdf', 'Arash@yahoo.com', '2016-02-24 14:14:49');

-- ----------------------------
-- Table structure for tbl_order
-- ----------------------------
DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE `tbl_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `company` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phoneNumber` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `createDateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_order
-- ----------------------------
INSERT INTO `tbl_order` VALUES ('1', '123', 'طراحان صنعت خاورمیانه', '123', '123', '123@123.123', 0xD8B4D8B1D8AD20D9BED8B1D988DA98D98720, '2016-02-24 13:52:47');

-- ----------------------------
-- Table structure for tbl_sample
-- ----------------------------
DROP TABLE IF EXISTS `tbl_sample`;
CREATE TABLE `tbl_sample` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `content` text COLLATE utf8_bin,
  `url_link` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `url_display_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `createDateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_sample
-- ----------------------------
INSERT INTO `tbl_sample` VALUES ('2', '1', 'Lorem ipsum dolor sit amet, consectetur', 0x4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E, 'http://www.google.com', 'Assumenda eligendi ', 'upload/image/eiRlsyTEV7nMUTo16xulxp0ayIZNAQ1P.png', '2017-05-30 14:24:22');
INSERT INTO `tbl_sample` VALUES ('4', '1', 'Lorem ipsum dolor sit amet, consectetur', 0x4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E, 'www.yahoo.com', 'Assumenda eligendi ', 'upload/image/4IvZE5HH2Lxa7O8E1miohyl70F4-EwKS.png', '2017-05-30 14:23:25');
INSERT INTO `tbl_sample` VALUES ('5', '1', 'Lorem ipsum dolor sit amet, consectetur', 0x4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E20416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E, 'www.msn.com', 'Assumenda eligendi ', 'upload/image/Yo7sj_1aAXufs7uikah1XX_gVfV2wXWP.png', '2017-05-30 14:23:58');

-- ----------------------------
-- Table structure for tbl_setting
-- ----------------------------
DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `type` enum('About','Opportunity','WorkingHours','PostalCode','PhoneNumber','Email','Address','FaxNumber','Facebook','Twitter','Aparat','Youtube','Linkedin','Telegram','Instagram','GooglePlus','CompanyName') COLLATE utf8_bin DEFAULT NULL,
  `content` text COLLATE utf8_bin,
  `updateDateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of tbl_setting
-- ----------------------------
INSERT INTO `tbl_setting` VALUES ('1', '1', 'About', 0x3C70207374796C653D22746578742D616C69676E3A6A757374696679223E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4163637573616D7573206163637573616E7469756D20616C6961732C206170657269616D206172636869746563746F20617373756D656E64612061747175652063757069646974617465206572726F72206578706C696361626F2066616365726520667567697420696C6C6F20696E636964756E742C20697073612C206D6178696D65206E65717565206E65736369756E74206E6F737472756D206F64697420706572737069636961746973207175617320717569612072656D20726570726568656E64657269742073657175692073696E742073756E742076657269746174697320766F6C75707461746573213C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4163637573616E7469756D20616C697175696420636F6D6D6F646920646562697469732064696374612064697374696E6374696F20656E696D2065756D20686172756D2C206C617564616E7469756D2C206D6F6469206E65636573736974617469627573206E65736369756E74206E6F626973206E6F6E206F64696F20706572666572656E64697320706F72726F20706F7373696D7573207072616573656E7469756D2073656420736571756920746F74616D207665726F212049746171756520726570726568656E64657269742073616570652076657269746174697320766F6C757074617320766F6C7570746174652E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4163637573616D757320646F6C6F72656D71756520656120657420657865726369746174696F6E656D20696E636964756E7420697073616D206E617475732C2070726F766964656E742071756173692071756F2073617069656E74652073756E7420766F6C7570746174652E204163637573616E7469756D20617373756D656E646120636F6E73657175756E7475722064656C656374757320696E636964756E7420697073756D206F6D6E69732C207061726961747572207175616520717569732071756F732C207265637573616E6461652073657175692073756E74207375736369706974207665726974617469732E3C2F7370616E3E3C2F703E0D0A0D0A3C703E266E6273703B3C2F703E0D0A0D0A3C703E266E6273703B3C2F703E0D0A, '2017-05-30 11:09:53');
INSERT INTO `tbl_setting` VALUES ('2', '1', 'Opportunity', 0x3C6469763E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4C6F72656D20697073756D20646F6C6F722073697420616D65742C20636F6E7365637465747572206164697069736963696E6720656C69742E20417373756D656E646120656C6967656E6469206D61676E6920706172696174757220706F7373696D75732074656D706F72613F204164697069736369206265617461652C20646F6C6F72657320656C6967656E646920657420657865726369746174696F6E656D20696420696D7065646974206E657175652C206E6F626973206E756D7175616D206F64696F20706F72726F20736F6C7574612073757363697069742075742E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E416420636F6E73657175756E7475722063756C706120646F6C6F726962757320647563696D757320667567697420696E636964756E7420697073616D20697073756D206C61626F72696F73616D206C696265726F2C206D6F6C657374696165206E6F6E206E6F737472756D20717569737175616D20726570656C6C656E6475732073616570652073617069656E746520737573636970697420756E64652076656C20766F6C7570746174656D20766F6C7570746174657320766F6C7570746174696275732E20416C697175616D20646F6C6F7220647563696D7573206C61626F72756D206E69736920717561657261742E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4163637573616D7573206163637573616E7469756D20616C6961732C206170657269616D206172636869746563746F20617373756D656E64612061747175652063757069646974617465206572726F72206578706C696361626F2066616365726520667567697420696C6C6F20696E636964756E742C20697073612C206D6178696D65206E65717565206E65736369756E74206E6F737472756D206F64697420706572737069636961746973207175617320717569612072656D20726570726568656E64657269742073657175692073696E742073756E742076657269746174697320766F6C75707461746573213C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4163637573616E7469756D20616C697175696420636F6D6D6F646920646562697469732064696374612064697374696E6374696F20656E696D2065756D20686172756D2C206C617564616E7469756D2C206D6F6469206E65636573736974617469627573206E65736369756E74206E6F626973206E6F6E206F64696F20706572666572656E64697320706F72726F20706F7373696D7573207072616573656E7469756D2073656420736571756920746F74616D207665726F212049746171756520726570726568656E64657269742073616570652076657269746174697320766F6C757074617320766F6C7570746174652E3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E266E6273703B3C2F7370616E3E3C7370616E207374796C653D22666F6E742D66616D696C793A6972616E73616E737A2C73657269663B20666F6E742D73697A653A31347078223E4163637573616D757320646F6C6F72656D71756520656120657420657865726369746174696F6E656D20696E636964756E7420697073616D206E617475732C2070726F766964656E742071756173692071756F2073617069656E74652073756E7420766F6C7570746174652E204163637573616E7469756D20617373756D656E646120636F6E73657175756E7475722064656C656374757320696E636964756E7420697073756D206F6D6E69732C207061726961747572207175616520717569732071756F732C207265637573616E6461652073657175692073756E74207375736369706974207665726974617469732E3C2F7370616E3E3C2F6469763E0D0A0D0A3C703E266E6273703B3C2F703E0D0A, '2017-05-30 11:09:38');
INSERT INTO `tbl_setting` VALUES ('3', '1', 'Facebook', 0x68747470733A2F2F7777772E66616365626F6F6B2E636F6D2F6B706F7274616C, '2017-05-29 12:49:00');
INSERT INTO `tbl_setting` VALUES ('4', '1', 'Twitter', 0x68747470733A2F2F747769747465722E636F6D2F6B706F7274616C, '2017-05-29 12:49:05');
INSERT INTO `tbl_setting` VALUES ('5', '1', 'Linkedin', 0x68747470733A2F2F7777772E6C696E6B6564696E2E636F6D2F636F6D70616E792F6B706F7274616C, '2017-05-29 12:49:09');
INSERT INTO `tbl_setting` VALUES ('6', '1', 'Aparat', 0x687474703A2F2F7777772E6170617261742E636F6D2F6B706F7274616C, '2017-05-29 12:49:13');
INSERT INTO `tbl_setting` VALUES ('7', '1', 'Telegram', 0x687474703A2F2F7777772E74656C656772616D2E6D652F6B706F7274616C, '2017-05-29 12:49:19');
INSERT INTO `tbl_setting` VALUES ('8', '1', 'GooglePlus', 0x68747470733A2F2F706C75732E676F6F676C652E636F6D2F636F6D6D756E69746965732F6B706F7274616C, '2017-05-29 12:49:26');
INSERT INTO `tbl_setting` VALUES ('9', '1', 'Youtube', null, '2016-10-30 12:25:34');
INSERT INTO `tbl_setting` VALUES ('10', '1', 'Instagram', null, '2016-10-30 12:25:34');
INSERT INTO `tbl_setting` VALUES ('11', '1', 'Address', 0x41646472657373, '2017-05-30 10:09:28');
INSERT INTO `tbl_setting` VALUES ('12', '1', 'Email', 0x696E666F406B506F7274616C2E6972, '2017-05-30 10:09:28');
INSERT INTO `tbl_setting` VALUES ('13', '1', 'PhoneNumber', 0x303931323030303030303020E28093203838313030303030, '2017-05-30 10:09:28');
INSERT INTO `tbl_setting` VALUES ('14', '1', 'FaxNumber', 0x3838303030303030, '2017-05-30 10:09:28');
INSERT INTO `tbl_setting` VALUES ('15', '1', 'PostalCode', 0x313538363030303030, '2017-05-30 10:09:29');
INSERT INTO `tbl_setting` VALUES ('16', '1', 'WorkingHours', 0x54756573646179202D2046726964617920393A3030414D202D20343A3435504D, '2017-05-30 10:09:29');
INSERT INTO `tbl_setting` VALUES ('17', '1', 'CompanyName', 0x436F6D70616E79204E616D65, '2017-05-30 10:09:29');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '3eNTGCsPlG8qtNjs1f6JJoIJNHB2ENbr', '$2y$13$hGF50LVblVTaLs.55TIvB.9DFJpeqnciQN0tKyz7sBV7OZliKZU2O', null, 'arash_pm84@yahoo.com', '10', '1456041729', '1496123006');