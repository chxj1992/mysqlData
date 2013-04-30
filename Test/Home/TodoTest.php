<?php

require_once '../myTestCase.php';

/** 
 * TodoTest 待办事项部分测试
 * @author Chenxiaojing
 * @date   2013-04-09 
 */
class TodoTest extends myTestCase {
        
    private static $todoid; 

    //测试初始化 
    public function testInit() {
        login(); 
    }

    /** 
     * testAddTodo 测试添加待办事项
     *
     */
    public  function testAddTodo() {

        $todo = $this->getData('todo');
        $data = array(
               'url' =>  '/Todo/addTodo/',
               'type' => 'POST',
               'data' => $todo,
           ); 
        
        $res = $this->request($data);

        $this->assertTrue($res->status == 1);       

        // 保存测试数据ID
        self::$todoid = $res->data;

    } //End Of Func testAddTodo
    

    /** 
     * testFinishTodo 测试完成待办事项
     *
     */
    public  function testFinishTodo() {
 
        $data = array(
               'url' =>  '/Todo/FinishTodo/',
               'type' => 'POST',
               'data' => array('todoid' => self::$todoid),
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testFinishTodo


    /** 
     * testTodoList 测试获取待办事项列表
     *
     */
    public  function testTodoList() {
 
        $data = array(
               'url' =>  '/Todo/todoList/',
           ); 

        $res = $this->request($data);
        
        $this->assertTrue($res->status == 1);       

    } //End Of Func testTodoList



} //End Of Class
