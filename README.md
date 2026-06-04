# 郑州鑫聚康电子科技有限公司 - 官网系统

## 环境要求
- PHP 5.6+
- MySQL 5.6+ / MariaDB 10+
- Nginx / Apache
- ThinkPHP 5.1

## 安装步骤

### 1. 导入数据库
```bash
mysql -uroot -p < xjk.sql
```

### 2. 配置数据库连接
编辑 `config/database.php`，修改以下配置：
```php
'hostname' => '127.0.0.1',
'database' => 'xjk',
'username' => 'root',
'password' => '你的密码',
'hostport' => '3306',
'prefix'   => 'xjk_',
```

### 3. 配置Web服务器

#### Nginx配置
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/xjk/public;
    index index.php index.html;

    location / {
        if (!-e $request_filename) {
            rewrite ^(.*)$ /index.php?s=$1 last;
        }
    }

    location ~ \.php(.*)$ {
        fastcgi_pass unix:/run/php/php5.6-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf|ico|js|css)$ {
        expires 30d;
    }
}
```

#### Apache配置
项目已内置 `.htaccess`，直接配置虚拟主机指向 `public` 目录即可。

### 4. 设置目录权限
```bash
chmod -R 755 runtime/
chmod -R 755 public/uploads/
```

### 5. 访问系统
- 前台首页：http://your-domain.com/
- 后台管理：http://your-domain.com/admin

## 后台账号
- 用户名：admin
- 密码：admin123

## 目录结构
```
xjk/
├── application/           # 应用目录
│   ├── admin/            # 后台模块
│   │   ├── controller/   # 后台控制器
│   │   └── view/         # 后台视图
│   ├── index/            # 前台模块
│   │   ├── controller/   # 前台控制器
│   │   └── view/         # 前台视图
│   └── common/           # 公共模块
│       └── model/        # 数据模型
├── config/               # 配置文件
├── public/               # 网站根目录
│   ├── static/           # 静态资源
│   │   └── css/          # 样式文件
│   └── uploads/          # 上传文件
├── route/                # 路由配置
├── thinkphp/             # 框架核心
├── vendor/               # Composer依赖
└── xjk.sql              # 数据库文件
```

## 功能说明

### 前台功能
| 页面 | 路由 | 说明 |
|------|------|------|
| 首页 | / | 轮播图、推荐产品、新闻动态 |
| 关于我们 | /about | 公司简介、企业文化 |
| 产品服务 | /product | 产品列表（支持分类筛选） |
| 产品详情 | /product/{id} | 产品详细信息 |
| 新闻资讯 | /news | 新闻列表（支持分类筛选） |
| 新闻详情 | /news/{id} | 新闻详细内容 |
| 联系我们 | /contact | 联系方式、在线留言 |

### 后台功能
| 功能 | 路由 | 说明 |
|------|------|------|
| 登录 | /admin | 后台登录 |
| 控制台 | /admin/index | 数据概览 |
| 产品分类 | /admin/product_category | 增删改查 |
| 产品管理 | /admin/product | 增删改查、图片上传 |
| 新闻分类 | /admin/news_category | 增删改查 |
| 新闻管理 | /admin/news | 增删改查、封面图上传 |
| 轮播图管理 | /admin/banner | 增删改查、图片上传 |
| 公司信息 | /admin/company | 公司信息编辑 |
