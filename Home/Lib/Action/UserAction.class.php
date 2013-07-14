<?php

/**
 * 用户模块
 * @author chenxiaojing
 * @copy epptime
 */ 
class UserAction extends Action {


   /**
    * 用户登录
    * @param $_POST
    * @access public
    * @return null
    */
    public function login(){

        if ( !$_POST ) {
            if ( username() ) {
                $this->redirect('/');
            }
            $this->display();
            return ;
        } //End of If

        $login = false; 
	$users = C('USERS');
        foreach ( $users as $k=>$user ) {
            if ( $k == $_POST['username'] ) {
                if ( $user == $_POST['password'] ) {
                    $login = true;
                    $login_user = $k;
                    break;
                }
            } 
        }

	if ( $login ) {
		$this->doLogin($login_user);
		return $this->ajaxReturn(1,'登录成功',1);
	}//End Of If

	return $this->ajaxReturn(0,'用户不存在',0);
		
    }//End Of Login


    /**
     * 登出
     * @param null
     * @access public
     * @return $this->redirect
     */
    public function logout(){

        session('user',null);
        $this->redirect('/');

    }//End Of Public

    /**
     * 执行登录操作
     * @access public
     * @return null
     */
    public function doLogin($user){

        session(array('expire'=>3600000));
        session('user',$user);

    }//End Of Func
	
	
	
}//class
