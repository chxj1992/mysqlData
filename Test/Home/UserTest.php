<?php

require_once '../myTestCase.php';

/** 
 * UserTest 用户部分测试
 * @author Chenxiaojing
 * @date   2013-04-09 
 */
class UserTest extends myTestCase {
    
    //用户ID
    private static $userid;

    /** 
     * testRegister 测试注册用户    
     *
     */
    public  function testRegister() {
          
        $new_user = $this->getData('new_user');

        //先登出 
        logout();

        $data = array(
               'url' =>  '/User/register/',
               'type' => 'POST',
               'data' => $new_user,
           ); 
        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

        self::$userid = $res->data;

    } //End Of Func testRegister


     /** 
      * testLoginSuccess 测试登录成功
      */
    public  function testLoginSuccess() {

        $new_user = $this->getData('new_user');

        //先登出 
        logout();

        $data = array(
               'url' =>  '/User/login/',
               'type' => 'POST',
               'data' => $new_user,
           ); 
        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testLoginSuccess
        
        
    /** 
     * testSaveUser 测试修改用户
     *
     */
    public  function testSaveUser() {
    
        $new_user = $this->getData('new_user');
        $new_user['password'] = $new_user['password'].'_change';
        $new_user['userid'] = self::$userid;
        $data = array(
               'url' =>  '/User/saveUser/',
               'type' => 'POST',
               'data' => $new_user,
           ); 
        
        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

    } //End Of Func testSaveUser
    

    /** 
     * testDelUser 测试删除用户 
     *
     */
    public  function testDelUser() {
 
        $data = array(
               'url' =>  '/User/delUser/',
               'type' => 'POST',
               'data' => array('userid' => self::$userid),
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testDelUser


} //End Of Class
