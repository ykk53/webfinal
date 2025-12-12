# 快速安装指南

## 第一步：导入数据库

1. 启动WAMP服务器（确保Apache和MySQL都在运行）
2. 打开浏览器访问：`http://localhost/phpmyadmin`
3. 点击"新建"创建数据库，或直接执行以下步骤：
4. 点击"导入"标签
5. 选择文件：`database/schema.sql`
6. 点击"执行"按钮

**或者使用SQL命令行：**
```bash
mysql -u root -p < database/schema.sql
```

## 第二步：配置数据库连接

打开文件：`config/database.php`

确认配置正确（默认配置如下）：
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // 如果你的MySQL有密码，请填写
define('DB_NAME', 'ancient_architecture');
```

## 第三步：访问网站

1. 确保WAMP服务器正在运行
2. 打开浏览器访问：`http://localhost/webnewdemo/`

## 测试账号

### 管理员账号
- 用户名：`admin`
- 密码：`admin123`
- 登录时选择"管理员"类型

### 创建普通用户
访问注册页面自行注册新用户

## 功能测试清单

- [ ] 用户注册功能
- [ ] 用户登录功能（普通用户）
- [ ] 管理员登录功能
- [ ] 首页建筑展示
- [ ] 建筑列表页面
- [ ] 建筑详情页面
- [ ] 知识问答功能
- [ ] 个人中心页面
- [ ] 答题统计功能

## 常见问题

### 1. 数据库连接失败
- 检查MySQL服务是否启动
- 检查数据库配置是否正确
- 确认数据库已创建

### 2. 页面显示空白
- 检查PHP错误日志
- 确认所有文件路径正确
- 检查浏览器控制台是否有JavaScript错误

### 3. 登录后跳转失败
- 检查session配置
- 确认PHP session功能正常
- 清除浏览器缓存和Cookie

### 4. 图片不显示
- 确认images目录存在
- 添加实际的建筑图片到images目录
- 或使用在线图片URL

## 下一步

1. 添加实际的建筑图片到`images/`目录
2. 通过后台管理添加更多建筑数据（待实现）
3. 通过后台管理添加更多问答题目（待实现）
4. 根据需要调整样式和布局

## 项目目录说明

```
webnewdemo/
├── api/              # 后端API接口
├── config/           # 配置文件
├── css/              # 样式文件
├── database/         # 数据库文件
├── images/           # 图片资源
├── js/               # JavaScript文件
├── *.html            # 前端页面
└── README.md         # 项目说明
```

## 技术支持

如遇到问题，请检查：
1. WAMP服务器状态（绿色图标）
2. PHP版本（建议7.0+）
3. MySQL版本（建议5.7+）
4. 浏览器控制台错误信息
