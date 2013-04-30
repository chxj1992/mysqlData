<?php

require_once '../myTestCase.php';

/** 
 * NoteTest 日记部分测试
 * @author Chenxiaojing
 * @date   2013-04-09 
 */
class NoteTest extends myTestCase {
        
    private static $noteid; 

    //测试初始化 
    public function testInit() {
        login(); 
    }

    /** 
     * testSaveNote 测试记录日记
     *
     */
    public  function testSaveNote() {

        $note = $this->getData('note');
        $data = array(
               'url' =>  '/Note/saveNote/',
               'type' => 'POST',
               'data' => $note,
           ); 
        
        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

        // 保存测试数据ID
        self::$noteid = $res->data;

    } //End Of Func testSaveNote
    

    /** 
     * testgetNote 测试获取日记
     *
     */
    public  function testGetNote() {
 
        $data = array(
               'url' =>  '/Note/getNote/',
               'type' => 'POST',
               'data' => array('noteid' => self::$noteid),
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testGetNote


} //End Of Class
