<?php
NameSPace com\v7v3\www\wordpress\CSTs\svn\system;
Class API{
	
	const apiHOST = 'http://cst.api.v7v3.com/cst.php';
	const apiAGENT = 'v7v3 CST API Bot1.0';
	
	public function get_api($url){
			if( function_exists('curl_init') )
				return $this->cst_curl($url);
			else return $this->file_get_contents($url);
	}
	
	private function cst_curl($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, self::apiAGENT);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
	private function file_get_contents($url){
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>60,
			'header'=>'User-Agent: '.self::apiAGENT
			)
		);
		$http = stream_context_create($opts);
		return \file_get_contents($url, false, $http);
	}
	
	public function toArray($page){
		$data = $this->get_api(self::apiHOST . '?p=' .$page);
		return json_decode($data,1);
	}
	
	public function downMode($url){
		$f = \CST_M . \md5( time() . $url ) . '.zip';
		$fp = fopen($f,'ab');
		if( fwrite($fp,$this->get_api($url)) !== false ){
			return $f;
		}else return false;
	}
	
	public function installMode($z,$case=1){
		$case === 1 ? $d = \CST_U : $d = \CST_O;
		$zip = new \ZipArchive;
		$zip->open($z);
		if ( $zip->extractTo($d) )
			$zip->close();
			return true;
	}
	
}