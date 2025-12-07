# 🎓 Photo Gallery - 部署信息文档

## 📋 项目信息

**项目名称**: Minimalist Photo Gallery  
**技术栈**: Laravel 11 + PHP 8.3 + MySQL + Apache  
**部署平台**: AWS EC2 (Ubuntu 22.04 LTS)  
**GitHub 仓库**: https://github.com/Ei-Ayw/photo-gallery-test

---

## 🌐 访问信息

### 应用程序访问

- **HTTP**: http://3.236.233.166
- **HTTPS**: https://3.236.233.166 ⭐ (推荐)

> ⚠️ **注意**: 使用自签名 SSL 证书，浏览器会显示安全警告。
> 点击"高级"→"继续访问"即可。

### phpMyAdmin 数据库管理

- **HTTP**: http://3.236.233.166/phpmyadmin
- **HTTPS**: https://3.236.233.166/phpmyadmin ⭐

---

## 🔑 登录凭据

### Super Admin（管理员）账户

```
邮箱：admin@photogallery.com
密码：admin123
角色：系统管理员
```

**权限**:
- ✅ 查看所有照片
- ✅ 管理所有用户（创建、编辑、删除）
- ✅ 管理所有照片（编辑、删除）
- ✅ 访问管理员面板
- ✅ 查看系统统计信息

**管理面板访问**: https://3.236.233.166/admin

---

### Regular User（普通用户）账户

```
邮箱：user@photogallery.com
密码：user123
角色：普通用户
```

**权限**:
- ✅ 上传照片
- ✅ 管理自己的照片（编辑、删除）
- ✅ 查看所有公开照片
- ✅ 下载原图（无水印）

---

### 数据库访问凭据

**应用数据库用户**:
```
用户名：photo_user
密码：123456
数据库：photo_gallery
主机：localhost
```

**MySQL Root 用户** (如果设置):
```
用户名：root
密码：（安装时设置的密码）
```

---

## ✨ 核心功能演示

### 1. 用户功能

#### 注册和登录
- 访问 https://3.236.233.166/register 注册新用户
- 访问 https://3.236.233.166/login 登录

#### 照片上传
1. 登录后点击 "Upload Photo"
2. 填写标题和描述
3. 选择图片（JPG, PNG，最大 2MB）
4. 上传后自动添加水印

#### 照片查看
- 照片详情页显示上传者水印（右下角）
- 响应式设计，支持手机和桌面浏览

#### 照片下载
- **登录用户**: 下载原图（无水印）
- **游客用户**: 下载带对角水印版本

### 2. 管理员功能

访问 https://3.236.233.166/admin

#### Dashboard（仪表盘）
- 总用户数统计
- 总照片数统计
- 今日上传统计
- 热门上传者排行

#### User Management（用户管理）
- 查看所有用户列表
- 创建新用户
- 编辑用户信息
- 删除用户
- 设置管理员权限

#### Photo Management（照片管理）
- 查看所有照片
- 编辑照片信息
- 删除任意照片（内容审核）

---

## 🔒 安全特性

### HTTPS 加密
- ✅ SSL/TLS 加密通信
- ✅ HTTP 自动重定向到 HTTPS
- ✅ 自签名证书（365天有效期）

### 应用安全
- ✅ CSRF 保护
- ✅ SQL 注入防护
- ✅ XSS 防护
- ✅ 文件上传验证
- ✅ 基于 Policy 的授权
- ✅ 密码加密存储（bcrypt）

### 服务器安全
- ✅ UFW 防火墙配置
- ✅ 仅开放必要端口（22, 80, 443）
- ✅ 文件权限正确设置
- ✅ 目录列表禁用

---

## 📊 技术实现亮点

### 1. 高级图像处理
- **动态水印生成**: 使用 GD Library
- **条件水印逻辑**: 
  - 显示水印: 右下角用户名
  - 下载水印: 对角重复版权保护
- **实时处理**: 无需预生成，按需生成

### 2. Laravel 最佳实践
- **RESTful 路由**: 标准的资源路由
- **Policy 授权**: 细粒度权限控制
- **Eloquent ORM**: 优雅的数据库交互
- **Blade 模板**: 组件化视图设计
- **Middleware**: 身份验证和授权

### 3. 响应式设计
- **Tailwind CSS**: 现代化 UI 框架
- **移动优先**: 完美支持各种设备
- **优雅动画**: 平滑的交互体验

### 4. 生产级部署
- **LAMP 栈**: 企业级架构
- **代码优化**: Production 模式
- **缓存配置**: 性能优化
- **日志系统**: 完整的错误追踪

---

## 🚀 快速部署命令

### 在 AWS 服务器上完成以下步骤：

```bash
# 1. 配置 HTTPS
cd /var/www/photo-gallery
git pull origin main
chmod +x setup-https.sh
./setup-https.sh

# 2. 安装 phpMyAdmin
chmod +x install-phpmyadmin.sh
./install-phpmyadmin.sh

# 3. 创建测试用户
chmod +x create-users.sh
./create-users.sh
```

---

## 📁 项目文件结构

```
/var/www/photo-gallery/
├── app/
│   ├── Http/Controllers/
│   │   ├── PhotoController.php    # 照片 CRUD + 水印
│   │   ├── AdminController.php     # 管理面板
│   │   └── ProfileController.php   # 用户资料
│   ├── Models/
│   │   ├── User.php                # 用户模型
│   │   └── Photo.php               # 照片模型
│   └── Policies/
│       └── PhotoPolicy.php         # 照片授权策略
├── database/
│   └── migrations/                 # 数据库迁移
├── resources/views/
│   ├── photos/                     # 照片视图
│   ├── admin/                      # 管理面板视图
│   └── layouts/                    # 布局模板
├── storage/app/public/photos/      # 上传的照片
└── public/                         # 公开访问目录
```

---

## 🧪 测试场景

### 场景 1: 游客访问
1. 访问 https://3.236.233.166
2. 浏览照片库
3. 点击照片查看详情（显示水印）
4. 下载照片（带对角水印版本）

### 场景 2: 普通用户
1. 注册账户或使用 user@photogallery.com 登录
2. 上传测试照片
3. 编辑自己的照片
4. 下载照片（原图无水印）

### 场景 3: 管理员
1. 使用 admin@photogallery.com 登录
2. 访问 /admin 管理面板
3. 查看统计数据
4. 管理用户和照片

---

## 📞 故障排除

### 常见问题

**无法访问 HTTPS**
```bash
# 检查 SSL 配置
sudo apache2ctl -S
sudo systemctl status apache2
```

**phpMyAdmin 404 错误**
```bash
# 检查符号链接
ls -la /var/www/html/phpmyadmin
# 重新创建链接
sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin
```

**图片上传失败**
```bash
# 检查权限
ls -la /var/www/photo-gallery/storage/app/public/photos
# 修复权限
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage
```

---

## 📝 项目文档

完整文档请查看：
- [完整部署指南](AWS_DEPLOYMENT.md)
- [快速入门](DEPLOYMENT_QUICKSTART.md)
- [功能说明](FEATURES.md)
- [管理面板指南](ADMIN_PANEL.md)
- [安全配置](SECURITY_FIX.md)

---

## ✅ 部署检查清单

- [x] 应用可通过公网 IP 访问
- [x] HTTPS 已配置（自签名证书）
- [x] HTTP 自动重定向到 HTTPS
- [x] phpMyAdmin 已安装并可访问
- [x] 管理员账户已创建
- [x] 普通用户账户已创建
- [x] 数据库正常运行
- [x] 照片上传功能正常
- [x] 水印功能正常
- [x] 管理面板可访问
- [x] 防火墙已配置
- [x] 文件权限正确设置

---

**部署完成时间**: 2025-12-07  
**部署状态**: ✅ Production Ready  
**维护者**: Ayw

---

🎉 **恭喜！您的 Photo Gallery 应用已成功部署！**
