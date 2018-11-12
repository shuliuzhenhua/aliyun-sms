<?php
namespace Shuliuzhenhua\AliSms;

use Mrgoon\AliyunSmsSdk\Autoload;
use Mrgoon\AliyunSmsSdk\DefaultAcsClient;
use Mrgoon\AliyunSmsSdk\Profile\DefaultProfile;
use Mrgoon\Dysmsapi\Request\V20170525\SendSmsRequest;

class AliSms
{
    /**
     * 发送短信
     * @param int $to
     * @param string $template_code
     * @param array $data
     * @param array $config
     * @return mixed|\SimpleXMLElement
     */
    public static function sendSms(int $to, string $template_code, array $data, array $config = [])
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

        // 可选如果短信模板中包含变量则用需要传入数组
        if ($data) {
            $request->setTemplateParam(json_encode($data));
        }

        $result = $acsClient->getAcsResponse($request);
        return self::handleResult($result);
    }

    /**
     * 处理返回结果
     * @param object $result
     * @return object
     */
    private static function handleResult($result)
    {
        $error = [
            'isp.SYSTEM_ERROR' => '请重试。',
            'isv.MOBILE_NUMBER_ILLEGAL' => '请输入国内手机号',
            'isv.BUSINESS_LIMIT_CONTROL' => '发送频率过高，请稍后重试',
            'isv.MOBILE_COUNT_OVER_LIMIT' => '短信群发数量超过限制>>最多支持1000个以逗号隔开的手机号码<<',
            'isv.OUT_OF_SERVICE' => '短信服务已停机,>>账户余额不足，请充值<<',
            'isv.PRODUCT_UN_SUBSCRIPT' => '当前用户未开通短信服务>>请前往阿里云开通<<',
            'isv.PRODUCT_UNSUBSCRIBE' => '未开通短信服务>>请前往阿里云开通<<',
            'isv.ACCOUNT_NOT_EXISTS' => '当前账户不存在>>请前往阿里云验证<<',
            'isv.ACCOUNT_ABNORMAL' => '当前账户异常>>请前往阿里云查看详细信息<<',
            'isv.SMS_TEMPLATE_ILLEGAL' => '短信模板不合法>>请前往阿里云-短信服务调整模板信息<<',
            'isv.SMS_SIGNATURE_ILLEGAL' => '短信签名不合法>>请前往阿里云-短信服务调整签名信息<<',
            'isv.INVALID_PARAMETERS' => '参数使用错误>>请检查前往项目检查SMS.php文件<<',
            'isv.TEMPLATE_MISSING_PARAMETERS' => '短信模板缺少参数>>请检查前往项目检查SMS.php文件<<',
            'isv.INVALID_JSON_PARAM' => '短信模板json参数错误>>请前往阿里云短信服务调整短信模板<<',
            'isv.BLACK_KEY_CONTROL_LIMIT' => '当前发送短信模板含有黑名单字符>>请前往阿里云短信服务调整短信模板<<',
            'isv.PARAM_LENGTH_LIMIT' => '短信模板参数太长>>请前往阿里云短信服务调整短信模板<<',
            'isv.PARAM_NOT_SUPPORT_URL' => '发送短信中含有url地址>>请前往阿里云短信服务调整短信模板<<',
            'isv.AMOUNT_NOT_ENOUGH' => '账户余额不足>>请前往阿里云充值<<',
            'InvalidVersion' => '短信服务使用api版本号错误>>请检查前往项目检查短信服务sdk文件<<',
            'InvalidAction.NotFound' => '调用sdk接口名称错误>>请前往阿里云官网确认并调整sdk文件<<',
            'OK' => 'OK'
        ];
        $result->errorMsg = $error[$result->Code];
        return $result;
    }
}
