<?php

namespace lion9966\utils;

use lion9966\utils\exception\ResponseCode;
use DateTimeImmutable;
use DateTimeZone;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;

//use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use think\Exception;

class Token
{
    //过期/退出缓存存储
    const DELETE_TOKEN = 'delete_token';
    //token信息
    protected $accessToken = '';
    //存储字段名称
    protected $storages = [
        'user_id' => '',
        'is_root' => false,
    ];
    //配置jwt
    protected $config = [
        'id'       => '', //token的唯一标识
        'issuer'   => '', //签发人
        'audience' => '', //接收人 魔术方法 __construct 动态设置
        'sign'     => '', //签名密钥
        'expire'   => 0, //有效期 8640 一天
    ];

    public function __construct()
    {
        $this->config             = [
            'id'     => config('utils.jwt.id'),
            'issuer' => config('utils.jwt.issuer'),
            'sign'   => config('utils.jwt.sign'),
            'expire' => config('utils.jwt.expire'),
        ];
        $this->config['audience'] = !app()->runningInConsole() ? md5(request()->server('HTTP_USER_AGENT')) : '';

        $langSet = app()->lang->getLangSet();
        app()->lang->load([
            __DIR__ . DIRECTORY_SEPARATOR . 'lang' . $langSet . DIRECTORY_SEPARATOR . 'token.php'
        ]);
    }

    /**
     * 设置数组
     * @param $name
     * @param string $value
     * @return $this
     */
    public function withConfig($name, string $value = ''): Token
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->withConfig($k, $v);
            }
            return $this;
        }
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
        return $this;
    }

    /**
     * 缓存数据设置
     * @param $name
     * @param string $value
     * @return $this
     */
    public function withStorages($name, string $value = ''): Token
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->withStorages($k, $v);
            }
            return $this;
        }
        $this->storages[$name] = $value;
        return $this;
    }

    /**
     * 对获取的Token进行格式验证
     * @param string $authorization
     * @return $this
     * @throws Exception
     */
    public function withRequestToken($authorization = ''): Token
    {
        $authorization = $authorization ?: request()->header('Authorization');
        if (!$authorization) throw new Exception(lang('Token cannot be empty'), ResponseCode::ACCESS_TOKEN_ERROR);

        $authorizationArr = explode(' ', $authorization);
        if (count($authorizationArr) != 2) throw new Exception(lang('Token cannot be empty'), ResponseCode::ACCESS_TOKEN_ERROR);
        if ($authorizationArr[0] != 'Bearer') throw new Exception(lang('Token format error'), ResponseCode::ACCESS_TOKEN_ERROR);

        $this->accessToken = $authorizationArr[1];
        if (!$this->accessToken) throw new Exception(lang('Token cannot be empty'), ResponseCode::ACCESS_TOKEN_ERROR);
        if (count(explode('.', $this->accessToken)) <> 3) throw new Exception(lang('Token format error'), ResponseCode::ACCESS_TOKEN_ERROR);

        return $this;
    }

    /**
     * 获取token令牌
     * @return string
     */
    public function getRequestToken(): string
    {
        return $this->accessToken;
    }

    /**
     * 获取Token
     * @return \Lcobucci\JWT\Token
     * @throws Exception
     */
    public function getJWTToken(): \Lcobucci\JWT\Token
    {
        try {
            //解析token
            return $this->getConfiguration()->parser()->parse((string)$this->accessToken);
        } catch (\Exception $e) {
            throw new Exception(lang('Token parsing error'));
        }
    }

    /**
     * 生成配置对象
     * @return Configuration
     */
    protected function getConfiguration(): Configuration
    {
        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($this->config['sign'])
        );
    }

    /**
     * 生成token
     * @return string
     */
    public function getToken(): string
    {
        $config = $this->getConfiguration();
        $now    = new DateTimeImmutable();

        $token = $config->builder()
            ->issuedBy($this->config['issuer'])
            //接收人
            ->permittedFor($this->config['audience'])
            //唯一标志
            ->identifiedBy($this->config['id'])
            //签发时间
            ->issuedAt($now)
            //生效时间（立即生效： 签发时间前一秒）
            ->canOnlyBeUsedAfter($now->modify('-1 second'))
            //过期时间
            ->expiresAt($now->modify("+{$this->config['expire']} second"));
        //存储数据
        foreach ($this->storages as $key => $val) {
            $token = $token->withClaim($key, $val);
        }
        //签名
        return $token->getToken($config->signer(), $config->signingKey())->toString();
    }

    /**
     * token注销处理
     * @param null $token
     */
    public function delete($token = null)
    {
        $deleteToken   = cache(self::DELETE_TOKEN);
        $deleteToken[] = $token ?: $this->accessToken;
        cache(self::DELETE_TOKEN, $deleteToken);
    }

    /**
     * 获取token存储数据，默认获取当前token存储的user_id
     * @param string $name
     * @return mixed|null
     * @throws Exception
     */
    public function getClaim($name = 'user_id')
    {
        return $this->getJWTToken()->claims()->get($name);
    }

    /**
     * Token的校验
     * @return $this
     * @throws Exception
     */
    public function validate()
    {
        if (empty($this->accessToken)) throw new Exception(lang('Token parsing error'));
        //注销token逻辑
        $deleteToken = cache(self::DELETE_TOKEN) ?: [];
        if (in_array($this->accessToken, $deleteToken)) throw new Exception(lang('Token has been cancelled'));
        //验证签发人
        $configuration = $this->getConfiguration();
        $jwtToken      = $this->getJWTToken();
        $issued        = new IssuedBy($this->config['issuer']);
        if (!$configuration->validator()->validate($jwtToken, $issued)) throw new Exception(lang('Issue exception'));
        //验证接收
        $audience = new PermittedFor($this->config['audience']);
        if (!$configuration->validator()->validate($jwtToken, $audience)) throw new Exception(lang('Receive exception'));

        //验证是否过期
        $timezone = new DateTimeZone('Asia/Shanghai');
        $now      = new SystemClock($timezone);
        $valid_at = new LooseValidAt($now);
        //        $valid_at = new ValidAt($now);
        if (!$configuration->validator()->validate($jwtToken, $valid_at)) throw new Exception(lang('Token has expired'));

        return $this;
    }

}
