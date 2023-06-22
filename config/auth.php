<?php

return [
    // token过滤
    'token_filter'      => [
        'captcha',
        'auth.login',
        'auth.register',
        'auth.sendCode',
        'auth.check',
    ],
    // 权限过滤
    'permission_filter' => [
        'captcha',
        'auth.login',
        'auth.logout',
        'auth.register',
        'auth.sendCode',
        'auth.check',
        'auth.info',
    ],
];
