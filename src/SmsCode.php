<?php

namespace lion9966\utils;

use Qcloud\Sms\SmsSingleSender;
use think\Cache;
use think\Config;

class SmsCode
{
    protected $code;
    //配置
    private $config = null;

    private $cache = null;

    protected $app_id = null;

    protected $app_key = null;

    protected $sms_sign = null;

    protected $template_id = null;
    //过期时间，分钟
    protected $expire = 900;

    protected $length = 4;

    protected $register_open = 1;


    public function __construct(Config $config, Cache $cache)
    {
        $this->config = $config;
        $this->cache  = $cache;
    }

    protected function configure(string $config = null): void
    {
        if (is_null($config)) {
            $config = $this->config->get('utils.codeSms', []);
        } else {
            $config = $this->config->get('utils.codeSms.' . $config, []);
        }

        foreach ($config as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    /**
     * 创建smsCode
     * @return string
     */
    protected function setCode(): array
    {
        $star = 1;
        $end  = 9;
        for ($i = 0; $i < $this->length; $i++) {
            $star = $star * 10 + 1;
            $end  = $end * 10 + 9;
        }
        $smsCode = rand((int)$star, (int)$end);
        $hash    = password_hash($smsCode, PASSWORD_BCRYPT, ['cost' => 10]);
        $this->cache->set('sms', ['key' => $hash,], $this->expire);
        return [
            'value' => $smsCode,
            'key'   => $hash,
        ];
    }

    /**
     * 验证短信验证码是否正确
     * @param $code
     * @return bool
     */
    public function check($code): bool
    {
        if (!$this->cache->has('sms')) {
            return false;
        }
        $sms = $this->cache->get('sms');
        $res = password_verify($code, $sms['key']);
        if ($res) {
            $this->cache->delete('sms');
        }
        return $res;
    }

    /**
     * 发送手机验证码
     * @param $phone 手机号码
     * @param string|null $confing
     * @return bool|string
     */
    public function create($phone, string $config = null)
    {
        $this->configure($config);
        $code = $this->setCode();

        try {
            $sender = new SmsSingleSender($this->{'app_id'}, $this->{'app_key'});
            $params = [$code['value'], $this->expire / 60];
            $result = $sender->sendWithParam("86", $phone, $this->{'template_id'}, $params, $this->{'sms_sign'}, "", "");
            $rsp    = json_decode($result);
            if ($rsp->result == 0) return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return false;
    }
}
