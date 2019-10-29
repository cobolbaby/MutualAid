<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{

    public function index()
    {
        $this->display('login');
    }

    public function logincl()
    {
		//$this->error('系統暫未開放!');
        if (!IS_POST) {
            exit();
        }
        $username = trim(I('post.account'));
		$pwd = trim(I('post.password'));
		$verCode = trim(I('post.mycode'));//驗證碼

		if (!$this->check_verify($verCode)) {
			$this->ajaxReturn( array('nr'=>'验证码错误!','sf'=>0) );
		}

		$user=M('user')->where(array('UE_account'=>$username))->find();
		// [fix]返回值中字段大小写发生了变换
		if(!$user || $user['ue_password'] != md5($pwd)){
			$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤!','sf'=>0) );
        } elseif ($user['ue_status'] == 1) {
			$this->ajaxReturn( array('nr'=>'賬號被禁用!','sf'=>0) );
		}

		// 查询用户是否有未打款的问题
		$this->cspaycl($user);

		session('uid', $user['ue_id']);
		session('uname', $user['ue_account']);
        session('logintime', NOW_TIME);

		$record['date']     = date('Y-m-d H:i:s');
		$record['ip']       = get_client_ip();
		$record['user']     = $user['ue_account'];
		$record['leixin']   = 0;
		M( 'drrz' )->add( $record );

        $this->ajaxReturn(array('nr'=>'登录成功!','sf'=>1));

    }

    public function adminlogincl()
    {
        if (!IS_GET) {
            exit();
        }
    	header("Content-Type:text/html; charset=utf-8");

		$username = I('get.account');
		$pwd      = I('get.password');
		$pwd2     = I('get.secpw');

		$user = M('user')->where(array('UE_account'=>$username))->find();
		if(!$user || $user['ue_password']!=$pwd){
			$this->error('账号或密码错误,或被禁用!');
		}

    	session('uid',$user['ue_id']);
		session('uname',$user['ue_account']);
        session('logintime', NOW_TIME);
		$this->redirect('/');
	}

    public function logout()
    {
        session_destroy();
    	session_unset();
    	$this->redirect('Login/index');
    }

    //驗證碼模塊
    public function check_verify($code)
    {
    	$verify = new \Think\Verify();
    	return $verify->check($code);
    }

    public function verify()
    {
    	$config = array(
			'fontSize'    =>    16,    // 驗證碼字體大小
			'length'      =>    5,     // 驗證碼位數
			'useCurve'    =>    false, // 關閉驗證碼雜點
    	);
    	$Verify = new \Think\Verify($config);
    	$Verify->codeSet = '0123456789';
    	$Verify->entry();
    }

    // 超时打款
    public function cspaycl ($data)
    {
        if (!is_array($data)) {
            $this->error('参数错误');
        }
        if($data['ue_status'] == 2){
            return ;
        }

        $uname=$data['ue_account'];
        $fname=$data['ue_accname'];
        $uid=$data['ue_id'];

        $ppdd=M('ppdd');
        $where=array();
        $where['p_user']=$uname;
        $where['zt']=0;
        $rs=$ppdd->where($where)->select();

        if ($rs) {
            // 奖金设置-打款时间
        	$jjdktime=C("jjdktime");
        	// 奖金设置-超时未打款冻结提示语
            $jjhydjmsg=C("jjhydjmsg");
            // 奖金设置-超时未打款扣除上级金额
        	$jjhydjkcsjmoeney=C("jjhydjkcsjmoeney");
        	$cszt=0;
        	foreach( $rs as $v ) {
        		$pdtime = strtotime($v['date']);
                // 超时时间
        		$cstime = $pdtime + 3600 * $jjdktime;
        		if ( $cstime < time() ) {
        			$cszt=1;
        			break;
        		}
        	}

        	if ($cszt) {
        		$user= M('user');
        		$data2=array();
        		$data2['UE_ID']=$uid;
        		$data2['UE_status']=1;
        		$user->save($data2);

        		if ( $jjhydjkcsjmoeney && $fname ) {
            		$where=array();
            		$where['UE_account'] = $fname;
            		$user->where($where)->setDec('UE_money',$jjhydjkcsjmoeney);
        		}
        		die("<script>alert('.$jjhydjmsg.');history.back(-1);</script>");
        	}

        }

    }

}