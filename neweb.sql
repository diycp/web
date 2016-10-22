/*
Navicat MySQL Data Transfer

Source Server         : 本机
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : neweb

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-10-22 14:59:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bs_menu
-- ----------------------------
DROP TABLE IF EXISTS `bs_menu`;
CREATE TABLE `bs_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(15) DEFAULT NULL COMMENT '菜单名称',
  `module` varchar(20) DEFAULT NULL COMMENT '对应模块',
  `controller` varchar(20) DEFAULT NULL COMMENT '对应控制器',
  `action` varchar(20) DEFAULT NULL COMMENT '对应操作',
  `params` varchar(128) DEFAULT NULL COMMENT '请求参数（?id=）',
  `pid` int(11) DEFAULT '0' COMMENT '上级id',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态（0禁用，1显示，2隐藏）',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序（倒序）',
  `icon` varchar(25) DEFAULT NULL COMMENT '图标',
  `target` varchar(15) DEFAULT NULL COMMENT '打开方式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of bs_menu
-- ----------------------------
INSERT INTO `bs_menu` VALUES ('1', '系统管理', null, null, null, null, '0', '1', '99', 'fa-tachometer', null);
INSERT INTO `bs_menu` VALUES ('2', '菜单管理', 'admin', 'menu', 'index', null, '1', '1', '30', null, null);
INSERT INTO `bs_menu` VALUES ('3', '账号管理', 'admin', 'user', 'index', null, '1', '1', '99', 'fa-user', null);
INSERT INTO `bs_menu` VALUES ('4', '角色管理', 'admin', 'role', 'index', null, '1', '1', '60', null, null);
INSERT INTO `bs_menu` VALUES ('5', '菜单按钮管理', 'admin', 'menu', 'toolbar', null, '1', '1', '99', null, null);
INSERT INTO `bs_menu` VALUES ('6', '用户管理', '', null, null, null, '0', '1', '99', 'fa-user-plus', null);
INSERT INTO `bs_menu` VALUES ('7', '二级菜单1', 'admin', 'test', 'index', null, '6', '1', '99', null, null);
INSERT INTO `bs_menu` VALUES ('8', '二级菜单2', 'admin', 'text', 'index', null, '6', '1', '99', null, null);
INSERT INTO `bs_menu` VALUES ('9', '二级菜单3', 'admin', 'txt', 'index', null, '6', '1', '99', null, null);

-- ----------------------------
-- Table structure for bs_node
-- ----------------------------
DROP TABLE IF EXISTS `bs_node`;
CREATE TABLE `bs_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned DEFAULT NULL COMMENT '菜单id',
  `title` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `name` varchar(20) DEFAULT NULL COMMENT '节点key（操作名称）',
  `icon` varchar(20) DEFAULT NULL COMMENT '节点图标',
  `group` tinyint(1) DEFAULT '1' COMMENT '分组',
  `visible` tinyint(1) DEFAULT '0' COMMENT '状态（1显示，2隐藏）',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `sort` smallint(6) unsigned DEFAULT '0' COMMENT '倒序排序',
  `event_type` enum('default','custom','view','script') DEFAULT NULL COMMENT '事件类型',
  `event_value` varchar(128) DEFAULT NULL COMMENT '动作地址',
  `target` varchar(15) DEFAULT NULL COMMENT '链接打开方式',
  `access` int(1) DEFAULT '0' COMMENT '-1禁止访问，0默认，1公共（无权限控制）',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`,`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='菜单权限节点表';

-- ----------------------------
-- Records of bs_node
-- ----------------------------
INSERT INTO `bs_node` VALUES ('1', '2', '查看', 'index', null, '1', '0', null, '0', 'view', null, null, '0');
INSERT INTO `bs_node` VALUES ('2', '2', '添加', 'add', null, '1', '0', null, '0', 'view', null, 'modal', '0');
INSERT INTO `bs_node` VALUES ('3', '2', '修改', 'edit', null, '1', '0', null, '0', 'view', null, 'modal', '0');
INSERT INTO `bs_node` VALUES ('4', '2', '删除', 'delete', null, '1', '0', null, '0', 'view', '', 'default', '0');
INSERT INTO `bs_node` VALUES ('5', '3', '查看', 'index', '', '1', '0', '', '0', 'view', '', '', '0');
INSERT INTO `bs_node` VALUES ('6', '3', '添加', 'add', '', '1', '0', '', '0', 'view', '', 'modal', '0');
INSERT INTO `bs_node` VALUES ('7', '3', '修改', 'edit', '', '1', '0', '', '0', 'view', '', 'modal', '0');
INSERT INTO `bs_node` VALUES ('8', '3', '删除', 'delete', '', '1', '0', '', '0', 'view', '', 'default', '0');
INSERT INTO `bs_node` VALUES ('9', '4', '查看', 'index', '', '1', '0', '', '0', 'view', '', '', '0');
INSERT INTO `bs_node` VALUES ('10', '4', '添加', 'add', '', '1', '0', '', '0', 'view', '', 'modal', '0');
INSERT INTO `bs_node` VALUES ('11', '4', '修改', 'edit', '', '1', '0', '', '0', 'view', '', 'modal', '0');
INSERT INTO `bs_node` VALUES ('12', '4', '删除', 'delete', '', '1', '0', '', '0', 'view', '', 'default', '0');
INSERT INTO `bs_node` VALUES ('13', '7', '查看', 'index', '', '1', '0', '', '0', 'view', '', '', '0');
INSERT INTO `bs_node` VALUES ('14', '7', '添加', 'add', '', '1', '0', '', '0', 'view', '', 'modal', '0');
INSERT INTO `bs_node` VALUES ('15', '8', '修改', 'edit', '', '1', '0', '', '0', 'view', '', 'modal', '0');
INSERT INTO `bs_node` VALUES ('16', '9', '删除', 'delete', '', '1', '0', '', '0', 'view', '', 'default', '0');

-- ----------------------------
-- Table structure for bs_role
-- ----------------------------
DROP TABLE IF EXISTS `bs_role`;
CREATE TABLE `bs_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(8) DEFAULT NULL COMMENT '角色名称',
  `node_id` varchar(500) DEFAULT NULL COMMENT '权限节点（node表id集合）',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态（0禁用，1启用）',
  `remark` varchar(50) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
-- Records of bs_role
-- ----------------------------
INSERT INTO `bs_role` VALUES ('1', '超级管理员', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16', '1', null);
INSERT INTO `bs_role` VALUES ('2', '运营员', '13,14,15,16', '1', null);

-- ----------------------------
-- Table structure for bs_role_user
-- ----------------------------
DROP TABLE IF EXISTS `bs_role_user`;
CREATE TABLE `bs_role_user` (
  `role_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='用户权限表';

-- ----------------------------
-- Records of bs_role_user
-- ----------------------------
INSERT INTO `bs_role_user` VALUES ('1', '1');
INSERT INTO `bs_role_user` VALUES ('2', '1');
INSERT INTO `bs_role_user` VALUES ('2', '2');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) DEFAULT NULL COMMENT '账号',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `status` int(11) DEFAULT '0' COMMENT '状态 （0禁止 1可用）',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '13330613012', 'e10adc3949ba59abbe56e057f20f883e', '1', '1474375341');
INSERT INTO `users` VALUES ('2', '13330613000', 'e10adc3949ba59abbe56e057f20f883e', '1', '1474375285');
INSERT INTO `users` VALUES ('3', 'admin', '1', '0', null);
