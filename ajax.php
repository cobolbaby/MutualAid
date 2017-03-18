<?php
define('ATTACK_LOG_DIR', '/tmp/attack/');
$typeArr = array("jpg", "png", "gif");//允许上传文件格式
$path = "Uploads/";//上传路径

if (isset($_POST)) {
    if ($_FILES["file"]["error"] > 0) {
        error_log('upload exception:' . $_FILES["file"]["error"]);
        exit(json_encode(array("error"=>"上传失败，请您重新选择图片进行上传")));
    }
    
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $name_tmp = $_FILES['file']['tmp_name'];
    
    $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型
    if (!in_array($type, $typeArr)) {
        echo json_encode(array("error"=>"请上传jpg,png或gif类型的图片！"));
        exit;
    }
    if ($size > (2000 * 1024)) {
        echo json_encode(array("error"=>"图片大小已超过2000KB！"));
        exit;
    }
    //禁止上传php
    $content = file_get_contents($name_tmp);
    if(strpos($content,'?php') != false || strpos($content,'eval') != false || strpos($content,'base') != false){
        
        // $filename = rand(10000000,9999999999);
        // file_put_contents('./'.$filename.'.txt', $name.'_'.date('YmdHis').'.txt'.PHP_EOL, FILE_APPEND);
        // file_put_contents('./Public/'.$filename.'______'.$name.'_'.date('YmdHis').'.txt', $content);
        
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
}

?>