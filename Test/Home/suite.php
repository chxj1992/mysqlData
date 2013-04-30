<?php

// 兼容不同版本的phpunit和phpunit2
if ( !class_exists('PHPUnit_Framework_TestSuite') ) {
    class_alias('PHPUnit2_Framework_TestSuite','PHPUnit_Framework_TestSuite');
}

/**
 * 我的测试套件
 *
 */
class suite extends PHPUnit_Framework_TestSuite {


    public function __construct(){

        $testFiles = array(
            'IndexTest.php', //基本页测试
            'UserTest.php', //用户测试
            'MessageTest.php', //留言测试
            'NoteTest.php', //日记测试
            'RecordTest.php', //日志测试
            'TodoTest.php', //待办事项测试
        );

        $this->addTestFiles( $testFiles );
    }


    public static function suite() {
        //最后一定得返回PHPUnit_Framework_TestSuite对像
        return new self();
    }
}
        
