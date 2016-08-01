<?php
return array(

    'DB_TYPE'   => 'mysql',     // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_PORT'   => '3306',      // 端口
    'DB_NAME'   => '',  // 数据库名
    'DB_USER'   => '',      // 用户名
    'DB_PWD'    => '',          // 密码
    'DB_PREFIX' => '',       // 数据库表前缀
    'DB_CHARSET'=> 'utf8',      // 字符集

    'URL_MODEL'        => 2,    // URL模式  默认关闭伪静态
    'URL_HTML_SUFFIX'  => '',   // URL伪静态后缀设置

    'DEFAULT_THEME'    => 'default', // 设置默认的模板主题

    'SHOW_ERROR_MSG' => false,

    'DEFAULT_FILTER' => 'strip_tags,htmlspecialchars',

    // 'LANG_SWITCH_ON' => true,
    //'LANG_LIST'        => 'zh-tw', // 允许切换的语言列表 用逗号分隔
    //'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    // 'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
    'DEFAULT_LANG' => 'zh-tw',

    'DB_BACKUP' => './data',

    'LOAD_EXT_CONFIG' => 'jerry_config,mm_config,jj_config,tx_config,hot_config',

);

