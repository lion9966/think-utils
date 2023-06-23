# think-utils

thinkphp 6 常用工具集合

将thinkphp常用的：

* 错误提示（think-exception），
* 权限管理（think-permission），
* JWT，
* 集成腾讯短信验证，邮件验证码发送，
* 密码
* 随机数
* 树

### 安装

```
composer require lion9966/think-utils
```

### 使用方法

```
use lion9966\utils
```

* 错误提示（think-exception）：

```php
use lion9966\utils\exception\ResPonseCode;
use lion9966\utils\exception\ResPonseJson;

//通过server自动绑定think\exception\Handle，如果没绑定执行如下
//需要在app/provide.php内定义：
//'think\exception\Handle'=>\lion9966\utils\exception\ExceptionHandle::class
//进行自定义错误处理

//use lion9966\utils\exception\ExceptionHandle;
```

* 权限管理（think-permission）： 安装后，配置连接好数据库后，执行命令：

```
php think permission:install
```

进行数据库安装

使用方法，具体见: lion9966\think-permission

```php
//引用
use lion9966\utils\permission;
//权限验证中间件
use lion9966\utils\middleware;
permission::class;
```

* JWT： 自动集成lcobucci/jwt 4.3.0，已设置好Token,中间件直接使用即可

```php
//jwt权限验证中间件
use lion9966\utils\middleware;
Auth::class;

//引用Token方法
use lion9966\utils\Token;
```

* 集成腾讯短信验证，邮件验证码发送： 自动集成腾讯qcloudsms/qcloudsms_php短信发送，可在此基础SmsCode改发送其他短信；
  自动集成邮件swiftmailer/swiftmailer发送，可以在此基础上SendCodeMail改发送其他邮件； 默认只发送验证码

* 密码： 密码产生和验证方法Password

```php
//引用方法
use lion9966\utils\Password;
```

* 随机数： uuid和随机字符串产生方法Random

```php
//引用方法
use lion9966\utils\Random;
```

* 树：

```php
//引用方法
use lion9966\utils\Tree;
```

另外：验证/登录后用户数据

```php
//引用方法
use lion9966\utils\data\UserData;
//已在jwt验证后，绑定类
bind('user-data', UserData::class);
//信息初始化
app('user-data')->withId($userId)->withData($user);
```

配置，具体见：

```php
//权限验证过滤
auth;
//权限管理数据库配置
permission
//工具集合综合配置（涉及到敏感信息，必须修改）
utils
