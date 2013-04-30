<?php

// 兼容不同版本的phpunit和phpunit2
if ( !class_exists('PHPUnit_Framework_TestSuite') ) {
    class_alias('PHPUnit2_Framework_TestSuite','PHPUnit_Framework_TestSuite');
}

/** 
 * myTestCase 我的测试类扩展
 * @author Chenxiaojing
 * @date   2013-01-23 
 */
class myTestCase extends PHPUnit_Framework_TestCase{
    
    //配置
    public $conf;
    //测试数据 
    public $test_data;

    /** 
     * __construct 构造方法 
     * @access public 
     * @return void
     */
    public  function __construct() {

        $this->conf = require 'conf.php';
        $this->test_data = require 'test_data.php';

        //加载项目共用函数
        require_once 'common.php';

    } //End Of Func __construct
    
    
    
    /** 
     * getData 获取测试数据
     * @param string $key
     * @access public 
     * @return array
     */
    public  function getData( $key ) {
        
        return $this->test_data[$key];
    
    } //End Of Func getData


    /** 
     * request 模拟发送外部请求
     * @param array $data
     * @access public 
     * @return void
     */
    public  function request( $data,$jsonType = true ) {

        $curl = curl_init();   
        curl_setopt($curl,CURLOPT_URL,$this->conf['host'].$data['url']);  
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);

        //post 数据
        if ( isset($data['type']) AND $data['type'] == 'POST' AND $data['data'] ) {
            $post_data = http_build_query($data['data']);
            curl_setopt($curl, CURLOPT_POST, 1);                                                                                   
            curl_setopt($curl, CURLOPT_POSTFIELDS,$post_data);
        }
        
        //打开Cookie
        //curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE); //每次请求都重置Session 
        curl_setopt($curl, CURLOPT_COOKIEFILE, "temp/cookiefile"); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, "temp/cookiefile"); 

        $res = curl_exec($curl);
        curl_close($curl);
        
        if( $jsonType ) {
            $res = json_decode($res); 
        }
        return $res;

    } //End Of Func request   


} //End Of Class



