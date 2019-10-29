<?php
$common_config_file = './Conf/common.php';
// 文件存在的情况下，is_file比file_exists要快N倍
if (is_file($common_config_file)) {
    $common_conf = load_config($common_config_file, 'php');

    $ipWhitelistFile = './Conf/adminipWhitelist.php';
    $iplist = array();
    if (is_file($ipWhitelistFile)) {
        $iplist = load_config($ipWhitelistFile, 'php');
    }

    return array_merge($common_conf, $iplist, array(
        'URL_MODEL' => 1,    // PATHINFO模式
    ));
}
