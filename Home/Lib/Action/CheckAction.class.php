<?php

class CheckAction extends Action {

    
    /** 
     * chart 查看保存图像
     *
     */
    public  function chart() {
         
        if ( $_REQUEST['month'] ) {
            $month = $_REQUEST['month'];
            setcookie('month',$month,time()+86400);
        }
        $month = $_COOKIE['month'] ? $_COOKIE['month'] : date('Y-m');
        $chart_dir = @dir(WEB_ROOT."/Charts");
        while ( false !== ($month_t = $chart_dir->read()) ) {
            if ( $month_t == '.' OR $month_t == '..' ) {
                continue ; 
            }
            $months[] = $month_t;
        }
        $chart_dir->close();
        if ( !in_array($month,$months) ) {
            $month = date('Y-m');
        }

        foreach ( $months as $k => $row ) {
           if ( $row == $month ) unset($months[$k]);
        }
        $dir = @dir(WEB_ROOT."/Charts/$month");
        while ( false !== ($file_t = $dir->read()) ) {
            if ( $file_t == '.' OR $file_t == '..' ) {
                continue ; 
            }
            $list[] = $file_t;
        }
        $dir->close();
    
        $title = $_COOKIE['title'];
        if ( $_REQUEST['title']  ) {
            $title = $_REQUEST['title'];
            setcookie('title',$title);
        }     
        if ( $title ) {
            $content = file_get_contents(WEB_ROOT."/Charts/$month/$title");
            $this->assign('content',$content); 
        }

        arsort($list);

        $this->assign('months',$months);
        $this->assign('month',$month);
        $this->assign('list',$list );
        $this->display(); 

    } //End Of Func chart


     /** 
      * unsetChart 重置
      *
      */
     public  function unsetChart() {

        setcookie('title',false,time()-86400,'/Check/chart');
     
     } //End Of Func unsetChart
        
    /** 
     * saveChart 保存页面
     *
     */
    public  function saveChart() {
        
        if ( !$_REQUEST['chart'] ) {
            return T('参数错误');
        }
        ob_start();
        echo $_REQUEST['chart'];
        $html = ob_get_contents();
        ob_end_clean();

        $title = $_REQUEST['title'] ? $_REQUEST['title'] : '未命名图表';
        $month = date('Y-m');
        $time = date('Y-m-d H:i:s');
        if ( ! is_dir(WEB_ROOT."/Charts/$month/") ) {
            mkdir(WEB_ROOT."/Charts/$month/");
        }
        $chart_root = WEB_ROOT."/Charts/$month/$time $title.html";
        $fp = fopen("$chart_root",'w');
        $res = fwrite($fp,$html);
        if ( $res ) {
            $this->ajaxReturn(1,'success',1); 
        }
            $this->ajaxReturn(0,'fail',0); 

    } //End Of Func saveChart



    /** 
     * delChart 删除图表
     *
     */
    public  function delChart() {
        
        if ( !$_REQUEST['title'] OR !$_REQUEST['month'] ) {
            return T('参数错误'); 
        }       
        $title = $_REQUEST['title'];
        $month = $_REQUEST['month'];
        $res = @unlink(WEB_ROOT."/Charts/$month/$title");

        if ( $res ) {
            $this->ajaxReturn(1,'success',1); 
        }
        $this->ajaxReturn(0,'fail',0); 

    } //End Of Func delChart
   


    /** 
     * data 查看历史数据
     *
     */
    public  function data() {
        
        //分页
        $p = intval($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $psize = intval($_REQUEST['psize']) ? $_REQUEST['psize'] : $_COOKIE['psize'];
        if ( !$psize )  {
            $psize = 25;
        }
        setcookie('psize',$psize,time()+86400,'/Check/data');

        $count = M('Sql','data_',"DB_CONFIG1")->count();   
        $list = M('Sql','data_',"DB_CONFIG1")->page("$p,$psize")->order('sql_id DESC')->select();   

        loadHelper('Page.class.php');
        $Page   = new Page($count,$psize);
        $Page->url = 'Check/data/p';
        $show = $Page->show();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display(); 

    } //End Of Func data
    

    /** 
     * saveData 保存数据 
     *
     */
    public  function saveData() {

        if ( !$_REQUEST['sql'] ) {
            return T('参数错误');
        }
        $data['sql'] = $_REQUEST['sql'];
        $data['name'] = $_REQUEST['sql_name'] ? $_REQUEST['sql_name'] : '未命名数据';
        $data['createtime'] = date('Y-m-d H:i:s');

        $res = M('Sql','data_',"DB_CONFIG1")->add($data);   

        if ( $res ) {
            $this->ajaxReturn(1,'success',1); 
        }
            $this->ajaxReturn(0,'fail',0); 
   
    } //End Of Func saveData
    

    /** 
     * delData 删除数据
     *
     */
    public  function delData( ) {
        
        if ( !$sql_id = intval($_REQUEST['sql_id']) ) {
            return T('参数错误'); 
        } 
        $where['sql_id'] = $sql_id;
        $res = M('Sql','data_',"DB_CONFIG1")->where($where)->delete();   

        if ( $res === false ) {
            return T('fail');
        }
        $this->ajaxReturn(1,'success',1);
    
    } //End Of Func delData



}//class
