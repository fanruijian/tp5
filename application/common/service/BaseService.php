<?php

namespace app\common\service;

use think\Controller;
use think\Db;

/**
 * 基础控制器
 */

class BaseService
{

    public function page($table,$column,$where,$all=false,$page=1,$pageSize=10,$order=''){
        //防止sql注入
        $noin = " where 1=1 ";
        $where = $noin.$where;
        $countSql = "select count(1) from ".$table.$where.$order;
        $countRes = Db::query($countSql);
        $total = $countRes[0]['count(1)'];
        $start = ($page-1)*$pageSize;
        $limit = " limit $start,$pageSize";
        if($all){
            $sql = "select ".$column." from ".$table.$where.$order;
        }else{
            $sql = "select ".$column." from ".$table.$where.$order.$limit;
        }
        // var_dump($sql);exit;
        $res = Db::query($sql);
        if($all){
            $data = $res;
        }else{
            $data = [
                'count' => $total,
                'list' => $res
            ];
        }
        return $data;
    }

    //直接传入sql,用于复杂查询
    public function sqlPage($sql,$where,$all=false,$page=1,$pageSize=10,$order=''){
        //防止sql注入
        $noin = " where 1=1 ";
        $where = $noin.$where;
        $countSql = "select count(1) from (".$sql.$where.$order.") as z";
        $countRes = Db::query($countSql);
        $total = $countRes[0]['count(1)'];
        $start = ($page-1)*$pageSize;
        $limit = " limit $start,$pageSize";
        if($all){
            $sql = $sql.$where.$order;
        }else{
            $sql = $sql.$where.$order.$limit;
        }
        $res = Db::query($sql);
        if($all){
            $data = $res;
        }else{
            $data = [
                'count' => $total,
                'list' => $res
            ];
        }
        return $data;
    }
}
