<?php
/**
 * 此为项目的常用函数库，供开发使用,只用于处理与逻辑无关系的数据
 * @author eppstudio
 * @package Home
 */

/**
* hash字符串
*/
function pwdHash($pwd,$salt,$type='sha512'){
	return hash($type,$salt.$pwd.$salt);
}


/**
 * 后台加密算法
* hash字符串
*/
function pwdStrongHash($pwd,$salt){
	$res =  crypt($pwd,'$6$rounds=5000$'.$salt.'$');
	$arr = explode('$', $res) ;
	return $arr[4];
}

/**
* 判断某个字符串是不是email
*/
function isEmail($str){
	return preg_match('{^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$}',$str);
}

/**
* 得到时间，可以没有年
*/
function getDateWithoutYear($time){
	$thisYear = date('Y',time());
	if (date('Y',$time)===$thisYear){
		return date('n月j日',$time);
	}else{
		return date('Y年n月j日',$time);
	}
}

/**
* 得到时间，mysql日期插入格式
*/
function getDateFormySql($time=''){
    if (empty($time)){
        $time = time();
    }
    return date('Y-m-d H:i:s',$time);
}
/**
* 返回所能SQL表达的最大时间
*/
function maxDateSQL(){
    return '9999-12-31 23:59:59';
}

/**
* 生成附件文件名用
*/
function randFileName(){
	return date('Ymd-His').'-'.rand();
}

/**
* 随机字节(加密用途)注意返回的东西是任意字节
*/
function randByte($len=32){
	if (function_exists('openssl_random_pseudo_bytes')){
	    //先找openssl
	    $r = openssl_random_pseudo_bytes($len,$tmp);
	    if ($r!==FALSE){
	        return $r; 
	    }
	}
	if (file_exists('/dev/urandom')){
	    //再去找系统
	    $r =  fread(fopen('/dev/urandom', 'r'),$len);
	    if (!empty($r)){
	        return $r;
	    }    
	}
    //没办法只能用rand
    $output='';
    for ($i=0;$i<$len;$i++){
        $output .= chr(rand()%255);
    }
    return $output;

}

/**
* 去掉HTML部分并用于描述
*/
function htmlForDescription($data){
    $data = trim(strip_tags(html_entity_decode($data,ENT_COMPAT | ENT_HTML401,'UTF-8')));
    $data = preg_replace('![\s\n\t\pZ\pC]+!u', ' ', $data);
	return $data;
}

/**
* 切掉一部分HTML部分并用于显示
*/
function cutForShow($data,$len){
	return msubstr(htmlspecialchars($data),0,$len);
}

/**
* 创建表单令牌 XXX:暂不用本功能
* 注意此方法会重置表单令牌
*/
function getFormToken(){
    // 开启表单验证自动生成表单令牌
    $tokenName   = C('TOKEN_NAME');
    $tokenType = C('TOKEN_TYPE');
    $tokenValue = $tokenType(microtime(TRUE));
    $_SESSION[$tokenName]  =  $tokenValue;
    return $tokenValue;
}

/**
* 调试用函数
* 当前堆栈信息
* $skip 跳过的堆栈数量
*/
function traceInfo($skip=0){
    $backTrace = debug_backtrace(false);
	$tracestr=count($backTrace)."\n";
	$i=0;
	foreach($backTrace as $trace){
	    if ($i<$skip){
	        $i++;continue;
	    }
		$tracestr.='['.$i.'] => '.(isset($trace['file'])?$trace['file']:'')
			.':'
			.(isset($trace['line'])?$trace['line']:'')
			."\n";
		$i++;
	}
	return $tracestr;
}
/**
* 调试用函数
* @param mix $val 需要查看的数据
* 
*/
function V($val,$show=FALSE){
	if (!APP_DEBUG){
		return ;
	}
	ob_start();
	var_dump($val); //必须的
	$dump_str=ob_get_clean();
	/*$arg_array=func_get_args();
	$var_str = '';
	foreach($arg_array as $key => $val){
		ob_start();
		var_dump($val); //必须的
		$dump_str=ob_get_clean();
		$var_str.=$key.' => '.$dump_str."\n";
	}*/
	//D('RecordDebug')->record($arg_array,debug_backtrace(TRUE));
	trace('D调试堆栈', traceInfo(1));
	trace('D调试信息', $dump_str);
	if ($show){
	    echo '<pre>';
	    echo traceInfo(1).'<hr/>';
	    echo $dump_str;
	    echo '</pre>';
	}
	Log::record($dump_str,Log::DEBUG);
}

$GLOBALS['T_IS_EXIT'] = TRUE;
/**
* 抛出错误函数。
* T_IS_EXIT TRUE表示退出程序
* 				FALSE表示不退出
* 产生页面错误直接退出,Thinkphp的Action->error自动退出了。。
* @param $str 错误消息
* @param $exit 是否强制退出脚本 1一定会退出脚本，0按照配置进行
*/
function T($str,$exit=0){
    //如果在编译静态，不允许推出
    if($GLOBALS['static'] == 1)
        return ;
    global $T_IS_EXIT;
    $extstr = '错误信息 : '.$str."\n";
    $backTrace = debug_backtrace(false);
    $extstr .= '错误堆栈 ： '.count($backTrace)."\n";
    $i=0;
    foreach($backTrace as $trace){
    	$extstr.='['.$i.'] => '.(isset($trace['file'])?$trace['file']:'')
    		.':'
    		.(isset($trace['line'])?$trace['line']:'')
    		."\n";
    	$i++;
    }
    if ($T_IS_EXIT||$exit){
        Log::record($extstr,Log::ERR);
        if (!APP_DEBUG){
        	Log::save();
        }
		//A('Common')->showError($str);
        $data['data'] = 0; 
        $data['info'] = $str; 
        $data['status'] = 0; 
        echo json_encode($data);

		quit();
	}else{
	    trace('错误',$extstr);
		return FALSE;
	}
}
/**
* T函数行为控制
* @param $is T函数是否退出程序，1退出，0不退出
*/
function T_exit($is=1){
    global $T_IS_EXIT;
    if (empty($is)){
        $T_IS_EXIT = FALSE;
    }else{
        $T_IS_EXIT = TRUE;
    }
}
/**
* 包含辅助代码
* 同一个文件同一次运行仅引用一次
* @param $place string 相对路径
*/
function loadHelper($place){
    //同一个文件同一次运行仅引用一次
    static $_Helper=array();
    if (isset($_Helper[$place])){
        return ;
    }
    $_Helper[$place]=1;
    require_once APP_PATH.'Lib/Helper/'.$place;
}
/**
* 限制数组只包含某些key，去掉其他所有key
* 备注：不关心原数组是否缺少某些key
* @param data array 原始数组 
* @param key array 需要的key数组 ''表示不处理
* @return array 返回数组
*/
function array_only_have($data,$key=''){
	if (empty($key)){
		return $data;
	}else{
	return array_intersect_key($data,
		   array_fill_keys($key, ''));
	}	   
}

/**
* 发送post请求封装函数
* @param $url:地址
* @param $postdata:二维数组array
* @param $file:二维数组array
*/
function do_post_request($url, $postdata, $files = null) 
{ 
    $data = ""; 
    $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10); 
       
    //Collect Postdata 
    foreach($postdata as $key => $val) 
    { 
        $data .= "--$boundary\n"; 
        $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n"; 
    } 
     
    $data .= "--$boundary\n"; 
    
    //Collect Filedata 
    foreach($files as $key => $file) 
    { 
        $fileContents = file_get_contents($file['tmp_name']); 
        
        $data .= "Content-Disposition: form-data; name=\"{$key}\"; filename=\"{$file['name']}\"\n"; 
        $data .= "Content-Type: image/jpeg\n"; 
        $data .= "Content-Transfer-Encoding: binary\n\n"; 
        $data .= $fileContents."\n"; 
        $data .= "--$boundary--\n"; 
    } 
  
    $params = array('http' => array( 
           'method' => 'POST', 
           'header' => 'Content-Type: multipart/form-data; boundary='.$boundary, 
           'content' => $data 
        )); 

   $ctx = stream_context_create($params); 
   $fp = fopen($url, 'rb', false, $ctx); 
   
   if (!$fp) { 
      return T("_Problem with $url, $php_errormsg"); 
   } 
  
   $response = @stream_get_contents($fp); 
   if ($response === false) { 
      return T("_Problem reading data from $url, $php_errormsg"); 
   } 
   return $response; 
} 

/**
* 退出脚本封装函数（避免FASTCGIexit错误）
*/
function quit($str=''){
    if (class_exists('Session')){
        Session::stop();
    }
    if(C('LOG_RECORD')) Log::save();
	exit($str);		//必须的
}

/**
* 文件网络类型转换为扩展名
*/
function type_to_extension($type){
	static $contentType = array(
		'image/bmp'=>'bmp',
		'image/gif'=>'gif',
		'image/jpeg'=>'jpg',
		'image/png'=>'png',
		'image/vnd.microsoft.icon'=>'icon',
		'image/tiff'=>'tif',
	);
	if (isset($contentType[$type])){
		return $contentType[$type];
	}
	return FALSE;
}
/**
* 安全过滤函数
* @param $string
* @return string
*/
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	return $string;
}
/**
* 数组中字符串html转义函数
* @param mix 
* @return mix
*/
function htmlspecialchars_array($mix,$dep = 10){
    if ($dep<=0) return $mix;
    if (is_string($mix)) return htmlspecialchars($mix);
    if (is_array($mix)){
        foreach($mix as &$v1){
            $v1 = htmlspecialchars_array($v1,$dep-1);
        }
        unset($v1);
    }
    return $mix;
}
/**
* 获取当前页面完整URL地址
*/
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
/**
* 获取当前页面的网站地址
* 最后不含'/'哦
* http://www.example.com
*/
function get_site_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
}
/**
* urlbase64编码
*/
function base64url_encode($data) { 
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

/**
* urlbase64解码
*/
function base64url_decode($data) { 
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
} 

/**
* 过滤数据
*/
/**
* 过滤数据函数版
* filterData
* 定义在Helper/Filter/FilterData.php
* 注意没有状态项，因为只要传入函数一定会进行验证。
* 除非自行绕开，否则全部过滤掉html代码

* @param $data 数据
* @param array $have_key 只能包含列表中的key,MAP功能
* @param array $validData thinkphp的自动验证数组
* @param array $operData thinkphp的自动完成数组

*/
function _filterData($data,
         $have_key='',
     	 $validData='',
     	 $operData='') {}
/**
* 某项在数组中存在，并且是数字,成功后返回结果
* is_set_num
* 定义在Helper/Filter/FilterData.php'
* @param array $arr
* @param string $index 
*/
function _is_set_num($arr,$index){}

/**
* 是否登录
*/
function isLogin(){
	if (Session::is_set('user_id')){
		return TRUE;	
	}else{
		return FAlSE;
	}
}
/**
* 是否AJAX
*/
function isAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
        if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
            return true;
    }
    if(!empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')]))
        // 判断Ajax方式提交
        return true;
    return false;
}

/**
* 得到当前登录用户id
*/
function getCurrentUserid(){
	//return 1;
    static $cache = 0;
    if ($cache>0){
        return $cache;
    }
    if (!class_exists('Session')){
        return 0;
    }
    $cache = Session::get('user_id');
    
	return $cache;
}
/**
* 得到当前登录用户key
*/
function getUserKey(){
	//return 1;
    static $salt_8 = '';
    if ($salt_8 != ''){
        return $salt_8;
    }
    $salt_8 = Session::get('key');
	return $salt_8;
}

/**
* 得到当前登录管理员id
*/
function getAdminUid(){
	//return 1;
    static $cache = 0;
    if ($cache>0){
        return $cache;
    }
    if (!class_exists('Session')){
        return 0;
    }
    $cache = Session::get('admin_uid');
	return $cache;
}

/**
* 从目录中递归获取所有文件
*/
function getFilesFromDir($dir) { 

  $files = array(); 
  if ($handle = opendir($dir)) { 
    while (false !== ($file = readdir($handle))) { 
        if ($file[0] != ".") { 
            if(is_dir($dir.'/'.$file)) { 
                $dir2 = $dir.'/'.$file; 
                $files[] = getFilesFromDir($dir2); 
            } 
            else { 
              $files[] = $dir.'/'.$file; 
            } 
        } 
    } 
    closedir($handle); 
  } 
  return array_flat($files); 
} 
/**
* 扁平化数组
*/
function array_flat($array) {
    $tmp = array();
    foreach ( $array as $a ) {
        if (is_array ( $a )) {
            $tmp = array_merge ( $tmp, array_flat ( $a ) );
        } else {
            $tmp [] = $a;
        }
    }
    
    return $tmp; 
} 
/**
* 本函数被调用多少次，
* 用于初始化判断
*/

function has_call_num($key) {
    static $data = array();
    if (!isset($data[$key])){
        $data[$key] = 1;
        return 0;
    }else{
        $_num = $data[$key];
        $data[$key] ++;
        return $_num;
    }
} 

/**
 * 去除一维数组中的空元素
 */
function array_empty($array){
	foreach($array as $key => $value){
		if($value === '' || $value===NULL){
			unset($array[$key]);
		}
	}
	return $array;
}

/**
* 随机获取一个二维数组的值
* @param:array为传入的数组
* @param:field为要获取的字段
*/
function getRandOfArray($array,$field){
	foreach($array as $key => $value){
		$item[]=$value["$field"];
	}
	$count=count($item);
	$rand=rand(0, $count-1);
	return $item["$rand"];		
}

/**
* 枚举类实例生成函数
* ex:(使用样例)
* 	enum('gender')->getName(1);
* @param str $name string  枚举的名字
* @return class 枚举类实例 
*/
function enum($name){
    return EnumFactory::getInstance($name);
}

/**
 *  获取调试用信息
 *  @return array
*/
function getAllTrace(){
    loadHelper('/Debug/TraceInfo.php');
    return TraceInfo::getAll();
}

/**
 *  返回一维关联数组
 *  @param array $data 原数组(2维)
 *  @param str $key 选为key的项
 *  @param str $value value的项
 *  @return array
*/
function array_build_one_assoc(&$data,$key,$value){
    $newData = array();
    foreach($data as $v1){
        $newData[$v1[$key]]=$v1[$value];
    }
    unset($v1);
    return $newData;
}
    
    
/**
	* 转换时间
    * 输入：最小年龄，最大年龄
    * 输出：array('min'=> 最小年龄的出生日期,'max'=> 最大年龄的出生日期)
	*/
    function makeAgeFormat($value_min,$value_max){
        $ret['min']=date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")-$value_min)); 
        $ret['max']=date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")-$value_max)); 
        return $ret;
    }


/**
 * 获取时间和当前时间的时间差
 */
function getTimeDiff($time){
	import("ORG.Util.Date");
    $Date=new Date();
    $result=$Date->timeDiff($time);
    return $result;
}
/**
 * 将长字符串拆短，用于显示
 * 按字符宽度截断。（汉字宽度2，英文宽度1）。。
 * @param string $str
 * @param string $cutlen
 * @param string $end
 * @param string $encoding
 * @return array
 */
function mb_chunk_split_array($str,$cutlen=76,$encoding='utf-8'){
	if (!is_string($str)) return false;
	$lines = str_lines($str);
	$out_lines = array();
	foreach ($lines as $v1){
		$tlen = mb_strwidth($v1,$encoding);
		if ($tlen<=$cutlen) {
			$out_lines[] = $v1;
			continue;
		}
		$p=0;
		$outstr = '';
		while($p<$tlen){
			if ($p+$cutlen>=$tlen){
				$this_cutlen = $tlen-$p;
			}else{
				$this_cutlen = $cutlen;
			}
			$pstr = mb_strimwidth($v1,$p,$this_cutlen,'',$encoding);
			$out_lines[] = $pstr;
			$p += $cutlen; 
		}
	}
	return $out_lines;
}
/**
 * return a array of lines from the input str
 * @param string $str
 * @param bool $trim
 * @param bool $rmEmpty
 */
function str_lines($str,$trim = true,$rmEmpty=true){
	if (!is_string($str)) return false;
	$outArray = explode("\n",$str);
	if ($trim){
		foreach($outArray as &$v1){
			$v1 = trim($v1);
		}
		unset($v1);
	}
	if ($rmEmpty){
		foreach($outArray as $k1=>&$v1){
			if (empty($v1)) unset($outArray[$k1]); 
		}
		unset($v1);
	}
		//var_dump($outArray);
	return $outArray;
}
/**
 * Object to array..对象转换为数组，
 * 作为get_object_vars的一个代理，可以仅导出public变量
 */
function obj_to_array($obj){
	return get_object_vars($obj);	
}

/**
*没有权限错误
*
*/
function showerror(){
	A('Common')->error('没有权限');
	}
	
/**
 * 显示404页面
 */
function show404(){
	A('Common')->show404();
	exit();	
}

/**
 * 调试模式下的404页面
 */
/*
function __hack_action(){
    A('Common')->debugshow404();
    exit();
    }
    */
