<?php

/** 
 * CommonView extends ViewModel 基本视图模型
 * @date   2013-04-17 
 */
class CommonView extends ViewModel {


    //关联表
    public $_relation;

    /** 
     * relationView 获取相关视图 
     *
     */
    public  function relationView() {

        $res = array(); 
        foreach ( $this->_relation as $row ) {
            $res[] = array( $row=>getViewName($row) );
        }
    
        return $res; 

    } //End Of Func relationView


    /** 
     * get 获取商品及关联列表
     *
     */
    public function get( $views,$p=1,$psize=25 ) {
        
        foreach ( $views as $row ) {
            if( !in_array($row,$this->_relation) ) {
                continue;
            }
            $this->viewFields = array_merge($this->viewFields,$this->$row);
        }
        $result['count'] = $this->count();       
        $data = $this->page("$p,$psize")->select();       
        //$result['data'] = $this->format($data);
        $result['data'] = $data;
        $result['sql'] = $this->getLastSql();

        return $result; 

    } //End Of Func get
    
        
    /** 
     * chartData 获取画图数据
     *
     */
    public  function chartData( $sql ) {

        $data = $this->query($sql); 
        if ( $data === false ) {
            return false;
        }
                    
        return $data; 
    
    } //End Of Func chartData

    /** 
     * calcu 拟合数据
     *
     */
    public function calcu( $sql,$condition=array(),$p=1,$psize=25 ) {

        $salt = randStr();
        $sql_start = $this->getSqlStart($condition,$salt);
        
        $new_sql = "($sql) as $salt WHERE 1 ";

        if( !empty($condition['where']) ) {
            $new_sql = $this->decorateWhere( $new_sql,$condition['where'],$salt ); 
        }

 
        if( !empty($condition['group']) ) {
            $new_sql = $this->decorateGroup( $new_sql,$condition['group'],$salt ); 
        }
  
        if( !empty($condition['order']) ) {
            $new_sql = $this->decorateOrder( $new_sql,$condition['order'],$salt ); 
        }
            
        $count_sql = "SELECT COUNT(*) FROM ".$new_sql;
        $count = $this->query($count_sql); 
        $result['count'] = $count[0]['COUNT(*)'];

        $new_sql = $this->decorateLimit($new_sql,$p,$psize);
        $query_sql = $sql_start.$new_sql;
        $data = $this->query($query_sql); 
        if ( $data === false ) {
            return false;
        }
        $result['data'] = $data;
        $result['sql'] = $query_sql;
                    
        return $result; 

    } //End Of Func calcu    
        

    /** 
     * attachModels 获取关联的附表
     *
     */
    public  function attachModels( $model ) {
        
        foreach ( $this->_relation as $row ) {
            $model = getModelName($row);
            $res[$row] = $model[$row]; 
        } 

        return $res; 

    } //End Of Func attachModels

/*************************************************************
* 私有方法
**********************************************************/ 

    /**
     * 写一个SQL 开头
     */
    private function getSqlStart( $condition,$salt ) {

        foreach ( $condition['show'] as $row ) {
            if ( $row['func'] ) {
                if ( $row['param'] ) {
                    $fields_arr[] = $row['func']."($salt.`".$row['field']."`,'".$row['param']."') AS ".$row['field']."1".$row['func'];
                    continue ;
                }
                $fields_arr[] = $row['func']."($salt.`".$row['field']."`)AS ".$row['field']."1".$row['func'];
                continue ;
            }                     
            $fields_arr[] = '`'.$row['field']."`";                   
        }
        if( $my_field = $condition['my_field'] ) {
            $fields_arr[] = $my_field;
        }
        $fields_arr = array_unique($fields_arr);
        $fields = implode(',',$fields_arr);

        if ( !$fields ) {
            $fields = '*'; 
        }
        $sql_start = "SELECT $fields FROM ";

        return $sql_start;
    }

    
    /**
     * 组装WHERE条件
     */
    private function decorateWhere($sql,$where,$salt) {

        foreach ( $where as $row ) {
            if ( $row['sign'] == 'LIKE' ) {
                $sql_where_arr[] = " $salt.`".$row['field']."`".$row['sign']."'%".$row['value']."%' ";
                continue;
            }
            if ( $row['sign'] == 'IN' ) {
                $sql_where_arr[] = " $salt.`".$row['field']."`".$row['sign']."(".$row['value'].") ";
                continue;
            }
            $sql_where_arr[] = " $salt.`".$row['field']."`".$row['sign']."'".$row['value']."' ";
        } 

        $sql_where = implode('AND',$sql_where_arr);
        $sql .= 'AND'.$sql_where;

        return $sql;

    }
        
    /**
     * 组装 SQL 分组 
     */ 
    private function decorateGroup($sql,$group,$salt) {
        foreach ( $group as $row ) {
            $group_arr[] .= " $salt.`".$row['field']."` ";
        }
        $sql .= 'GROUP BY '.implode(',',$group_arr);
        return $sql;

    }
    
    /**
     * 组装 SQL 排序
     */ 
    private function decorateOrder($sql,$order,$salt) {
        
        foreach ( $order as $row ) {
            $sql_order_arr[] = " $salt.`".$row['field']."` ".$row['order'];
        }
        
        $sql_order = ' ORDER BY '.implode(',',$sql_order_arr); 

        $sql .= $sql_order;

        return $sql;

    }
    

    /**
     * 组装 SQL 分页
     */ 
    private function decorateLimit($sql,$p=1,$psize=25) {
        
        $start = ($p-1)*$psize;
        $sql .= " LIMIT $start,$psize ";
        
        return $sql;

    }



} //End Of Class
