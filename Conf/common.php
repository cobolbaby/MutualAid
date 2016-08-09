<?php
return array(

    'DB_TYPE'   => 'mysql',     // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_PORT'   => '3306',      // 端口
    'DB_NAME'   => 'antdomain',          // 数据库名
    'DB_USER'   => 'root',          // 用户名
    'DB_PWD'    => '',          // 密码
    'DB_PREFIX' => 'ot_',          // 数据库表前缀
    'DB_CHARSET'=> 'utf8',      // 字符集

    'URL_MODEL'         => 2,    // 默认REWRITE模式
    'URL_HTML_SUFFIX'   => '',   // URL伪静态后缀设置

    'DEFAULT_THEME'     => 'default', // 设置默认的模板主题,决定模板存放路径

    'SHOW_ERROR_MSG'    => false,  // 屏蔽异常输出

    'DEFAULT_FILTER'    => 'htmlspecialchars', // 默认参数过滤方法

    'DB_BACKUP' => '/root/backup', // 数据库备份存放路径

    'LOAD_EXT_CONFIG' => 'jerry_config,mm_config,jj_config,tx_config,hot_config',

);

