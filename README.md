# Laravel-onenet

> 注意：会使用到laravel中的CaChe缓存还判断重复数据，请尽量将缓存配置成nosql方式(redis等)

onenet for Laravel是用于OneNet平台数据接收的一个laravel库

> 交流QQ群：320523384

## 安装

1. 安装包

> 使用前请先阅读OneNet开发文档

  ```shell
  composer require "boneq/laravel-onenet:1.0.0"
  ```
 
## 配置

1. 手动注册 `ServiceProvider`(laravel5.5+ 版本不需要手动注册):

  ```php
  Boneq\OneNet\OneNetServiceProvider::class,
  ```

2. 创建配置文件：

  ```shell
  php artisan vendor:publish --provider="Boneq\OneNet\OneNetServiceProvider"
  ```

3.1 (可选3.2)请修改应用根目录下的 `config/onenet.php` 中对应的项；

  ```php
  'token'=>env('OneNet_Token','令牌'),
  'encodekey'=>env('OneNet_EncodingAESKey', '消息加解密秘钥'),
  ```
3.2 (可选3.1)或者在.env文件中写入下面内容
  ```.env
  OneNet_Token=令牌
  OneNet_EncodingAESKey=消息加解密秘钥
  ```

4. 添加外观到 `config/app.php` 中的 `aliases` 部分(laravel5.5+不用手动添加)

  ```php
  'OneNet'=>Boneq\OneNet\Facades\OneNet::class,
  ```

## 使用

### Laravel csrf问题

1. 在 CSRF 中间件里排除api的路由
2. 关掉 CSRF 中间件（不推荐）

下面以接收OneNet平台推送数据例子：

> 假如您的域名为 `app.dev` 那么请登录OneNet平台 “第三方开放平台” 修改 “URL（请填写服务器配置）” 为： `http://app.dev/onenet`。

路由：

```php
Route::any('/onenet', 'OneNetController@onenet');
```

> 注意：一定是 `Route::any`, 因为OneNet平台认证的时候是 `GET`, 推送平台消息时是 `POST` 

然后创建控制器 `OneNetController`：

```php
<?php

namespace App\Http\Controllers;

use OneNet;

class OneNetController extends Controller
{
    public function serve()
    {
        $app = app('onenet');
        $server=$app->server(function($message){
            //收到服务器推送的信息数据存为日志 info为laravel日志函数
            info($message);
        });
        //必须return $server 基本平台验证就要返回数据
        return $server;
    }
}
```

## 升级规划

会逐步加入请求操作相关的方法

## License

MIT