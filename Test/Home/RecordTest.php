<?php

require_once '../myTestCase.php';

/** 
 * RecordTest 日志部分测试
 * @author Chenxiaojing
 * @date   2013-04-09 
 */
class RecordTest extends myTestCase {
        
    private static $recid; 

    //测试初始化 
    public function testInit() {
        login(); 
    }

    /** 
     * testSaveRecord 测试记录日志
     *
     */
    public  function testSaveRecord() {

        $record = $this->getData('record');
        $data = array(
               'url' =>  '/Record/saveRecord/',
               'type' => 'POST',
               'data' => $record,
           ); 
        
        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

        // 保存测试数据ID
        self::$recid = $res->data;

    } //End Of Func testSaveRecord
    

    /** 
     * testGetRecord 测试获取日志
     *
     */
    public  function testGetRecord() {
 
        $data = array(
               'url' =>  '/Record/getRecord/',
               'type' => 'POST',
               'data' => array('recid' => self::$recid),
           ); 

        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

    } //End Of Func testGetRecord


    /** 
     * testRecordList 测试获取日志列表
     *
     */
    public  function testRecordList() {
 
        $data = array(
               'url' =>  '/Record/recordList/',
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testRecordList



} //End Of Class
