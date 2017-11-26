<?php
namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
	public function _initialize()
	{
		if (!session('?adminuser')) {
			$this->error('請先登錄','/luhu.php/Home/Login') && exit;
		}

		if (!$this->checkLoginWhiteList()) {
		    session('[destroy]'); // 销毁session
            $this->error('禁止访问');
        }

		/*$czmcsy = CONTROLLER_NAME . ACTION_NAME;
		$czmc = ACTION_NAME;
		if(session('adminqx') <> '1') {

			if($czmc<>'main'&&$czmc<>'df1'&&$czmc<>'top'&&$czmc<>'left'&&$czmc<>'userlist'&&$czmc<>'team'&&$czmc<>'rggl'&&$czmc<>'getTreeso'&&$czmc<>'getTree'&&$czmc<>'get_childs'&&$czmc<>'getTreeInfo'&&$czmc<>'getTreeBaseInfo'&&$czmc<>'userbtc'&&$czmc<>'jbzs'){
				$this->error('您暂无权限操作!','/luhu.php/Home/Index/df1');die;
				//echo '无权限';
			}

		}*/
	}

	private function checkLoginWhiteList()
	{
		// 检查IP地址访问
		$iplist = C('ADMIN_ALLOW_IP');
		if (is_null($iplist) || count($iplist) == 0) {
			// 如果没有iplist文件或者暂无配置
			return true;
		}
        foreach ($iplist as $v) {

        	// TODO::[fix]因防火墙的原因造成获取的IP非真实的客户端IP

        	// 100.10.1.*
        	if (strpos($v, '*') !== false) {
        		$ipBlock = substr($v, 0, -1);
        		$clientIp = get_client_ip(1);
        		if ($clientIp > ip2long($ipBlock.'1') && $clientIp < ip2long($ipBlock.'255')) {
        			return ture;
        		}
        	} else {
        		$clientIp = get_client_ip();
        		if ($clientIp == $v) {
        			return true;
        		}
        	}
        }
        return false;
	}

	public function check_verify($code)
	{
		$verify = new \Think\Verify ();
		return $verify->check ( $code );
	}

	public function getTreeBaseInfo($id) {

		if (! $id)

			return;

		$r = M ( "user" )->where ( array (

				'UE_account' => $id 

		) )->find ();

		if ($r)

			return array (

					"id" => $r ['ue_account'],

					"pId" => $r ['ue_accname'],

					"name" => $r ['ue_account'] . "[" .sfjhff($r['ue_check']).",". $r ['ue_truename'] . ",团队人数:" . $r ['tj_num'] . "]" 

			);

		return;

	}
	
	function jlj3($a,$b,$c,$d,$e){
		$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
		$ppddxx=M('tgbz')->where(array('id'=>$e))->find();
		$peiduidate=M('tgbz')->where(array('id'=>$e))->find();
		$data2['user']=$a;
		$data2['r_id']=$ppddxx['id'];
		$data2['date']=$peiduidate['date'];
		$data2['note']='管理奖'.C("jjtuijianrate").'%';
		$data2['jb']=$ppddxx['jb'];
		$data2['jj']=$b;
		$data2['leixin']=1;
		$data2['ds']=$d;
		M('user_jl')->add($data2);
		return $tgbz_user_xx['zcr'];
	}
	
	public function getTreeInfo($id) {
		
		
		
		static $trees = array ();
		$ids = self::get_childs ( $id );
		if (! $ids){
			return $trees;
		}

		$_SESSION['user_jb']++;
		//echo $_SESSION['user_jb'].'<br>';
		foreach ( $ids as $v ) {
			
			$trees [] = $this->getTreeBaseInfo ( $v );
			$this->getTreeInfo ( $v );
		
		}
		//if($_SESSION['user_jb']<'10'){
		
		
		//

		return $trees;
	}
	public static function get_childs($id) {

		if (! $id)
			return null;
		
		$childs_id = array ();
		$childs = M ( "user" )->field ( "UE_account" )->where ( array (
				'UE_accName' => $id 
		) )->select ();
		
		foreach ( $childs as $v ) {
			$childs_id [] = $v ['ue_account'];
		}
		
		if ($childs_id)
			return $childs_id;
		return 0;
	}
	public function getTree() {
		// if (!$this->uid) {
		//echo json_encode(array("status" => 1));
		
		// return ;
		// }
		if(I('post.user1')<>'0'){
			$getuser = I('post.user1');
		}else{
			$getuser = 'admin@qq.com';
		}
		//echo json_encode ( array ("status" => 1,"data" => $getuser ) );die;
		$base = $this->getTreeBaseInfo ( $getuser );
		$znote = $this->getTreeInfo ($getuser);
		$znote [] = $base;
		// dump($znote);die;
		/*
		 * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );
		 */
		
		echo json_encode ( array ("status" => 0,"data" => $znote ) );
	}
	
	public function getTreeso() {
		
		if(I('post.user')<>''){
		
		if(! preg_match ( '/^[a-zA-Z0-9@.]{1,120}$/', I('post.user') )){
			
			echo json_encode ( array ("status" => 1,"data" => '用戶名格式不對!' ) );
			
		}else{
		
		if(!M('user')->where(array('UE_account'=>I('post.user')))->find()){
			echo json_encode ( array ("status" => 1,"data" => '用戶不存在!' ) );
		}else{
			 
			
						$base = $this->getTreeBaseInfo ( I('post.user') );
		$znote = $this->getTreeInfo ( I('post.user') );
		$znote [] = $base;
		echo json_encode ( array ("status" => 0,"data" => $znote ) );
			
		
		}
		}
		}else{
			
			//echo json_encode ( array ("status" => 0,'nr'=>I('post.user')) );die;
			// if (!$this->uid) {
			// echo json_encode(array("status" => 1));
			// return ;
			// }
			//die;
			$base = $this->getTreeBaseInfo ('admin@qq.com');
			$znote = $this->getTreeInfo ('admin@qq.com');
			$znote [] = $base;
			// dump($znote);die;
			/*
			 * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );
			*/
			
			echo json_encode ( array ("status" => 0,"data" => $znote ) );
			
		}
	}
	
}