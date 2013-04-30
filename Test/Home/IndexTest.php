<?php

require_once '../myTestCase.php';

/** 
 * IndexTest 基本页部分测试
 * @author Chenxiaojing
 * @date   2013-04-09 
 */
class IndexTest extends myTestCase {
        
    //测试初始化 
    public function testInit() {
        login(); 
    }

    /** 
     * testIndex 测试状态列表
     *
     */
    public  function testIndex() {

        $data = array(
               'url' =>  '/Index/index/',
           ); 
        
        $res = $this->request($data);
        $this->assertTrue($res->status == 1);       

    } //End Of Func testIndex
    

    /** 
     * testDiary 测试用户全部记录
     *
     */
    public  function testDiary() {

        $data = array(
               'url' =>  '/Index/diary/',
           ); 
        
        $res = $this->request($data);
        $this->assertTrue($res->status == 1);       

    } //End Of Func testDiary
    


} //End Of Class
