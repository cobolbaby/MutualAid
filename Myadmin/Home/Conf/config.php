<?php
if (is_file('./Conf/common.php'))
    return array_merge(require_once('./Conf/common.php'), array(
        'URL_MODEL' => 1,    // PATHINFO模式
    ));
