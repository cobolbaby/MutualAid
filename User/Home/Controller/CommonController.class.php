<?php



namespace Home\Controller;



use Think\Controller;



class CommonController extends Controller {



	

	

	

	public function _initialize() {

		header("Content-Type:text/html; charset=utf-8");

// 		echo cookie('uid2');

// 	if( $_COOKIE ['uid2'] ==''){

// 		session_unset();

// 		session_destroy();

// 		$this->redirect('Login/index');

// 	}

		$zt=M('system')->where(array('SYS_ID'=>1))->find();

// 		$time2 = date('H');

		if($zt['zt']<>0){

			$this->error('系统升级中,请稍后访问!','/Home/Login/index');die;

		}

		

		

		//推广链接
		$tgurl = "http://" . $_SERVER["HTTP_HOST"] . u("Reg/index", array("uname" => $_SESSION['uname']));
		$this->tgurl = $tgurl;

		

        $czmcsy = CONTROLLER_NAME . ACTION_NAME;

		$czmc = ACTION_NAME;

		//echo $czmcsy;die;

		if($czmcsy<>'Indexindex'){

			

		if (! isset ( $_SESSION ['uid'] )) {

			// $this->error('請先登錄!',U('Login/index'));

			

			$this->redirect ( 'Login/index' );

		}

		$this->checkAdminSession();

		}

		$_SESSION['user_jb'] = 1;


		//===2015/12/1 QQ74 2224183 add
		accountaddlevel($_SESSION['uname']);
		//===end

		$userData = M ( 'user' )->where ( array (

		'UE_ID' => $_SESSION ['uid']

		) )->find ();

		 // 实例化User对象
		$this->news_count = M('Info')->where ( array (
				'IF_type' => 'news' 
		) )->count (); 

		$this->tgbz_open = C('tgbz_open');
        $this->jsbz_open = C('jsbz_open');


		$this->assign ( 'mm001', C("mm001") );
		$this->assign ( 'mm002', C("mm002") );
		$this->assign ( 'mm003', C("mm003") );
		$this->assign ( 'mm004', C("mm004") );
		$this->assign ( 'mm005', C("mm005") );

		$this->assign ( 'jj01s', C("jj01s") );
		$this->assign ( 'jj01m', C("jj01m") );
		$this->assign ( 'jj01', C("jj01") );
		$this->assign ( 'jjdktime', C("jjdktime") );

		//提现设置
		$this->assign ( 'txthemin', C("txthemin") );
		$this->assign('txthemax',C('txthemax'));
		$this->assign ( 'txthebeishu', C("txthebeishu") );


		$this->assign ( 'jl_start', C("jl_start") );
		$this->assign('jl_e',C('jl_e'));
		$this->assign ( 'jl_beishu', C("jl_beishu") );


		$this->assign ( 'tj_start', C("tj_start") );
		$this->assign('tj_e',C('tj_e'));
		$this->assign ( 'tj_beishu', C("tj_beishu") );


		$paidan_jbs = C('paidan_jbs');
        if($paidan_jbs>0){
        	$uname = $_SESSION ['uname'];
			$starttime = date('Y-m-d 00:00:01', time());
            $endtime = date('Y-m-d 23:59:59', time());
            $count = M("tgbz")->where("date>='$starttime' and date<='$endtime' and user='$uname'")->sum('jb');		     
            if ($count >= $paidan_jbs) {
               	$this->tgbz_full = true;
            }
    	}


		//排单币
		$paidan_db = M('paidan');
		$this->paidan_num  = $paidan_db->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count()+0;
		$this->paidans = $paidan_db->where(array('user'=>$_SESSION['uname'],'zt'=>0))->find();

		//激活码
		$pin_db = M('pin');
		$this->pin_zs = $pin_db->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count()+0;


		//会员任务  by扣扣  74 2 24183

		$level_array = explode(',', C('jjaccountlevel'));
		$task_arr = explode(',', C('per_task'));
		if(!empty($level_array) && !empty($userData['levelname'])){
			foreach ($level_array as $key => $value) {
				if($userData['levelname'] == $value){
					$this->renwu_num = $task_arr[$key];
				}
			}
		}

		$starttime = date('Y-m-1 00:00:01', time());
        $endtime = date('Y-m-31 23:59:59', time());
        $this->tasked = M("task")->where(array('MA_time'=>array('egt',$starttime),'MA_time'=>array('elt',$endtime),'MA_userName'=>$_SESSION['uname']))->count();
		


		

		$this->userData=$userData;

	}

	function   _empty(){

                    header( " HTTP/1.0  404  Not Found" );

                    $this->display( ' Public:404 ' );

     }

	

	public function checkAdminSession() {

		//设置超时为10分

		$nowtime = time();

		$s_time = $_SESSION['logintime'];

		if (($nowtime - $s_time) > 3600000) {

		session_unset();

    	session_destroy();

			$this->error('当前用户登录超时，请重新登录', U('/Home/Login/index'));

		} else {

			$_SESSION['logintime'] = $nowtime;

		}

	}

	

	function check_verify($code) {

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

	

	public function getTreeCount() {

		// if (!$this->uid) {

		// echo json_encode(array("status" => 1));

		// return ;

		// }

		$base = $this->getTreeBaseInfo ( $_SESSION ['uname'] );

		$znote = $this->getTreeInfo ( $_SESSION ['uname'] );

		$znote [] = $base;

		// dump($znote);die;

		/*

		 * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );

		 */



		return count($znote);

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

		// echo json_encode(array("status" => 1));

		// return ;

		// }

		$base = $this->getTreeBaseInfo ( $_SESSION ['uname'] );

		$znote = $this->getTreeInfo ( $_SESSION ['uname'] );

		$znote [] = $base;



		// dump($znote);die;

		/*

		 * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );

		 */

		

		echo json_encode ( array ("status" => 0,"data" => $znote ,'count'=>$this->userData['tj_num']) );

	}

	

	public function getTreeso() {

		

		if(I('post.user')<>''){

		

		if(! preg_match ( '/^[a-zA-Z0-9]{6,12}$/', I('post.user') )){

			

			echo json_encode ( array ("status" => 1,"data" => '用戶名格式不對!' ) );

			

		}else{

		

		if(!M('user')->where(array('UE_account'=>I('post.user')))->find()){

			echo json_encode ( array ("status" => 1,"data" => '用戶不存在!' ) );

		}elseif(I('post.user')==$_SESSION ['uname']){

			echo json_encode ( array ("status" => 1,"data" => '用戶名不能填自己!' ) );

		}else{

			 $account = M('user')->where(array('UE_account'=>I('post.user')))->find();

			 $accname = $account['ue_accname'];

			for ($i=1;$i<=30;$i++){

				if($accname== $_SESSION ['uname']){$quanxian = 1;$daishu=$i;break;}

				if($accname== ''){$quanxian = 0;break;}

				$account = M('user')->where(array('UE_account'=>$accname))->find();

				$accname = $account['ue_accname'];

			}

			if($quanxian == 1){

				//echo json_encode ( array ("status" => 2 );

						$base = $this->getTreeBaseInfo ( I('post.user') );

		$znote = $this->getTreeInfo ( I('post.user') );

		$znote [] = $base;

		echo json_encode ( array ("status" => 0,"data" => $znote ,"ds" =>$daishu ) );

			}elseif($quanxian == 0){

				echo json_encode ( array ("status" => 1,"data" => '此會員不在您的線下!' ) );

			}

		

		}

		}

		}else{

			

			//echo json_encode ( array ("status" => 0,'nr'=>I('post.user')) );die;

			// if (!$this->uid) {

			// echo json_encode(array("status" => 1));

			// return ;

			// }

			//die;

			$base = $this->getTreeBaseInfo ( $_SESSION ['uname'] );

			$znote = $this->getTreeInfo ($_SESSION ['uname'] );

			$znote [] = $base;

			// dump($znote);die;

			/*

			 * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );

			*/

			

			echo json_encode ( array ("status" => 0,"data" => $znote ) );

			

		}

	}

	

	

	/*public function uploadFace() {

	

		//if (!$this->isPost()) {

		//	$this->error('页面不存在');

		//}

		//echo 'asdfsaf';die;

		$upload = $this->_upload('Pic');

		$this->ajaxReturn($upload);

	}

	

	

	

	

	

	Private function _upload ($path) {

		import('ORG.Net.UploadFile');	//引入ThinkPHP文件上传类

		$obj = new \Think\Upload();	//实例化上传类

		$obj->maxSize = 2000000;	//图片最大上传大小

		$obj->savePath =  $path . '/';	//图片保存路径

		$obj->saveRule = 'uniqid';	//保存文件名

		$obj->uploadReplace = true;	//覆盖同名文件

		$obj->allowExts = array('jpg','jpeg','png','gif');	//允许上传文件的后缀名

	

		$obj->autoSub = true;	//使用子目录保存文件

		$obj->subType = 'date';	//使用日期为子目录名称

		$obj->dateFormat = 'Y_m';	//使用 年_月 形式

		//$obj->upload();die;

		$info   =   $obj->upload();

		if (!$info) {

			return array('status' => 0, 'msg' => $obj->getErrorMsg());

		} else {

			foreach($info as $file){

				$pic = $file['savepath'].$file['savename'];

			}

			//$pic =  $info[0][savename];

			//echo $pic;die;

			return array(

					'status' => 1,

					'path' => $pic

			);

		}

	}*/

	

	

	

}