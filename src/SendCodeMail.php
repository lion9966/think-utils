<?php

namespace lion9966\utils;

use lion9966\utils\mailer\facade\Mailer;
use think\Exception;
use think\facade\Cache;

class SendCodeMail
{
    //发送地址
    protected $from;
    //发送者名称
    protected $fromName;
    //过期时间，分钟
    protected $expire = 900;
    //code码长度
    protected $length = 4;

    //收件人地址
    protected $to;
    //邮件主题
    protected $title;
    //邮件内容
    protected $content;
    //验证码
    protected $code;

    private $cache = null;


    /**
     * 初始化
     * @param string $from 发送地址
     * @param string $fromName 发送者名称
     * @param int $expire 过期时间
     * @param int $length 码长度
     */
    public function __construct(string $config = null)
    {
        if (is_null($config)) {
            $config = $this->config->get('utils.codeMail.from', []);
        } else {
            $config = $this->config->get('utils.codeMail.from' . $config, []);
        }

        foreach ($config as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }

        $langSet = app()->lang->getLangSet();
        app()->lang->load([
            __DIR__ . DIRECTORY_SEPARATOR . 'lang' . $langSet . DIRECTORY_SEPARATOR . 'sendCodeMail.php'
        ]);
    }

    /**
     * 创建邮件
     * @param string $to 收件人地址
     * @return bool|int|string
     */
    public function create(string $to)
    {
        $code          = $this->setCode();
        $this->to      = $to;
        $this->title   = $this->setTitle();
        $this->content = $this->setContent($code['value']);
        try {
            Mailer::from($this->from)
                ->to($this->to)
                ->subject($this->title)
                ->html($this->content)
                ->send();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    protected function setTitle()
    {
        return lang('Code title');
    }

    protected function setContent($code)
    {
        $min        = $this->expire / 60;
        $expireTime = date("Y-m-d H:i", time() + ($this->expire) - 60);
        $content    = lang('Code content dear');
        $content    .= lang('Code content verification code', $code);
        $content    .= lang('Code content fill out reminder', [$min, $expireTime]);
        $content    .= '<p><br><br>' . $this->fromName . '</p>';
        $content    .= '<p>' . date("Y-m-d H:i") . '</p>';
        return $content;
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
        $code = rand((int)$star, (int)$end);
        $hash = password_hash($code, PASSWORD_BCRYPT, ['cost' => 10]);
        Cache::set('email', ['key' => $hash,], $this->expire);
        return [
            'value' => $code,
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
        if (!Cache::has('email')) {
            return false;
        }
        $email = Cache::get('email');
        $res   = password_verify($code, $email['key']);
        if ($res) {
            Cache::delete('email');
        }
        return $res;
    }
}
