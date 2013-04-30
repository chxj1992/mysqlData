<?php
/**
 * 项目初始化代码放在这。
 * @author eppstudio
 * @package Home.Behavior
 */
class CheckBehavior extends Behavior{
   
    public function run(&$params){

        if (has_call_num('HomeInitBehavior')>=1){

            return;

        }//End Of If

    	if ( username() ) {

        	return ;

        }//End Of if
      
        
        if ( ( MODULE_NAME == 'User' && ACTION_NAME == 'login') ) {

        	return ;

        }//End Of If
        

        redirect('/User/login/');

    }//End Of Func
    
    
}//class

