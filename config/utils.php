<?php

return [
    //jwt配置
    'jwt'      => [
        'id '      => 'ffsgsgwcxesxx', //token的唯一标识
        'issuer'   => 'fsdswrwxxxhrtxz@ijoduo.com@rtddxxxxxcxxx', //签发人
        'audience' => '',//接收人 魔术方法 __construct 动态设置
        'sign'     => 'fdbefsxdzsvzsfgasxxd@ijoduo.com@sdfs56fsfsscczxexcddggf', //签名密钥
        'expire'   => 3600 * 24, //有效期 8640 一天
    ],
    //腾讯云短信配置
    'codeSms'  => [
        'app_id'        => '1012345678',
        'app_key'       => 'afsdf55fsdsgdsdxcdfdsgsdgsgs',
        'sms_sign'      => 'xxxx科技',
        'template_id'   => '612345',
        'expire'        => 900, //验证码过期时间
        'lenght'        => 4,   //验证码长度
        'register_open' => 1,   //注册开放
    ],
    //密码
    'password' => [
        'salt' => 'fwrwwgver5cvdgdvfg5hdgf2xaw',//盐
    ],
    //可以配置在 mail.php 或 config.php 文件中, 但要保证能通过 mail.host 访问到配置信息
    'codeMail' => [
        'scheme'   => 'smtp', // 邮件驱动, 支持 smtp|sendmail|mail 三种驱动
        'host'     => 'smtp.163.com', // SMTP服务器地址
        'username' => 'xxx@163.com', // 发件邮箱名称
        'password' => 'xxxxxxxx', // 发件邮箱密码
        'port'     => 994, // SMTP服务器端口号,一般为25/465

        'options' => [],
        'dsn'     => '',

        'debug' => false, // 开启debug模式会直接抛出异常, 记录邮件发送日志
        'embed' => 'embed:', // 邮件中嵌入图片元数据标记

        'from' => [
            'from'     => 'xxx@163.com',
            'fromName' => 'xxxx科技',
            'expire'   => 900, //验证码过期时间
            'lenght'   => 4,   //验证码长度
        ],

    ],
];
