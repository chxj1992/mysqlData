<?php

class IndexAction extends Action {

    
    /** 
     * index 开始页
     *
     */
    public  function index() {
        
        $models = mainModels();
        $this->assign('models',$models);     
        $this->display();
     
    } //End Of Func index

    
    /**
     * 显示数据结果
     *
     */
    public function show(){
 
        $model = $_COOKIE['model'];
        if ( $_REQUEST['model'] ) {
            $model = $_REQUEST['model'];
        }
        if ( $_POST['refresh'] ) {
            setcookie('relation',false,time()-86400,'/Index/data');   
        }
        $model = filterModel($model);    
        setcookie('model',$model,time()+86400,'/Index/show');
    
        $p = intval($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $psize = intval($_REQUEST['psize']) ? $_REQUEST['psize'] : $_COOKIE['psize'];
        if ( !$psize )  {
            $psize = 25;
        }
        setcookie('psize',$psize,time()+86400,'/Index/show');

        $this->assign('p',$p);
        $this->assign('psize',$psize);
        $this->assign('main_models',mainModels());
        $this->assign('attach_models',D($model,'View')->attachModels($model));
        $model_name = getModelName($model);
        $this->assign('model',$model);
        $this->assign('model_name',$model_name[$model]);
        $this->assign('relation_res',json_decode($_COOKIE['relation_res'],true));
        $this->display();

    }
    

     /** 
      * data 获取数据表
      *
      */
    public  function data() {

        if ( !$_REQUEST['model'] ) {
            return T('参数错误,没有获取到数据模型!');
        }
        $model = $_REQUEST['model'];

        //关联表
        $relation = json_decode($_COOKIE['relation']);
        if ( $_POST['relation'] OR $_POST['refresh']) {
            $relation = $_POST['relation'];
            $relation_str = json_encode($relation);
            setcookie('relation',$relation_str);   
        }        
        $relation = array_empty($relation);

        //分页
        $p = intval($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $psize = intval($_REQUEST['psize']) ? $_REQUEST['psize'] : 25;
        setcookie('psize',$psize,time()+86400,'/Index/show');

        $result = D($model,'View')->get( $relation,$p,$psize );

        loadHelper('Page.class.php');
        $Page   = new Page($result['count'],$psize);
        $Page->url = 'Index/show/p';
        $show = $Page->show();
         
        //字段映射表
        $field = getField($result['data'][0]);
        $field_str = json_encode($field);

        foreach ( $relation as $row ) {
            $res= getModelName($row);
            $relation_res[$row] = $res[$row];
        }

        setcookie('relation_res',json_encode($relation_res),time()+86400,'/Index/show');   
        $this->assign('sql',$result['sql']);   
        $this->assign('data',$result['data']);
        $this->assign('field',$field);
        $this->assign('field_str',$field_str);
        $this->assign('page',$show);
        $model_name = getModelName($model);
        $this->assign('model',$model);
        $this->assign('model_name',$model_name[$model]);
        $this->assign('relation',$relation_res);
        
        $html = $this->fetch();
        $this->ajaxReturn($html,'success',1); 

    } //End Of Func data


    /** 
     * calcu 数据统计页
     *
     */
    public  function calcu() {

        if ( $_POST['sql'] ) {
            $sql = $_POST['sql'];
            setcookie('show_sql',$sql,time()+86400,'/Index/calcu');
            setcookie('condition',false,time()-86400,'/Index/calcu');
        } elseif ( $_COOKIE['old_sql'] ) {
            $sql = $_COOKIE['old_sql']; 
        } else {
            $this->display();
            return ;
        }
        if ( $_POST['field'] ) {
            setcookie('field',$_POST['field'],time()+86400,'/Index/calcu');
        }
        setcookie('old_sql',$sql,time()+86400,'/Index/calcu');
        
        $p = intval($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $psize = intval($_COOKIE['psize']) ? $_COOKIE['psize'] : 25;

        $this->assign('field',json_decode($_COOKIE['field'],true));
        $this->assign('condition',json_decode($_COOKIE['condition'],true));
        $this->assign('functions',C('FUNCTIONS'));
        $this->assign('p',$p);
        $this->assign('psize',$psize);
        $this->assign('old_sql',$sql);
        $this->assign('show_sql',$_COOKIE['show_sql']);

        $this->display();
    
    } //End Of Func calcu



    /** 
     * doCalcu 拟合查询出的数据
     *
     */
    public  function doCalcu() {
        
        if ( !$_POST['sql'] ) {
            return T('参数错误,缺少源数据'); 
        }
        if ( !isSelect($_POST['sql']) ) {
            return T('参数错误,SQL语句必须以SELECT开头'); 
        }
        if ( $_POST['refresh'] ) {
            setcookie('condition',false,time()-86400,'/Index/calcu');
        }
        $sql = sqlWithoutLimit($_POST['sql']);
        $condition = $this->gatherCondition($_REQUEST);

        //分页
        $p = intval($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $psize = intval($_REQUEST['psize']) ? $_REQUEST['psize'] : 25;
        setcookie('psize',$psize,time()+86400,'/Index/calcu');
        $result = D('Common','View')->calcu($sql,$condition,$p,$psize);

        loadHelper('Page.class.php');
        $Page   = new Page($result['count'],$psize);
        $Page->url = 'Index/calcu/p';
        $show = $Page->show();

        //设置外层sql
        if ( empty($condition) ) {
            setcookie('old_sql',$_POST['sql'],time()+86400,'/Index/calcu');
        } else {
            setcookie('old_sql',$result['sql'],time()+86400,'/Index/calcu');
            setcookie('condition',json_encode($condition),time()+86400,'/Index/calcu');
        }

        //字段映射表
        $field = getField($result['data'][0]);
        $field_str = json_encode($field);
        setcookie('field',json_encode($field),time()+86400,'/Index/calcu');
        $this->assign('field_str',$field_str);
        $this->assign('data',$result['data']);
        $this->assign('sql',$result['sql']);
        $this->assign('request',$_REQUEST);
        $this->assign('field',$field);
        $this->assign('page',$show);

        $html = $this->fetch('Index:data');
        $this->ajaxReturn($html,'success',1); 

    } //End Of Func doCalcu
     

    /** 
     * gatherCondition 收集条件
     *
     */
    private function gatherCondition( $request ) {
    
        $condition = array();

        //筛选器
        if( !empty($request['where']) ) {
            $condition['where'] = $request['where'];
        }
        
        //分组器 
        if( !empty($request['group']) ) {
            $condition['group'] = $request['group'];
        }
    
        //显示字段
        if( !empty($request['show']) ) {
            $condition['show'] = $request['show'];
        }
       
        //自定义字段
        if( !empty($request['my_field']) ) {
            $condition['my_field'] = $request['my_field'];
        }

        //排序方式
        if( !empty($request['order']) ) {
            $condition['order'] = $request['order'];
        }

        return $condition;

    } //End Of Func gatherCondition     
    

    /** 
     * charts 生产图像
     *
     */
    public  function charts() {
 
        if ( $_POST['sql'] ) {
            $sql = $_POST['sql'];
            setcookie('chart_type',false,time()-86400,'/Index/charts');
        } elseif ( $_COOKIE['sql'] ) {       
            $sql = $_COOKIE['sql'];
        } else {
            $this->display();
            return ;
        }
        if ( $_POST['field'] ) {
            setcookie('field',$_POST['field'],time()+86400,'/Index/charts');
        }
        setcookie('sql',$sql,time()+86400,'/Index/charts');
        
        $this->assign('field',json_decode($_COOKIE['field'],true));
        $this->assign('column',json_decode($_COOKIE['column'],true));
        $this->assign('column_field',$_COOKIE['column_field']);
        $this->assign('data',json_decode($_COOKIE['data'],true));
        $this->assign('data_field',json_decode($_COOKIE['data_field'],true));
        $this->assign('title',$_COOKIE['title']);
        $this->assign('chart_type',$_COOKIE['chart_type']);
        $this->assign('charts',C('CHART_TYPE'));
        $this->assign('y_name',$_COOKIE['y_name']);
        $this->assign('x_name',$_COOKIE['x_name']);
        $this->assign('sql',$sql);

        $this->display();
    
    } //End Of Func charts
    

    /** 
     * chartData 根据sql获取数据
     *
     */
    public  function chartData() {
 
        if ( !$_POST['sql'] ) {
            return T('参数错误,缺少源数据'); 
        }
        if ( !isSelect($_POST['sql']) ) {
            return T('参数错误,SQL语句必须以SELECT开头'); 
        }

        $result = D('Common','View')->chartData($_POST['sql']);

        //字段映射表
        $field = getField($result[0]);
        if ( !$_COOKIE['field'] ) {
            setcookie('field',json_encode($field),time()+86400,'/Index/charts');
        }
        $this->assign('data',$result);
        $this->assign('field',$field);

        $html = $this->fetch('Index:data');
        $this->ajaxReturn($html,'success',1);

    } //End Of Func chartData


    /** 
     * chartShow 获取图表页面
     *
     */
    public  function chartShow() {

        if ( !$_REQUEST['type'] OR !$_REQUEST['sql'] ) {
            return T('参数错误,缺少图表类型或源数据'); 
        }
        $type = $_REQUEST['type'];
        $title = $_REQUEST['title'] ? $_REQUEST['title'] : '水果巴士数据图表';
        $result = D('Common','View')->chartData($_POST['sql']);
        $column_field = $_REQUEST['column_field'];
        $data_field = array_empty($_REQUEST['data_field']);
        
        foreach ( $result as $row ) {
            $column[] = $row[$column_field];
        }

        foreach ( $data_field as $one ) {
            $one_name = getOneField($one);
            foreach ( $result as $row ) {
                $data[$one_name][] = $row[$one];
            }
        }
        $y_name = $_REQUEST['y_name'] ? $_REQUEST['y_name'] : '纵坐标名';
        $x_name = $_REQUEST['x_name'] ? $_REQUEST['x_name'] : '横坐标名';
        setcookie('y_name',$y_name,time()+86400,'/Index/charts');
        setcookie('x_name',$x_name,time()+86400,'/Index/charts');
        setcookie('chart_type',$type,time()+86400,'/Index/charts');
        setcookie('column',json_encode($column),time()+86400,'/Index/charts');
        setcookie('column_field',$column_field,time()+86400,'/Index/charts');
        setcookie('data_field',json_encode($data_field),time()+86400,'/Index/charts');
        setcookie('data',json_encode($data),time()+86400,'/Index/charts');
        setcookie('title',$title,time()+86400,'/Index/charts');

        $this->ajaxReturn(1,'success',1);
    
    } //End Of Func chartShow
    

    /** 
     * unsetChart 重置画图数据
     *
     */
    public  function unsetChart() {
    
        setcookie('chart_type',false,time()-86400,'/Index/charts');
        $this->ajaxReturn(1,'success',1);
    
    } //End Of Func unsetChart
    

} //class
