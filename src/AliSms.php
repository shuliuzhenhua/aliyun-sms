<?php
namespace Shuliuzhenhua\AliSms;

use Mrgoon\AliyunSmsSdk\Autoload;
use Mrgoon\AliyunSmsSdk\DefaultAcsClient;
use Mrgoon\AliyunSmsSdk\Profile\DefaultProfile;
use Mrgoon\Dysmsapi\Request\V20170525\SendSmsRequest;

class AliSms
{
    public static function sendSms(String $to, String $template_code, array $data, array $config = [])
    {
        if ($config) {
            $accessKeyId = $config['access_key'];
            $accessKeySecret = $config['access_secret'];
            $signName = $config['sign_name'];
        } else {
            $accessKeyId = config('aliyunsms.access_key');
            $accessKeySecret = config('aliyunsms.access_secret');
            $signName = config('aliyunsms.sign_name');
        }

        // 短信api 产品名称
        $product = 'Dysmsapi';
        // 短信产品域名
        $domain = 'dysmsapi.aliyuncs.com';
        // 暂时不支持多语言
        $region = 'cn-hangzhou';

        // 初始化访问acsCleint;
        Autoload::config();

        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        DefaultProfile::addEndpoint($region, $region, $product, $domain);
        $acsClient = new DefaultAcsClient($profile);

        $request = new SendSmsRequest();
        $request->setPhoneNumbers($to);
        $request->setSignName($signName);
        $request->setTemplateCode($template_code);

        if ($data) {
            $request->setTemplateParam(json_encode($data));
        }

        return $acsClient->getAcsResponse($request);
    }
}
