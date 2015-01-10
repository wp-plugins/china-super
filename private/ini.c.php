<?php
NameSPace com\v7v3\www\wordpress\CSTs\svn\system;
require \CST_C . 'api.c.php';
Class CST{
	
	private $sys;
	private $user;
	private $nowPage;
	
	function __construct(){
		isset($_GET['n']) ? $this->nowPage = (int)$_GET['n'] : $this->nowPage = 1;
		add_action( 'wp_ajax_cst', array($this, 'cst_ajax'));
		add_action( 'wp_ajax_cst_install', array($this, 'cst_install'));
		add_action( 'wp_ajax_cst_uninstall', array($this, 'cst_uninstall'));
		if( isset($_GET['page']) && $_GET['page'] == 'cst_option' || 'cst_system' || 'cst_user' ){
			add_action( 'admin_enqueue_scripts', array($this ,'cst_script') );
			wp_localize_script( 'wct_ajaxrequest', 'cstAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}
		add_action( 'admin_menu', array($this ,'cst_menu') );
		!get_option('CST_SYS') ? $this->sys = array() : $this->sys = get_option('CST_SYS');
		!get_option('CST_USE') ? $this->user = array() : $this->user = get_option('CST_USE');
	}
	
	function cst_menu(){
		add_menu_page( '中文超级工具箱', \P_N, 'activate_plugins', 'cst_option', array($this ,'cst_page'), 'dashicons-wordpress', 6 );
		add_submenu_page( 'cst_option', 'CST系统扩展', 'CST系统扩展', 'activate_plugins', 'cst_system', array($this, 'system_page'));
		add_submenu_page( 'cst_option', 'CST自定义扩展', 'CST自定义扩展', 'activate_plugins', 'cst_user', array($this, 'user_page'));
		add_submenu_page( 'cst_option', 'CST在线扩展', 'CST在线扩展', 'activate_plugins', 'cst_install', array($this, 'install_page'));
		add_submenu_page( 'cst_option', '上传CST扩展', '上传CST扩展', 'activate_plugins', 'cst_upload', array($this, 'upload_page'));
	}

	function user_page() {
		$this->top_page();
		$user = $this->user;
		echo '<h3>自定义扩展</h3>
		<table class="wp-list-table widefat plugins dm">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name">扩展名称</th>
					<th scope="col" id="name" class="manage-column column-name">扩展作者</th>	
					<th scope="col" id="name" class="manage-column column-name">扩展文件</th>	
					<th scope="col" id="name" class="manage-column column-name">扩展功能</th>
					<th scope="col" id="name" class="manage-column column-name">卸载</th>
				</tr>
			</thead>
		';
		echo '<tbody id="the-list">';
		$this->get_val($user);
		$i = 0;
		foreach ($user as $k => $v) {
			$this->nowPage === 1 ? $un = $i : $un = ( $this->nowPage - 1 ) * 5 + $i;
			echo '<tr class="inactive">
			<td class="plugin-title">'. $v[0]['name'] .'</td>
			<td class="plugin-title">'. $v[0]['author'] .'</td>
			<td class="plugin-title">'. str_replace(\CST_U, '', $v[0]['route']) .'</td>			
			<td class="column-description">'. $v[0]['effect'] .'</td>
			<td class="column-description"><a class="cst_uninstall" data="'. $un .'" href="javascript:;">卸载扩展</a></td>
			</tr>';
			$i++;
		}
		echo '</table>';
		$this->cst_nav('cst_user',$this->nowPage,ceil(sizeof($this->user)/5),sizeof($this->user));
	}
	
	function system_page() {
		$this->top_page();
		$sys = $this->sys;
		echo '<h3>系统扩展</h3>
		<table class="wp-list-table widefat plugins dm">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name">扩展名称</th>
					<th scope="col" id="name" class="manage-column column-name">扩展作者</th>	
					<th scope="col" id="name" class="manage-column column-name">扩展文件</th>	
					<th scope="col" id="name" class="manage-column column-name">扩展功能</th>
				</tr>
			</thead>
		';
		echo '<tbody id="the-list">';
		$this->get_val($sys);
		foreach ($sys as $k => $v) {
			echo '<tr class="inactive">
			<td class="plugin-title">'. $v[0]['name'] .'</td>
			<td class="plugin-title">'. $v[0]['author'] .'</td>
			<td class="plugin-title">'. str_replace(\CST_S, '', $v[0]['route']) .'</td>	
			<td class="column-description">'. $v[0]['effect'] .'</td>
			</tr>';
		}
		echo '</table>';
		$this->cst_nav('cst_system',$this->nowPage,ceil(sizeof($this->sys)/5),sizeof($this->sys));
	}
	
	function install_page() {
		$api = New API;
		$data = $api->toArray($this->nowPage);
		$this->top_page();
		echo '<h3>在线扩展列表</h3>
		<table class="wp-list-table widefat plugins dm">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name">扩展名称</th>
					<th scope="col" id="name" class="manage-column column-name">扩展作者</th>	
					<th scope="col" id="name" class="manage-column column-name">更新日期</th>	
					<th scope="col" id="name" class="manage-column column-name">扩展功能</th>
					<th scope="col" id="name" class="manage-column column-name">安装</th>
				</tr>
			</thead>
		';
		echo '<tbody id="the-list">';
		foreach ($data['m'] as $v) {
			echo '<tr class="inactive">
			<td class="plugin-title">'. $v['Mname'] .'</td>
			<td class="plugin-title">'. $v['Mauthor'] .'</td>
			<td class="plugin-title">'. $v['Mdate'] .'</td>		
			<td class="column-description">'. $v['Meffect'] .'</td>
			<td class="column-description"><a class="cst_install" data="'.$v['link'].'" href ="javascript:;">在线安装</a></td>
			</tr>';
		}
		echo '</table>';
		$this->cst_nav('cst_install',$this->nowPage,$data['allPage'],$data['all']);
	}
	
	function cst_page(){
		$this->top_page();
		echo '<script>var wct_loading = "' . CST_URI . 'static/images/loading.gif";</script>
		<div id="wct" class="postbox">
		<h3 class="hndle"><span>工具包概况</span></h3>
		<div class="inside">
		<div class="main">
			<p>
			<a id="sms" href="javascript:;" class="button button-primary">扫描系统内置扩展</a>
			<a id="yykz" href="javascript:;" class="button button-primary">生成系统扩展引用文件</a>
			<a id="smzd" href="javascript:;" class="button button-primary">扫描自定义扩展</a>
			<a id="yyzd" href="javascript:;" class="button button-primary">生成自定义扩展引用文件</a>
			</p>
			<div class="info left">
				<p>当前已安装<span class="h xy">'.sizeof($this->sys).'</span>个系统扩展</p>
				<p>当前已安装<span class="h zdy">'.sizeof($this->user).'</span>个自定义扩展</p>
			</div>
			<div class="info2 left">
				China Site TooLs插件说明：
				
				1、China Site TooLs插件是一款高效可扩展的wordpress插件
				2、China Site TooLs插件并无特定功能，该插件仅仅是一款扩展引入框架，所有功能都可以根据用户去求来添加删除。
				3、China Site TooLs插件的高效之处在于“引用即所用”，即凡是引入的代码都是当前所需的，不引入多余的代码。
				4、China Site TooLs插件核心代码都是采用面向对象思想编写而来，代码精简高效可维护，可读性高。
				5、用户自定义扩展目录：/wp-content/CstPort/lib/
			</div>
			<div class="link">
			<span><a href="admin.php?page=cst_system">查看所有系统扩展</a></span>
			<span><a href="admin.php?page=cst_user">查看所有自定义扩展</a></span>
			</div>
		</div>
		</div>
		</div>';
		$this->unlzip();
	}
	
	function top_page(){
		echo "<h2>超级工具箱插件</h2>
		<div id='tip' class='updated inline below-h2'>
		中文wordpress网站工具集成箱插件是一款可按需求扩展功能的插件，用户可以自主安装所需的模块，减少无用模块让插件以最低的消耗达到最满意的效果！插件官网：《<a target='_blank' href='http://www.v7v3.com/?referer=cst'>维7维3</a>》
		</div>";
	}
	
	function upload_page(){
		if( isset($_POST['cst']) ){
			$ff = $this->up_install();
			if( $ff == 3 ){
				m::get_all();
				m::inc_mode();
				m::inc_mode(3);
				$a ='a';
			}
			else if( $ff == 2 ){
				m::get_all();
				m::inc_mode();
				$a = 'b';
			}
		}
		$this->top_page();
		if( isset($a) ){
			echo "<div id='tip' class='updated inline below-h2'>扩展安装成功，请点击链接查看：<a href='".admin_url( 'admin.php?page=cst_user' )."'>自定义扩展</a></div>";
		}
		print '<div class="upload-plugin">
		<p class="install-help">如果您有.zip格式的CST扩展文件，可以在这里通过上传安装它。</p>
		<form method="post" enctype="multipart/form-data" class="wp-upload-form">
		<input type="hidden" name="cst" value="cst" id="cst">
		<label class="screen-reader-text" for="pluginzip">扩展zip文件</label>
		<input type="file" id="pluginzip" name="pluginzip" />
		<input type="submit" name="install-plugin-submit" id="install-plugin-submit" class="button" value="现在安装"  />
		</form>
		</div>';
	}
	
	function cst_script(){
		wp_register_script( 'cst_jq', includes_url( '/js/jquery/jquery.js'), array('jquery'), '' );
		wp_register_script( 'cst_ajax', \CST_URI . 'static/js/cst_ajax.js', array('jquery'), '' );
		wp_enqueue_script( 'cst_jq' );
		wp_enqueue_script( 'cst_ajax' );
		wp_register_style( 'cst_css', \CST_URI . 'static/css/cst.css',  array(), '', 'all' );
		wp_enqueue_style( 'cst_css' );
	}
	
	function cst_nav($page,$now,$max,$num){
		if( $max == 1 ){
			echo '<div class="tablenav bottom"><div class="tablenav-pages"><span class="displaying-num">'. $num .'个扩展项目</span></div></div>';
			return;
		}
		$now == 1 ? $s = 1 : $s = $now - 1;
		$max == $now ? $n = $max : $n = $now + 1;
		echo '<div class="tablenav bottom"><div class="tablenav-pages"><span class="displaying-num">'. $num .'个扩展项目</span>';
		if( $max > 1 ){
				echo '<span class="pagination-links"><a class="first-page disabled" title="前往第一页" href="?page='. $page .'">«</a>
				<a class="prev-page disabled" title="前往上一页" href="?page='. $page .'&n='. $s .'">‹</a>
				<span class="paging-input">第'. $now .'页，共<span class="total-pages">'. $max .'</span>页</span>
				<a class="next-page" title="前往下一页" href="?page='. $page .'&n='. $n .'">›</a>
				<a class="last-page" title="前往最后一页" href="?page='. $page .'&n='. $max .'">»</a>
				</span>';
		}
		echo '</div><br class="clear"></div>';
		return;
	}
	
	static public function up_install(){
		if( !function_exists( 'wp_handle_upload' ) ) require_once( \ABSPATH . 'wp-admin/includes/file.php' );
		$uploadedfile = $_FILES['pluginzip'];
		$upload_overrides = array('test_form' => false, 'mimes' => array('zip' => 'application/zip'));
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		if( !$movefile ) {
			wp_die('扩展包上传失败，请重试！<a href="javascript:history.go(-1);">返回上一页</a>');
		}else{
			$park = time();
			if( !file_exists(\CST_M. $park) ){
				if( !mkdir(\CST_M. $park) ) wp_die('创建临时目录出错，请重试！<a href="javascript:history.go(-1);">返回上一页</a>');
			}
			$zip = new \ZipArchive;
			$zip->open($movefile['file']);
			if( !$zip->extractTo(\CST_M . $park) ) wp_die('解压扩展包失败，请重试！<a href="javascript:history.go(-1);">返回上一页</a>');
			$zip->close();
			if( file_exists(\CST_M . $park . '/option/') ){
				m::copy_files(\CST_M . $park . '/option/',\CST_D . 'option/');
				m::deldir(\CST_M . $park . '/option/');
				$ret = 3;
			}
			m::copy_files(\CST_M. $park,\CST_D . 'lib/');
			m::deldir(\CST_M . $park);
			if( unlink($movefile['file']) && isset($ret) ){
				return 3;
			}else return 2;
		}
	}
	
	function cst_ajax(){
		if( isset($_POST['scan']) ){
			switch ($_POST['scan']){
				case 'system':
					header('Content-type:text/json');
					m::get_all(2);
					if( $op = get_option('CST_SYS') )
						echo json_encode(array('snum'=>sizeof($op)));
					else echo json_encode(array('snum'=>0));
					break;
				case 'user':
					header('Content-type:text/json');
					m::get_all();
					if( $op = get_option('CST_USE') )
						echo json_encode(array('snum'=>sizeof($op)));
					else echo json_encode(array('snum'=>0));
					break;
			}
		}else if( isset($_POST['inc']) ){
			switch ($_POST['inc']){
				case 'system':
					header('Content-type:text/json');
					if( m::inc_mode(2) )
						echo json_encode(array('inc'=>200));
					else echo json_encode(array('inc'=>500));
					break;
				case 'user':
					header('Content-type:text/json');
					if( m::inc_mode() )
						echo json_encode(array('inc'=>200));
					else echo json_encode(array('inc'=>500));
					break;
			}
		}
		die();
	}
	
	function unlzip(){
		$arr = glob( \CST_M . '*' );
		if( $arr ){
			foreach($arr as $v){@unlink($v);};
		}
		return;
	}
	
	function get_val(&$val){
		$shift = ($this->nowPage - 1) * 5;
		$val=array_slice($val,$shift,$shift+5);
		return;
	}
	
	function cst_uninstall(){
		header('Content-type:text/json');
		$user = $this->user;
		if( isset($_POST['todo']) ){
			if( is_array( $user[$_POST['todo']][0] ) ){			
				if( isset($user[$_POST['todo']][0]['inc']) & isset($user[$_POST['todo']][0]['option']) ){
					$str = file_get_contents(\CST_O . $user[$_POST['todo']][0]['option']);
					$Mzz = "/^(?:option inc:)(?: )*(.+)/mi";
					preg_match($Mzz,$str,$arr);
					if( isset($arr[1]) ){
						m::deldir(\CST_O . $arr[1]);
						@unlink(\CST_O . $user[$_POST['todo']][0]['option']);
					}
					m::deldir(\CST_U . $user[$_POST['todo']][0]['inc']);
					@unlink($user[$_POST['todo']][0]['route']);
					die(json_encode(array('install'=>200)));
				}
				if( isset($user[$_POST['todo']][0]['inc']) ){
					m::deldir(\CST_U . $user[$_POST['todo']][0]['inc']);
					@unlink($user[$_POST['todo']][0]['route']);
					die(json_encode(array('install'=>200)));
				}
				if( isset($user[$_POST['todo']][0]['option']) ){
					$str = file_get_contents(\CST_O . $user[$_POST['todo']][0]['option']);
					$Mzz = "/^(?:option inc:)(?: )*(.+)/mi";
					preg_match($Mzz,$str,$arr);
					if( isset($arr[1]) ){
						m::deldir(\CST_O . $arr[1]);
						@unlink(\CST_O . $user[$_POST['todo']][0]['option']);
					}
					@unlink($user[$_POST['todo']][0]['route']);
					die(json_encode(array('install'=>200)));
				}
				@unlink($user[$_POST['todo']][0]['route']);
				die(json_encode(array('install'=>200)));
			}
		}
		die();
	}
	
	function cst_install(){
		header('Content-type:text/json');
		$api = New API;
		if( isset($_POST['todo']) ){
			 $str = json_decode($api->get_api('http://cst.api.v7v3.com/cst_install.php?mode=' . $_POST['todo']),true);
			 if( !isset($str['o']) ){
				 if( $dis = $api->downMode($str['u']) ){
					 $api->installMode($dis);
					 m::get_all();
					 m::inc_mode();
					 echo json_encode(array('install'=>200));
				 }
			 }else{
				 if( $downU = $api->downMode($str['u']) ){
					 $api->installMode($downU);
					 m::get_all();
					 m::inc_mode();
					 $a = 1;
				 }
				 if( $downO = $api->downMode($str['o']) ){
					 $api->installMode($downO,2);
					 m::inc_mode(3);
					 $b = 1;
				 }
				 if( $a && $b ){
					echo json_encode(array('install'=>200));
				 }
			 }
		}
		die();
	}

}