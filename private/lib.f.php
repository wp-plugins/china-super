<?php
NameSPace com\v7v3\www\wordpress\CSTs\svn\system;
Class m{

	static public function scan($case=1){
		$port = self::switchMode($case);
		return glob( $port['d'] . '*.php' );
	}

	static public function get_module_info($file){
		if( !file_exists($file) ){
			return false;
		}
		$str = file_get_contents($file,0,null,6,1000);
		$Mzz[] = "/^(?:module name:)(?: )*(.+)/mi";
		$Mzz[] = "/^(?:module effect:)(?: )*(.+)/mi";
		$Mzz[] = "/^(?:module author:)(?: )*(.+)/mi";
		$Mzz[] = "/^(?:module inc:)(?: )*(.+)/mi";
		$Mzz[] = "/^(?:module option:)(?: )*(.+)/mi";
		preg_match($Mzz[0],$str,$arr[]);
		preg_match($Mzz[1],$str,$arr[]);
		preg_match($Mzz[2],$str,$arr[]);
		preg_match($Mzz[3],$str,$arr[]);
		if( !$arr[0][1] ){
			return false;
		}
		if( $arr[0][1] && $arr[1][1] && $arr[2][1] && $arr[3][1] && $arr[4][1] ){
			return array(
				'name' => $arr[0][1],
				'effect' => $arr[1][1],
				'author' => $arr[2][1],
				'inc' => $arr[3][1],
				'option' => $arr[4][1],
				'route' => $file
			);
		}
		if( $arr[0][1] && $arr[1][1] && $arr[2][1] && $arr[4][1] ){
			return array(
				'name' => $arr[0][1],
				'effect' => $arr[1][1],
				'author' => $arr[2][1],
				'option' => $arr[4][1],
				'route' => $file
			);
		}
		if( $arr[0][1] && $arr[1][1] && $arr[2][1] && $arr[3][1] ){
			return array(
				'name' => $arr[0][1],
				'effect' => $arr[1][1],
				'author' => $arr[2][1],
				'inc' => $arr[3][1],
				'route' => $file
			);
		}
		if( $arr[0][1] && $arr[1][1] && $arr[2][1] ){
			return array(
				'name' => $arr[0][1],
				'effect' => $arr[1][1],
				'author' => $arr[2][1],
				'route' => $file
			);
		}
		return;
	}

	static public function get_a($arr,$case=1){
		$a = array();
		if( $case === 1 || $case === 2 ){
			foreach($arr as $v){
				$a[]=$v[0]['route'];
			};
		}else{
			foreach($arr as $v){
				$a[]= $v;
			};
		}
		return $a;
	}

	static public function we($f,$arr,$case=1){
		$port = self::switchMode($case);
		if( !$arr ){
			return null;
		}
		$code = array();
		foreach( $arr as $v ){
			$a = self::get_str($v,$case);
			$code[] = $a;
		};
		$str = '<?php' . implode($code);
		$fp = fopen($f,'ab');
		if( fwrite($fp,$str) !== false ){
			return true;
		}else return false;
	}

	static public function strip_whitespace($content) {  
		$stripStr = '';
		$tokens =   token_get_all ($content);  
		$last_space = false;  
		for ($i = 0, $j = count ($tokens); $i < $j; $i++){  
			if (is_string ($tokens[$i])){  
				$last_space = false;  
				$stripStr .= $tokens[$i];  
			}else{  
				switch ($tokens[$i][0]){
					case T_COMMENT:  
					case T_DOC_COMMENT:  
						break; 
					case T_WHITESPACE:  
						if (!$last_space){  
							$stripStr .= ' ';  
							$last_space = true;  
						}  
						break;  
					default:  
						$last_space = false;  
						$stripStr .= $tokens[$i][1];  
				}  
			}  
		}  
		return $stripStr;  
	}
	
	static public function copy_files($src,$dst) {  
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if(( $file != '.' ) && ( $file != '..' )) {
				if( is_dir($src . '/' . $file) ) {
					self::copy_files($src . '/' . $file,$dst . '/' . $file);
					continue;
				}else{
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
		return;
	}
	
	static public function deldir($dir) {
		$dh=opendir($dir);
		while($file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			}else{
				deldir($fullpath);
			}
			}
		}
		closedir($dh);
		if(rmdir($dir)) {
			return true;
		}else{
			return false;
		}
	}
	
	static public function get_str($f,$case=1){
		$port = self::switchMode($case);
		$zz = "/cst::include\(['\"?:]([\w\/]+\.php)['\"?:]\);/";
		$str = self::strip_whitespace(file_get_contents($f));
		$str = rtrim(ltrim($str,'<?php'),'?>');
		if( preg_match($zz,$str,$arr) ){
			$str = str_replace($arr[0],'',$str);
			$str .= self::get_str($port['d'] . $arr[1]);
		}
		return $str;
	}

	static public function get_all($case=1){
		$port = self::switchMode($case);
		$arr = array();
		foreach(self::scan($case) as $v){
			if( $a=self::get_module_info($v) ){
				$arr[] = array( $a );
			}
		};
		update_option($port['op'],$arr);
	}

	static public function inc_mode($case=1){
		$port = self::switchMode($case);
		if( $case === 1 || $case === 2 ){
			if( $op = get_option($port['op']) ){
				if( file_exists(\CST_INC . $port['s'] .  md5($port['d']) . '.php') ) unlink(\CST_INC . $port['s'] . md5($port['d']) . '.php');
				if( self::we(\CST_INC . $port['s'] .  md5($port['d']) . '.php',self::get_a($op),$case) ) return true;
				else return false;
			}else return null;
		}else if( $case == 3 ){
			if( $op = self::scan(3) ){
				if( file_exists(\CST_INC . $port['s'] .  md5($port['d']) . '.php') ) unlink(\CST_INC . $port['s'] . md5($port['d']) . '.php');
				if( self::we(\CST_INC . $port['s'] .  md5($port['d']) . '.php',self::get_a($op,$case),$case) ) return true;
				else return false;
			}else return null;
		}
	}
	
	static public function switchMode($num){
		switch ($num){
			case 1:
				return array('op'=>'CST_USE','d'=>\CST_U,'s'=>'USE_');
			case 2:
				return array('op'=>'CST_SYS','d'=>\CST_S,'s'=>'SYS_');
			case 3:
				return array('op'=>'CST_OPT','d'=>\CST_O,'s'=>'OPT_');
		}
	}
	
}