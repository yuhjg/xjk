-- ============================================
-- 郑州鑫聚康电子科技有限公司 官网数据库
-- ============================================

CREATE DATABASE IF NOT EXISTS `xjk` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `xjk`;

-- -------------------------------------------
-- 管理员表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_admin`;
CREATE TABLE `xjk_admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码(md5)',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

-- 默认管理员 admin / admin123
INSERT INTO `xjk_admin` VALUES (1, 'admin', '0192023a7bbd7b501b7c5f3c6e4e6e6e', 0, '', 0, 0);

-- -------------------------------------------
-- 产品分类表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_product_category`;
CREATE TABLE `xjk_product_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序(越小越靠前)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1启用,0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品分类表';

-- -------------------------------------------
-- 产品表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_product`;
CREATE TABLE `xjk_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '产品名称',
  `subtitle` varchar(200) NOT NULL DEFAULT '' COMMENT '产品副标题',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '产品图片',
  `description` text COMMENT '产品描述',
  `content` text COMMENT '产品详情(富文本)',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1显示,0隐藏',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '推荐:1是,0否',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品表';

-- -------------------------------------------
-- 新闻分类表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_news_category`;
CREATE TABLE `xjk_news_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1启用,0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新闻分类表';

-- -------------------------------------------
-- 新闻表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_news`;
CREATE TABLE `xjk_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '新闻标题',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '作者',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '摘要',
  `content` text COMMENT '新闻内容(富文本)',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1显示,0隐藏',
  `is_top` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '置顶:1是,0否',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新闻表';

-- -------------------------------------------
-- 公司信息表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_company`;
CREATE TABLE `xjk_company` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '公司名称',
  `short_name` varchar(30) NOT NULL DEFAULT '' COMMENT '简称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '公司Logo',
  `phone` varchar(30) NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '公司地址',
  `fax` varchar(30) NOT NULL DEFAULT '' COMMENT '传真',
  `qq` varchar(30) NOT NULL DEFAULT '' COMMENT 'QQ',
  `wechat` varchar(50) NOT NULL DEFAULT '' COMMENT '微信号',
  `wechat_qrcode` varchar(255) NOT NULL DEFAULT '' COMMENT '微信二维码',
  `icp` varchar(50) NOT NULL DEFAULT '' COMMENT '备案号',
  `copyright` varchar(100) NOT NULL DEFAULT '' COMMENT '版权信息',
  `intro` text COMMENT '公司简介',
  `culture` text COMMENT '企业文化',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='公司信息表';

-- -------------------------------------------
-- 留言表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_message`;
CREATE TABLE `xjk_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` varchar(30) NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `content` text COMMENT '留言内容',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读:1是,0否',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='留言表';

-- -------------------------------------------
-- 轮播图表
-- -------------------------------------------
DROP TABLE IF EXISTS `xjk_banner`;
CREATE TABLE `xjk_banner` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` varchar(200) NOT NULL DEFAULT '' COMMENT '副标题',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1显示,0隐藏',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='轮播图表';

-- ============================================
-- 初始数据
-- ============================================

-- 产品分类
INSERT INTO `xjk_product_category` (`id`, `name`, `sort`, `status`, `create_time`) VALUES
(1, '开关电源适配器', 1, 1, UNIX_TIMESTAMP()),
(2, '线性电源适配器', 2, 1, UNIX_TIMESTAMP()),
(3, 'USB电源适配器', 3, 1, UNIX_TIMESTAMP()),
(4, '车载电源适配器', 4, 1, UNIX_TIMESTAMP());

-- 示例产品
INSERT INTO `xjk_product` (`id`, `category_id`, `title`, `subtitle`, `image`, `description`, `sort`, `status`, `is_recommend`, `create_time`) VALUES
(1, 1, '12V 2A开关电源适配器', '高效节能 稳定可靠', '', '采用高性能开关电源方案，转换效率高达85%以上，具有过压、过流、短路等多重保护功能。', 1, 1, 1, UNIX_TIMESTAMP()),
(2, 1, '24V 3A开关电源适配器', '工业级品质', '', '工业级设计，宽电压输入范围，适用于工控设备、通信设备等领域。', 2, 1, 1, UNIX_TIMESTAMP()),
(3, 1, '5V 4A开关电源适配器', '多协议快充', '', '支持多种快充协议，适用于智能设备、路由器等电子产品。', 3, 1, 1, UNIX_TIMESTAMP()),
(4, 2, '9V 1A线性电源适配器', '低纹波低噪声', '', '低纹波低噪声设计，适用于对电源品质要求较高的音频设备、精密仪器等。', 4, 1, 0, UNIX_TIMESTAMP()),
(5, 3, '5V 2A USB电源适配器', '小巧便携', '', '超小型化设计，兼容各类USB设备，携带方便。', 5, 1, 1, UNIX_TIMESTAMP()),
(6, 4, '12V 5A车载电源适配器', '车载专用', '', '专为车载环境设计，耐高温、抗震动，适用于车载电子设备供电。', 6, 1, 0, UNIX_TIMESTAMP());

-- 新闻分类
INSERT INTO `xjk_news_category` (`id`, `name`, `sort`, `status`, `create_time`) VALUES
(1, '公司新闻', 1, 1, UNIX_TIMESTAMP()),
(2, '行业动态', 2, 1, UNIX_TIMESTAMP());

-- 示例新闻
INSERT INTO `xjk_news` (`id`, `category_id`, `title`, `author`, `description`, `content`, `views`, `sort`, `status`, `is_top`, `create_time`) VALUES
(1, 1, '郑州鑫聚康电子科技荣获行业优秀企业称号', 'admin', '近日，我司在电源适配器行业评选中荣获优秀企业称号。', '<p>近日，在由中国电子商会主办的电源适配器行业评选中，郑州鑫聚康电子科技有限公司凭借卓越的产品品质和优质的售后服务，荣获"行业优秀企业"称号。</p><p>这一荣誉是对我司多年来坚持品质第一、客户至上经营理念的充分肯定，也是对全体员工辛勤付出的最好回报。</p>', 128, 1, 1, 1, UNIX_TIMESTAMP()-86400*5),
(2, 2, '2024年电源适配器行业发展趋势分析', 'admin', '随着5G、物联网等新兴技术的发展，电源适配器行业迎来新的发展机遇。', '<p>随着5G、物联网、人工智能等新兴技术的快速发展，电源适配器行业正迎来新的发展机遇。高功率密度、高效率、小型化、智能化成为行业发展的主要方向。</p><p>GaN（氮化镓）技术的成熟应用，使得电源适配器在保持高功率输出的同时，体积大幅缩小，为便携式电子设备的发展提供了强有力的支撑。</p>', 96, 2, 1, 0, UNIX_TIMESTAMP()-86400*3),
(3, 1, '我司新研发中心正式投入使用', 'admin', '郑州鑫聚康电子科技新研发中心正式投入使用，将进一步提升产品研发能力。', '<p>经过半年的精心筹备和建设，郑州鑫聚康电子科技有限公司新研发中心于近日正式投入使用。</p><p>新研发中心配备了先进的测试设备和研发工具，将进一步提升我司的产品研发能力和技术创新水平，为客户提供更加优质的电源适配器产品和服务。</p>', 75, 3, 1, 0, UNIX_TIMESTAMP()-86400*1);

-- 公司信息
INSERT INTO `xjk_company` (`id`, `name`, `short_name`, `phone`, `email`, `address`, `qq`, `wechat`, `icp`, `copyright`, `intro`, `culture`, `create_time`) VALUES
(1, '郑州鑫聚康电子科技有限公司', '鑫聚康', '0371-88888888', 'info@xjkpower.com', '河南省郑州市高新区科学大道100号', '4008888888', 'xjkpower', '', '© 2024 郑州鑫聚康电子科技有限公司 版权所有', '<p>郑州鑫聚康电子科技有限公司是一家专业从事电源适配器研发、生产和销售的高新技术企业。公司成立于2010年，坐落于河南省郑州市高新区，拥有现代化的生产车间和先进的检测设备。</p><p>公司主要产品包括开关电源适配器、线性电源适配器、USB电源适配器、车载电源适配器等系列，广泛应用于安防监控、通信设备、工控仪器、消费电子等领域。产品通过CCC、CE、FCC、UL等多项国际认证，远销国内外市场。</p>', '<p><strong>企业使命：</strong>为全球客户提供安全可靠的电源解决方案</p><p><strong>企业愿景：</strong>成为中国电源适配器行业的领军企业</p><p><strong>核心价值观：</strong>品质第一、客户至上、持续创新、合作共赢</p>', UNIX_TIMESTAMP());

-- 轮播图
INSERT INTO `xjk_banner` (`id`, `title`, `subtitle`, `image`, `link`, `sort`, `status`, `create_time`) VALUES
(1, '专业电源适配器制造商', '十余年行业经验 · 品质值得信赖', '', '', 1, 1, UNIX_TIMESTAMP()),
(2, '品质铸就品牌', '通过CCC/CE/FCC/UL等多项国际认证', '', '', 2, 1, UNIX_TIMESTAMP()),
(3, '创新驱动发展', '持续研发投入 · 技术引领行业', '', '', 3, 1, UNIX_TIMESTAMP());
