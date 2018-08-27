<?php
return [
    // 远程地址
    'remote_url' => env('REMOTE_URL', 'localhost'),
    // 登录的平台代号
    'auth_type' => 'AUTH_OFFICE',
    // 接口网站是http协议还是https协议访问，正式网站推荐https
    'https' => env('REMOTE_HTTPS', false),
    // 登录接口地址
    'auth_path' => 'auth',
    // 修改密码接口地址
    'change_password_path' => 'changePass',
];