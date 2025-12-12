# 中国古代建筑成就网站

## 项目简介
这是一个展示中国古代建筑的简单网站，包含登录注册、建筑展示、知识问答等功能。

## 技术栈
- 前端：HTML + CSS + JavaScript + jQuery
- 后端：PHP
- 数据库：MySQL

## 安装步骤

### 1. 环境要求
- PHP 7.0+
- MySQL 5.7+
- Apache/Nginx Web服务器
- 推荐使用WAMP/XAMPP等集成环境

### 2. 数据库配置
1. 打开phpMyAdmin或MySQL命令行
2. 导入数据库文件：`database/schema.sql`
3. 修改数据库配置文件：`config/database.php`（如需要）

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ancient_architecture');
```

### 3. 启动项目
1. 将项目文件放置在Web服务器目录下（如：`e:\wamp\www\webnewdemo`）
2. 启动Apache和MySQL服务
3. 访问：`http://localhost/webnewdemo/`

## 默认账号

### 管理员账号
- 用户名：`admin`
- 密码：`admin123`
- 登录时选择"管理员"类型

## 项目结构

```
webnewdemo/
├── api/                          # API接口目录
│   ├── register.php             # 用户注册
│   ├── login.php                # 用户登录
│   ├── logout.php               # 退出登录
│   ├── check-login.php          # 检查登录状态
│   ├── get-architectures.php    # 获取建筑列表
│   ├── get-architecture-detail.php  # 获取建筑详情
│   ├── get-quiz.php             # 获取问答题目
│   ├── get-user-info.php        # 获取用户信息
│   └── get-quiz-stats.php       # 获取答题统计
├── config/                       # 配置文件目录
│   ├── database.php             # 数据库配置
│   └── session.php              # 会话管理
├── css/                          # 样式文件目录
│   └── style.css                # 主样式文件
├── database/                     # 数据库文件目录
│   └── schema.sql               # 数据库结构和示例数据
├── images/                       # 图片目录
│   └── placeholder.jpg          # 占位图片
├── js/                           # JavaScript文件目录
│   └── main.js                  # 主JS文件
├── index.html                    # 首页
├── login.html                    # 登录页
├── register.html                 # 注册页
├── architecture-list.html        # 建筑列表页
├── architecture-detail.html      # 建筑详情页
├── quiz.html                     # 知识问答页
├── user-center.html              # 个人中心页
└── README.md                     # 项目说明文档
```

## 功能说明

### 1. 用户模块
- 用户注册：填写用户名、邮箱、密码
- 用户登录：支持普通用户和管理员登录
- 个人中心：查看个人信息和答题统计

### 2. 建筑展示模块
- 首页展示：展示特色建筑
- 分类浏览：按民宿、官府、皇宫、桥梁分类
- 建筑详情：查看建筑的详细信息

### 3. 知识问答模块
- 随机题目：每次随机显示一道题目
- 即时判断：选择答案后立即显示对错
- 答题统计：记录答对/答错数量

### 4. 后台管理模块（待实现）
- 建筑管理：增删改查建筑信息
- 题目管理：增删改查问答题目

## 注意事项

1. 本项目为简化版本，未实现复杂的安全验证
2. 密码使用PHP的`password_hash()`函数加密
3. 图片路径需要根据实际情况调整
4. 建议在本地环境测试使用

## 后续开发计划

- [ ] 完善后台管理功能
- [ ] 添加图片上传功能
- [ ] 优化页面样式
- [ ] 添加更多建筑数据
- [ ] 添加更多问答题目

## 联系方式

如有问题，请联系开发者。
