<?php
return array(

    'DB_TYPE'   => 'mysqli',     // 数据库类型
    'DB_PORT'   => '3306',      // 端口
    'DB_PREFIX' => 'ot_',       // 数据库表前缀
    'DB_CHARSET'=> 'utf8',      // 字符集

    // 本地数据库
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'antdomain', // 数据库名
    'DB_USER'   => 'root',      // 用户名
    'DB_PWD'    => '',          // 密码

    // 远程数据库
    // 'DB_HOST'   => '',       // 服务器地址
    // 'DB_NAME'   => '',       // 数据库名
    // 'DB_USER'   => '',       // 用户名
    // 'DB_PWD'    => '',       // 密码

    'URL_MODEL'         => 2,   // 默认REWRITE模式
    'URL_HTML_SUFFIX'   => '',  // URL伪静态后缀设置
    'DEFAULT_THEME'     => 'default', // 设置默认的模板主题,决定模板存放路径
    'DEFAULT_FILTER'    => 'htmlspecialchars', // 默认参数过滤方法

    // 按模块加载自定义配置文件
    'LOAD_EXT_CONFIG'   => 'jerry_config,mm_config,jj_config,tx_config,hot_config',

    // 加载全局自定义配置
    'DB_BACKUP'         => '/root/backup', // 数据库备份存放路径
    'WEBSITE_NAME'      => '凝聚力',
    // 'SHOW_ERROR_MSG'    => true,      // 屏蔽异常输出

);

