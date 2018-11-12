# 阿里云短信封装

# 原来地址 https://packagist.org/packages/mrgoon/aliyun-sms

## 安装

```
composer require shuliuzhenhua/aliyun-sms

```

## 使用

```
use Shuliuzhenhua\AliSms;
$config = [
    'access_key' => 'your access key',
    'access_secret' => 'your access secret',
    'sign_name' => 'your sign name',
];


$response = AliSms('phone number', 'tempplate code', ['name'=> 'value in your template'], $config);
```

## 在 tp5 中使用

新增一个 `aliyunsms.php` 的配置文件

在 .env 中添加

```
ALIYUN_SMS_AK=your access key
ALIYUN_SMS_AS=your secret key
ALIYUN_SMS_SIGN_NAME=sign name
```

aliyunsms.php

```
return [
    'access_key' => \think\Env::get('ALIYUN.SMS.Ak'),
    'access_secret' => \think\Env::get('ALIYUN.SMS.AS'),
    'sign_name' => \think\Env::get('ALIYUN.SMS.SIGN.NAME'),
];

```
