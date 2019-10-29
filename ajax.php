<?php
session_start();
if (!isset($_SESSION['uid'])) { // 系统暂未设置session前缀
    exit(json_encode(array("error"=>"上传失败，请您重新登陆后再选择图片进行上传")));
}
if (!isset($_FILES["file"]) || $_FILES["file"]["error"] > 0) {
    error_log('upload exception:' . var_export($_FILES, true));
    exit(json_encode(array("error"=>"上传失败，请您重新选择图片进行上传")));
}

define('ATTACK_LOG_DIR', '/tmp/mutual-aid/attack/');
$typeArr = array("jpg", "png");//允许上传文件格式
$path = "Uploads/";//上传路径

$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'];
$name_tmp = $_FILES['file']['tmp_name'];

$type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型
if (!in_array($type, $typeArr)) {
    exit(json_encode(["error"=>"请上传jpg,png类型的图片！"]));
}
if ($size > (2000 * 1024)) {
    exit(json_encode(["error"=>"图片大小已超过2000KB！"]));
}
//禁止上传php
/*
    <?phP
        $a = file_get_contents("http://www.dssj.cc/me2.txt");
        file_put_contents("./Uploads/123545614545.ph"."p",$a);
    ?>
*/
// 判断图片文件真实类型
// 通过幻数识别
$content = file_get_contents($name_tmp);
if(strpos($content,'?php') != false || strpos($content,'eval') != false || strpos($content,'base') != false){

    file_put_contents(ATTACK_LOG_DIR . $name, $content);
    unlink($name_tmp);
    exit;
}

$pic_name = time() . rand(10000, 99999) . "." . $type;//图片名称
$pic_url = $path . $pic_name;//上传后图片路径+名称
if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
    echo json_encode(array("error"=>"0","pic"=>$pic_url,"name"=>$pic_name));
} else {
    echo json_encode(array("error"=>"上传失败，请检查服务器配置！"));
}
