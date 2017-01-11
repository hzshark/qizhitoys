<?php
namespace Home\Service;

class Programa
{
    Public $SHOW_IMAGE = 1;
    public $SHOW_VIDEO = 2;
    public function addPrograma($name, $status, $note, $img_path, $img_name){
        $series = D("Series");
        $data['name'] = $name;
        $data['indate'] =  date('Y-m-d H:i:s',time());
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['status'] = $status;
        $data['show_type_id'] = $this->SHOW_IMAGE;
        $data['note'] = $note;
        $data['type_id'] = 2;
        $data['image_name'] = $img_name;
        $data['filepath'] = $img_path;
        $series->add($data);
    }

    public function addColumn($sid, $name, $status, $file_path, $file_name){
        $series = D("Column");
        $data['series_id'] = $sid;
        $data['name'] = $name;
        $data['indate'] =  date('Y-m-d H:i:s',time());
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['filepath'] = $file_path;
        $data['image_name'] = $file_name;
        $data['status'] = $status;
        $series->add($data);
    }

    public function updateSeriesBgImg($id, $img_name){
        $series = D("Series");
        $where['id'] = $id;
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['m_image'] = $img_name;
        $series->where($where)->save($data);
    }

    public function updateColumnBgImg($id, $img_name){
        $molde = D("Column");
        $where['id'] = $id;
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['bg_image'] = $img_name;
        $molde->where($where)->save($data);
    }

    public function getAllValidColumn(){
        $series = D("Series");
        $where['status'] = 1;
        $where['type_id'] = 2;
        return $series->where($where)->select();
    }
    public function queryProgramaByName($name){
        $series = D("Series");
        $where['name'] = $name;
        $where['type_id'] = 2;
        return $series->where($where)->find();
    }

    public function queryProgramaById($id){
        $series = D("Series");
        $where['id'] = $id;
        $where['type_id'] = 2;
        return $series->where($where)->find();
    }

    public function queryPrograma(){
        $molde = D("Series");
        $where['type_id'] = 2;
        return $molde->where($where)->select();
    }

    public function queryColumnById($id){
        $molde = D("Column");
        $where["id"] = $id;
        return $molde->where($where)->find();
    }

    public function queryColumnBySId($sid){
        $molde = D("Column");
        $where["series_id"] = $sid;
        return $molde->where($where)->select();
    }

    public function queryColumnBySIdAndName($sid,$name){
        $molde = D("Column");
        $where["series_id"] = $sid;
        $where["name"] = $name;
        return $molde->where($where)->find();
    }

    public function queryShoppingByProgramaId($id){
        $molde = D("Column");
        $where["series_id"] = $id;
        return $molde->where($where)->select();
    }

}
