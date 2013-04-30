<?php
/**
 * 此为项目的常用函数库，供开发使用,只用于处理与逻辑无关系的数据
 * @author eppstudio
 * @package Home
 */
load('extend');
load('eppCommon');


/**
 * 获取当前用户名
 */
function username(){

	return session('user');

}//End Of getUserId();


/**
 * 生成一个密码盐
 */
function getSalt( $len=6 ){

    $salt = substr(md5(time()),0,$len);
    
    return $salt;

}

/**
 * 随即字母串
 */
function randStr($i=6){
    $str = "abcdefghijklmnopqrstuvwxyz";
    $finalStr = "";
    for($j=0;$j<$i;$j++)
    {
        $finalStr .= substr($str,rand(0,25),1);
      }
      return $finalStr;
}


/**
 * 获取视图模型名
 */
function getModelName( $key ){
    $name = C('MODEL_NAME')[$key];
    if ( !$name ) {
        $name = $key; 
    }
    return array($key=>$name);
}


/** 
 * getField 获取字段名 
 *
 */
function getField( $data ) {
    
    $result = array(); 
    foreach ( $data as $k=>$row ) {
        $res = C('FIELD_NAME')[$k];
        if ( !$res ) {
            $res = $k;
        }
        if( strpos($k,'1') ) {
            $f_arr = explode('1',$k);
            $res = C('FIELD_NAME')[$f_arr[0]].$f_arr[1];
        } 
        $result[$k] = $res;
    }

    return $result;

} //End Of Func getField

/** 
 * getOneField 获取字段名 
 *
 */
function getOneField( $k ) {
    
    $res = C('FIELD_NAME')[$k];
    if ( !$res ) {
        $res = $k;
    }
    if( strpos($k,'1') ) {
        $f_arr = explode('1',$k);
        $res = C('FIELD_NAME')[$f_arr[0]].$f_arr[1];
    } 

    return $res;

} //End Of Func getOneField


/** 
 * getChartName 获取图表类型名
 *
 */
function getChartName( $k ) {

    $res = C('CHART_TYPE')[$k];
    if ( !$res ) {
        $res = $k;
    }

    return $res;

} //End Of Func getChartName


/** 
 * getFuncName 获取Mysql函数名
 *
 */
function getFuncName( $k ) {

    $res = C('FUNCTIONS')[$k];
    if ( !$res ) {
        $res = $k;
    }

    return $res;

} //End Of Func getFuncName



/**
 * 获取主模型
 */
function mainModels() {
    $res = array();   
    foreach ( C('MAIN_MODEL') as $row ) {
        $name = C('MODEL_NAME')[$row];
        if ( !$name ) {
            $name = $row;
        }
        $res[$row] = $name;
    } 
    return $res; 
}

/**
 * 去除某条 SQL 中的 LIMIT n,m 部分
 *
 */
function sqlWithoutLimit($sql){

    $sql_arr = explode(' ',$sql); 
    $count = 0;
    foreach ( $sql_arr as $k=>$row ) {
        
        if ( $row == 'LIMIT' OR $count ) {
            unset($sql_arr[$k]); 
            $count ++;   
        } 
        if ( $count > 1 ) {
            break; 
        } 
    }
    $sql = implode(' ',$sql_arr); 

    return $sql;

}


function filterModel($model){
    $models = C('MAIN_MODEL');
    if ( in_array($model,$models) ) {
        return $model; 
    }
    return array_pop($models);
}


function isSelect($sql) {
    $sql_arr = explode(' ',trim($sql));
    $first = array_shift($sql_arr);
    if (strtolower($first) == 'select') {
        return true; 
    }
    return false;
}
