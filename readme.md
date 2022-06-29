# JKDYAF - V2.3.0

## 基于 YAF + SWOOLE API框架

           ____ __ ______  _____    ______
          / / //_// __ \ \/ /   |  / ____/
     __  / / ,<  / / / /\  / /| | / /_
    / /_/ / /| |/ /_/ / / / ___ |/ __/
    \____/_/ |_/_____/ /_/_/  |_/_/

###

### 介绍

简单、直接、非传统

JkdYaf 一个简单、高性能常驻内存的PHP框架。

基于Yaf与Swoole开发，性能较传统基于 PHP-FPM 的框架有质的提升。

一款专为Api开发的轻量级框架。一款面向中小型企业级项目的高可用、低门槛PHP开源框架。

### [详细文档](http://jkdyaf.crytjy.com/)

![](JkdYaf.png)

### 特性

- HTTP 服务
- Redis连接池
- Jwt 认证
- 协程化
- 定时任务(秒级)
- 日志管理
- 路由管理
- Yac无锁共享内存
- 注解AOP
- 中间件
- Mysql连接池
- 异步任务
- Artisan命令行

### 服务器要求

- php 8.x 或更高版本
- yaf 3.3.x 或更高版本
- swoole 4.5.9 或更高版本
- mysql
- redis

### 安装JkdYaf

```bash
git clone https://github.com/crytjy/JkdYaf.git
```

### 启动

配置好后，进入项目根目录，启动项目

```bash
cd /yaf/
php bin/JkdYaf start          //启动 （守护进程模式 `php bin/JkdYaf start -d`）
php bin/JkdYaf restart        //重启        
php bin/JkdYaf stop           //停止
php bin/JkdYaf status         //详情
```

浏览器访问 `http://localhost:12222/api/index`

> {"code":1,"message":"success","data":"Hello JkdYaf !"}
