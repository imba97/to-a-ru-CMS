# とあるCMS

你可以用它生成固定格式的静态文章、文章列表

# 开发

- [x] 文章的增删改
- [x] 根据模板生成首页、文章列表、文章页
- [x] 自定义配置
- [x] 复制文件配置
- [ ] 根据多个文章ID生成对应文章
- [ ] 文章类型的增删改
- [ ] 网站增删改查
- [ ] ...

# 使用

1. 使用composer安装laravel依赖：`composer install`
2. 使用npm安装前端依赖：`npm install`并打包：`npm run dev`
3. 配置文件 .env
4. 使用artisan创建数据表：`php artisan migrate`
5. 如果是测试，可生成填充数据：`php artisan db:seed`，或只填充某一项，比如文章：`php artisan db:seed --class=ArticlesTableSeeder`

测试账号：imba97

测试密码：111111

也可以注册

# 开发框架

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

# 更新日志

 - 2020-01-20

   为laravel-u-editor增加FTP上传，但作者还没处理合并请求，如果需要FTP上传你得单独在这里下载：[laravel-u-editor](https://github.com/imba97/laravel-u-editor)

 - 2020-04-07
 
   优化复制文件，现在可以自动创建目录结构
   
   增加网站编辑功能
   
   list页面的分页样式改成可定制的了