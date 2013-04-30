<?php

require_once '../myTestCase.php';

/** 
 * MessageTest  留言部分测试
 * @author Chenxiaojing
 * @date   2013-04-09 
 */
class MessageTest extends myTestCase {
        
    private static $messid; 
    private static $replyid; 

    //测试初始化 
    public function testInit() {
        login(); 
    }

    /** 
     * testAddMessage 测试添加留言
     *
     */
    public  function testAddMessage() {

        $message = $this->getData('message');
        $data = array(
               'url' =>  '/Message/addMessage/',
               'type' => 'POST',
               'data' => $message,
           ); 
        
        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

        // 保存测试数据ID
        self::$messid = $res->data;

    } //End Of Func testAddMessage
 

    /** 
     * testAddReply 测试添加留言回复
     *
     */
    public  function testAddReply() {

        $reply = $this->getData('reply');
        $reply['messid'] = self::$messid;
        $data = array(
               'url' =>  '/Message/addReply/',
               'type' => 'POST',
               'data' => $reply,
           ); 
        
        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

        // 保存测试数据ID
        self::$replyid = $res->data;

    } //End Of Func testAddReply
 

    /** 
     * testMessageList 测试获取留言列表 
     *
     */
    public  function testMessageList() {
         
        $data = array(
               'url' =>  '/Message/messageList/',
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testMessageList


    /** 
     * testReplyList 测试获取留言回复列表 
     *
     */
    public  function testReplyList() {
         
        $data = array(
               'url' =>  '/Message/replyList/messid/'.self::$messid,
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testReplyList


    /** 
     * testDelReply 测试删除留言回复
     *
     */
    public  function testDelReply() {
 
        $data = array(
               'url' =>  '/Message/delReply/',
               'type' => 'POST',
               'data' => array('replyid' => self::$replyid),
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testDelReply


    /** 
     * testDelMessage 测试删除留言
     *
     */
    public  function testDelMessage() {
 
        $data = array(
               'url' =>  '/Message/delMessage/',
               'type' => 'POST',
               'data' => array('messid' => self::$messid),
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testDelMessage



} //End Of Class
