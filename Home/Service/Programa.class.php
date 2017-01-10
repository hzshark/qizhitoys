<?php
namespace Home\Service;

class Programa
{
    
    public function queryPrograma(){
        $molde = D("Series");
        $where['type_id'] = 2;
        return $molde->where($where)->select();
    }
    
    public function queryColumnBySId($sid){
        $molde = D("Column");
        $where["series_id"] = $sid;
        return $molde->where($where)->select();
    }
    
    public function queryShoppingByProgramaId($id){
        $molde = D("Column");
        $where["series_id"] = $id;
        return $molde->where($where)->select();
    }
    
}
