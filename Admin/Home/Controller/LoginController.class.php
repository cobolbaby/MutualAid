<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller
{
    public function index()
    {
        $this->display('index/login');
    }

    public function logincl()
    {
    	header("Content-Type:text/html; charset=utf-8");
    	if (IS_POST) {

            // $this->error('系統暫未開放!');

	    	$username = I('post.account');
			$pwd = I('post.password');
			$verCode = I('post.verCode');//驗證碼
			if(!$this->check_verify($verCode)) {
				exit("<script>alert('驗證碼錯誤,請刷新驗證碼！');history.back(-1);</script>");
			} else {
    			$user=M('member')->where(array('MB_username'=>$username))->find();
     			if(!$user || $user['mb_userpwd']!=md5($pwd)){
    				exit("<script>alert('賬號或密碼錯誤,或被禁用！');history.back(-1);</script>");
    			}else{
					// 记录管理员名称以及管理员权限
                    session('uid', $user['mb_id']);
    				session('adminuser', $user['mb_username']);
    				session('adminqx', $user['mb_right']);
    				session('logintime', NOW_TIME);
                    // 记录登入日志
                    $record['date']    = date('Y-m-d H:i:s');
                    $record['ip']      = get_client_ip();
                    $record['user']    = $username;
                    $record['leixin']  = 1; // 0用户,1后台管理员
                    M ('drrz')->add( $record );
    				exit("<script>alert('登入成功！');document.location.href='/admin.php/Home/Index/main';</script>");
            	}
            }

    	}
    }

    public function logout()
    {
        session('[destroy]'); // 销毁session
    	$this->success('退出成功','/admin.php/Home/Login');
    }

    //驗證碼模塊
    public function check_verify($code)
    {
        $verify = new \Think\Verify();
    	return $verify->check($code);
    }

    public function verify() {
    	$config =    array(
    			'fontSize'    =>    16,    // 驗證碼字體大小
    			'length'      =>    5,     // 驗證碼位數
    			'useCurve'    =>    false, // 關閉驗證碼雜點
    		'useCurve' => false,
    	);

    	$Verify = new \Think\Verify($config);
    	$Verify->codeSet = '0123456789';
    	$Verify->entry();
    }

}