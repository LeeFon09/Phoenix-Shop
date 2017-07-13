# ------------------------------------
# 创建订单表tp_order表SQL语句
# ------------------------------------
DROP TABLE IF EXISTS `tp_order`;
CREATE TABLE `tp_order` (
    `order_id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单id',
    `order_sn` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '订单编号',
    `user_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
    `address_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收货地址id',
    `order_status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '订单状态(1.待付款 3.待发货 5.待收货 7.待评价 9.交易成功 11.删除订单)',
    `handle_status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '处理状态(0.未付款 2.已付款 4.已发货 6.已收货 8.已评价 10.交易完结)',
    `is_valid` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '订单有效(1.有效，0无效)',
    `shipping_code` VARCHAR(32) NULL DEFAULT '0' COMMENT '物流code',
    `shipping_name` VARCHAR(120) NULL DEFAULT NULL COMMENT '物流名称',
    `pay_code` VARCHAR(32) NULL DEFAULT NULL COMMENT '支付code',
    `pay_name` VARCHAR(120) NULL DEFAULT NULL COMMENT '支付方式名称',
    `invoice_title` VARCHAR(256) NULL DEFAULT NULL COMMENT '发票抬头',
    `goods_price` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '商品总价',
    `shipping_price` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00' COMMENT '邮费',
    `user_money` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00' COMMENT '使用余额',
    `coupon_price` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00' COMMENT '优惠券抵扣',
    `integral` INT(10) UNSIGNED NULL DEFAULT '0' COMMENT '使用积分',
    `integral_money` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00' COMMENT '使用积分抵多少钱',
    `order_amount` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00' COMMENT '应付款金额',
    `total_amount` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '订单总价',
    `commit_time` DATETIME NOT NULL COMMENT '下单时间',
    `valid_time` DATETIME NULL DEFAULT NULL COMMENT '订单有效时间',
    `pay_time` DATETIME NULL DEFAULT NULL COMMENT '付款时间',
    `shipping_time` DATETIME NULL DEFAULT NULL COMMENT '发货时间',
    `confirm_time` DATETIME NULL DEFAULT NULL COMMENT '收货确认时间',
    `comment_time` DATETIME NULL DEFAULT NULL COMMENT '订单评价时间',
    `user_note` VARCHAR(255) NULL DEFAULT '' COMMENT '用户备注',
    `admin_note` VARCHAR(255) NULL DEFAULT '' COMMENT '管理员备注',
    `deliver_rank` TINYINT(1) UNSIGNED NULL DEFAULT '5' COMMENT '物流评价等级',
    `goods_rank` TINYINT(1) UNSIGNED NULL DEFAULT '5' COMMENT '商品评价等级',
    `service_rank` TINYINT(1) UNSIGNED NULL DEFAULT '5' COMMENT '商家服务态度评价等级',
    PRIMARY KEY (`order_id`),
    UNIQUE INDEX `order_sn` (`order_sn`),
    INDEX `user_id` (`user_id`),
    INDEX `address_id` (`address_id`)
)
    COMMENT='购物订单表'
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB;
# ------------------------------------
# 创建订单明细表tp_order_detail表SQL语句
# ------------------------------------
DROP TABLE IF EXISTS `tp_order_detail`;
CREATE TABLE `tp_order_detail` (
    `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单详情表',
    `order_id` MEDIUMINT(8) UNSIGNED NOT NULL COMMENT '订单id',
    `user_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
    `goods_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品id',
    `goods_sn` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '商品货号',
    `goods_name` VARCHAR(120) NOT NULL DEFAULT '' COMMENT '商品名称',
    `goods_num` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '购买数量',
    `goods_price` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '本店价',
    `total_price` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '单规格数量总价',
    `benefit_price` DECIMAL(10,2) UNSIGNED NULL DEFAULT '0.00' COMMENT '优惠价',
    `original_img` VARCHAR(255) NULL DEFAULT NULL COMMENT '商品原始图片路径',
    `spec_key` VARCHAR(64) NULL DEFAULT '' COMMENT '商品规格key 对应tp_spec_goods_price 表',
    `spec_key_name` VARCHAR(64) NULL DEFAULT '' COMMENT '商品规格组合名称',
    `spec_image_id` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT '商品规格图片id',
    `spec_image_src` VARCHAR(255) NULL DEFAULT '' COMMENT '商品规格图片路径',
    `is_valid` TINYINT(1) NULL DEFAULT '1' COMMENT '订单有效(1.有效，0无效)',
    `add_time` DATETIME NULL DEFAULT NULL COMMENT '加入订单时间',
    PRIMARY KEY (`id`)
)
    COMMENT='订单详情明细表'
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB;
# ------------------------------------
# 创建后台菜单表tp_admin_menu表SQL语句
# ------------------------------------
CREATE TABLE `tp_admin_menu` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台菜单ID',
    `key_word` VARCHAR(255) NULL DEFAULT NULL COMMENT '菜单关键字',
    `name` VARCHAR(255) NOT NULL COMMENT '菜单名',
    `icon` VARCHAR(255) NULL DEFAULT NULL COMMENT '菜单图标',
    `pid` INT(11) NOT NULL COMMENT '父级ID',
    `level` INT(11) NOT NULL COMMENT '菜单等级',
    `new_sortpath` VARCHAR(255) NOT NULL COMMENT '菜单路径',
    `new_id` INT(11) NOT NULL,
    `new_pid` INT(11) NOT NULL,
    `act` VARCHAR(255) NULL DEFAULT NULL,
    `control` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of tp_admin_menu
-- ----------------------------
INSERT INTO `tp_admin_menu` VALUES ('1', 'system', '系统设置', 'fa-cog', '0', '1', '0000_0001', '1', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('2', null, '友情链接', null, '1', '2', '0000_0001_0002', '2', '1', 'linkList', 'Article');
INSERT INTO `tp_admin_menu` VALUES ('3', 'access', '权限管理', 'fa-gears', '0', '1', '0000_0003', '3', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('4', null, '管理员列表', null, '3', '2', '0000_0003_0004', '4', '3', 'index', 'Admin');
INSERT INTO `tp_admin_menu` VALUES ('5', null, '角色管理', null, '3', '2', '0000_0003_0005', '5', '3', 'role', 'Admin');
INSERT INTO `tp_admin_menu` VALUES ('6', null, '权限管理', null, '3', '2', '0000_0003_0006', '6', '3', 'access', 'Admin');
INSERT INTO `tp_admin_menu` VALUES ('7', 'member', '会员管理', 'fa-user', '0', '1', '0000_0007', '7', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('8', null, '会员列表', null, '7', '2', '0000_0007_0008', '8', '7', 'index', 'User');
INSERT INTO `tp_admin_menu` VALUES ('9', null, '会员等级', null, '7', '2', '0000_0007_0009', '9', '7', 'levelList', 'User');
INSERT INTO `tp_admin_menu` VALUES ('10', 'goods', '商品管理', 'fa-book', '0', '1', '', '10', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('11', null, '商品分类', null, '10', '2', '0000_0010_0011', '11', '10', 'categoryList', 'Goods');
INSERT INTO `tp_admin_menu` VALUES ('12', null, '商品列表', null, '10', '2', '0000_0010_0012', '12', '10', 'goodsList', 'Goods');
INSERT INTO `tp_admin_menu` VALUES ('13', null, '商品类型', null, '10', '2', '0000_0010_0013', '13', '10', 'goodsTypeList', 'Goods');
INSERT INTO `tp_admin_menu` VALUES ('14', null, '商品规格', null, '10', '2', '0000_0010_0014', '14', '10', 'specList', 'Goods');
INSERT INTO `tp_admin_menu` VALUES ('15', null, '商品属性', null, '10', '2', '0000_0010_0015', '15', '10', 'goodsAttributeList', 'Goods');
INSERT INTO `tp_admin_menu` VALUES ('16', null, '品牌列表', null, '10', '2', '0000_0010_0016', '16', '10', 'brandList', 'Goods');
INSERT INTO `tp_admin_menu` VALUES ('17', null, '商品评论', null, '10', '2', '0000_0010_0017', '17', '10', 'index', 'Comment');
INSERT INTO `tp_admin_menu` VALUES ('18', 'order', '订单管理', 'fa-money', '0', '1', '0000_0018', '18', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('19', null, '订单列表', null, '18', '2', '0000_0018_0019', '19', '18', 'index', 'Order');
INSERT INTO `tp_admin_menu` VALUES ('20', 'Ad', '广告管理', 'fa-flag', '0', '1', '0000_0020', '20', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('21', null, '广告列表', null, '20', '2', '0000_0020_0021', '21', '20', 'adList', 'Ad');
INSERT INTO `tp_admin_menu` VALUES ('22', null, '广告位置', null, '20', '2', '0000_0020_0022', '22', '20', 'positionList', 'Ad');
INSERT INTO `tp_admin_menu` VALUES ('23', 'content', '内容管理', 'fa-comments', '0', '1', '0000_0023', '23', '0', null, null);
INSERT INTO `tp_admin_menu` VALUES ('24', null, '文章列表', null, '23', '2', '0000_0023_0024', '24', '23', 'articleList', 'Article');
INSERT INTO `tp_admin_menu` VALUES ('25', null, '文章分类', null, '23', '2', '0000_0023_0025', '25', '23', 'categoryList', 'Article');

-- ----------------------------
-- Table structure for `tp_accesses`
-- ----------------------------
DROP TABLE IF EXISTS `tp_accesses`;
CREATE TABLE `tp_accesses` (
    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL,
    `desc` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_accesses
-- ----------------------------
INSERT INTO `tp_accesses` VALUES ('15', '权限管理', 'access', '给管理员分配权限');
INSERT INTO `tp_accesses` VALUES ('16', '角色管理', 'role', '给管理员分配角色');
INSERT INTO `tp_accesses` VALUES ('17', '会员管理', 'user', '管理网站的会员');
INSERT INTO `tp_accesses` VALUES ('18', '管理员管理', 'admin', '管理后台管理员');

-- ----------------------------
-- Table structure for `tp_role_access`
-- ----------------------------
DROP TABLE IF EXISTS `tp_role_access`;
CREATE TABLE `tp_role_access` (
    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `role_id` tinyint(4) NOT NULL,
    `access_id` tinyint(4) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_role_access
-- ----------------------------
INSERT INTO `tp_role_access` VALUES ('31', '26', '18');
INSERT INTO `tp_role_access` VALUES ('30', '26', '17');
INSERT INTO `tp_role_access` VALUES ('29', '26', '16');
INSERT INTO `tp_role_access` VALUES ('28', '26', '15');
INSERT INTO `tp_role_access` VALUES ('32', '27', '17');
INSERT INTO `tp_role_access` VALUES ('33', '27', '18');
INSERT INTO `tp_role_access` VALUES ('34', '28', '17');

-- ----------------------------
-- Table structure for `tp_roles`
-- ----------------------------
DROP TABLE IF EXISTS `tp_roles`;
CREATE TABLE `tp_roles` (
    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(24) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_roles
-- ----------------------------
INSERT INTO `tp_roles` VALUES ('26', 'CEO');
INSERT INTO `tp_roles` VALUES ('27', '经理');
INSERT INTO `tp_roles` VALUES ('28', '推广员');

-- ----------------------------
-- Table structure for `tp_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_role`;
CREATE TABLE `tp_user_role` (
    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `uid` tinyint(4) NOT NULL,
    `role_id` tinyint(4) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_role
-- ----------------------------
INSERT INTO `tp_user_role` VALUES ('12', '11', '26');
INSERT INTO `tp_user_role` VALUES ('13', '12', '27');
INSERT INTO `tp_user_role` VALUES ('14', '13', '28');