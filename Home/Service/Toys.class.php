<?php
namespace Home\Service;

class Toys
{

    public function getCompagesToysById($id)
    {
        $compages = D("Compages");
        $where['id'] = $id;
        return $compages->where($where)->find();
    }

    public function getCompagesToysBySId($id)
    {
        $compages = D("Compages");
        $where['series_id'] = $id;
        return $compages->where($where)->select();
    }

    public function getEffectiveCompagesToysBySId($id)
    {
        $compages = D("Compages");
        $where['series_id'] = $id;
        $where['status'] = 1;
        return $compages->where($where)->select();
    }

    public function getCompagesToysDetail($toyid)
    {
        $compages = D("CompagesDetail");
        $where['p_id'] = $toyid;
        return $compages->where($where)->select();
    }

    public function getColumn($series_id)
    {
        $column = D("Column");
        $where['series_id'] = $series_id;
        return $column->where($where)->select();
    }

    public function getShoppingsByColumnId($columnId)
    {
        $column = D("Shopping");
        $where['p_id'] = $columnId;
        return $column->where($where)->select();
    }

    public function getShoppingsBySeriesId($series_id)
    {
        $column = D("Column");
        $where['series_id'] = $series_id;
        $join = "LEFT JOIN shoppings on shoppings.p_id = shopping_column.id";
        $field = "shoppings.id, shoppings.tburl,shoppings.name, shoppings.image_name";
        return $column->join($join)
            ->where($where)
            ->field($field)
            ->select();
    }

    public function getEffectiveShoppingsBySeriesId($series_id)
    {
        $column = D("Column");
        $where['shopping_column.series_id'] = $series_id;
        $where['shoppings.status'] = 1;
        $join = "LEFT JOIN shoppings on shoppings.p_id = shopping_column.id";
        $field = "shoppings.id, shoppings.tburl,shoppings.name, shoppings.image_name";
        return $column->join($join)
        ->where($where)
        ->field($field)
        ->select();
    }

    public function getShoppingCount($series_id, $status, $keywords)
    {
        $shopping= D("Shopping");
        $where= array();
        if ($status > -1){
            $where["shoppings.status"] = $status;
        }
        if ($series_id > 0){
            $where["shoppings.status"] = $status;
        }
        if (isset($keywords)&&! empty($keywords)) {
            $where['shoppings.name'] = array(
                "like",
                "%" . $keywords . "%"
            );
        }
        $join1 = "LEFT JOIN shopping_column on shopping_column.id = shoppings.p_id";
        $join2 = "LEFT JOIN series on series.id = shopping_column.series_id";
        if (count($where) > 0){
            return $shopping->join($join1)->join($join2) ->where($where)->count();
        }else{
            return $shopping->join($join1)->join($join2) ->count();
        }
    }

    public function getShoppings ($series_id, $status, $keywords, $p)
    {
        $shopping= D("Shopping");
        $where= array();
        if ($status > -1){
            $where["shoppings.status"] = $status;
        }
        if ($series_id > 0){
            $where["shoppings.status"] = $status;
        }
        if (isset($keywords)&&! empty($keywords)) {
            $where['shoppings.name'] = array(
                "like",
                "%" . $keywords . "%"
            );
        }
        $join1 = "LEFT JOIN shopping_column on shopping_column.id = shoppings.p_id";
        $join2 = "LEFT JOIN series on series.id = shopping_column.series_id";

        $field = array(
            'shoppings.id' => 'id',
            'shoppings.name',
            'shoppings.image_name',
            'shoppings.tburl',
            'shoppings.moddate',
            'shoppings.status',
            'series.name' => 'series_name'
        );

        if (count($where) > 0){
            return $shopping->join($join1)->join($join2)
            ->where($where)
            ->field($field)
            ->limit($p * C("DEFAULT_PAGESIZE"), C("DEFAULT_PAGESIZE"))
            ->select();
        }else{
            return $shopping
            ->join($join1)
            ->join($join2)
            ->field($field)
            ->limit($p * C("DEFAULT_PAGESIZE"), C("DEFAULT_PAGESIZE"))
            ->select();
        }
    }

    public function getCartoonsAllCount($series_id, $status, $cartoon_name)
    {
        $column = D("Series");
        $where["series.status"] = 1;
        $where["series.type_id"] = 1;
        if (! empty($series_id) && $series_id > 0) {
            $where['series.id'] = $series_id;
        }
        if ($status > - 1) {
            $where['compages_toys.status'] = $status;
        }
        if (isset($cartoon_name)) {
            $where['compages_toys.name'] = array(
                "like",
                "%" . $cartoon_name . "%"
            );
        }
        $join1 = "RIGHT JOIN compages_toys on compages_toys.series_id = series.id";
        return $column->join($join1)
            ->where($where)
            ->count();
    }

    public function getPathAndName($file_path)
    {
        $path = mb_strrchr($file_path, "/", TRUE);
        $name = mb_substr($file_path, strrpos($file_path, "/") + 1);
        return array(
            "path" => $path."/",
            "name" => $name
        );
    }

    public function queryCartoonByNameAndProgramaId($name, $sid)
    {
        $compages = D("Shoppings");
        $where["name"] = $name;
        $where["p_id"] = $sid;
        return $compages->where($where)->select();
    }

    public function AddShopping($id, $name, $tburl, $show_img, $status)
    {
        $compages = D("Shopping");
        $data["p_id"] = $id;
        $data["name"] = $name;
        $data["image_name"] = $show_img;
        $data["tburl"] = $tburl;
        $data["indate"] = date('Y-m-d H:i:s', time());
        $data["moddate"] = $data["indate"];
        $data["status"] = $status;
        $compages->add($data);
    }

    public function updateShopping($id, $p_id,$name, $tburl, $show_img, $status)
    {
        $compages = D("Shopping");
        $where['id'] = $id;
        $data["p_id"] = $p_id;
        $data["name"] = $name;
        if (isset($show_img)){
            $data["image_name"] = $show_img;
        }
        $data["tburl"] = $tburl;
        $data["moddate"] = date('Y-m-d H:i:s', time());
        $data["status"] = $status;
        $compages->where($where)->save($data);
    }

    public function delShoppingById($id)
    {
        $compages = D("Shopping");
        $where["id"] = $id;
        $compages->where($where)->delete();
    }
    public function queryShoppingById($id){
        $compages = D("Shopping");
        $where["id"] = $id;
        return $compages->where($where)->find();
    }


    public function queryCartoonByNameAndSeriesId($name, $sid)
    {
        $compages = D("Compages");
        $where["name"] = $name;
        $where["series_id"] = $sid;
        return $compages->where($where)->find();
    }


    public function delCartoon($id){
        $this->delCartoonDetail($id);

        $compages = D("Compages");
        $where['id'] = $id;
        $compages->where($where)->delete();
    }

    public function delCartoonDetail($id){
        $compages = D("CompagesDetail");
        $where['id'] = $id;
        $compages->where($where)->delete();

    }



    public function AddCartoon($series_id, $name, $show_img, $show_type, $file_path = array())
    {
        $compages = D("Compages");
        $data["series_id"] = $series_id;
        $data["name"] = $name;
        $data["image_name"] = $show_img;
        $data["show_type"] = $show_type;
        $data["indate"] = date('Y-m-d H:i:s', time());
        $data["moddate"] = $data["indate"];
        $data["status"] = 0;
        $compages->add($data);
        $ret = $this-> queryCartoonByNameAndSeriesId($name, $series_id);
        //var_dump($ret);
        if ($ret) {
            $cid = $ret["id"];
            $compages_detail = D("CompagesDetail");
            foreach ($file_path as $path) {
                $detail_data['p_id'] = $cid;
                $detail_data['file_name'] = $path;
                $compages_detail->add($detail_data);
            }
        }
    }

        public function updateCartoon($id, $name, $show_type, $status,$show_img, $file_path = array())
        {
            $compages = D("Compages");
            $where['id'] = $id;
            $data["name"] = $name;
            $data['status'] = $status;
            if ($show_img){
                $data["image_name"] = $show_img;
            }
            $data["show_type"] = $show_type;
            $data["moddate"] = date('Y-m-d H:i:s', time());
            $compages->where($where)->save($data);
//             $ret = $this-> queryCartoonByNameAndSeriesId($name, $series_id);
//             //var_dump($ret);
//             if ($ret) {
//                 $cid = $ret["id"];
//                 $compages_detail = D("CompagesDetail");
//                 foreach ($file_path as $path) {
//                     $detail_data['p_id'] = $cid;
//                     $detail_data['file_name'] = $path;
//                     $compages_detail->add($detail_data);
//                 }
//             }
    }

    public function getCartoonDetailList($cartoon_id)
    {
        $molde = D("CompagesDetail");
        $where['p_id'] = $cartoon_id;
        $molde->where($where)->select();
    }

    public function getCartoonList($series_id, $status, $cartoon_name, $p)
    {
        $column = D("Series");
        $where["series.status"] = 1;
        $where["series.type_id"] = 1;
        if (! empty($series_id) && $series_id > 0) {
            $where['series.id'] = $series_id;
        }
        if ($status > - 1) {
            $where['compages_toys.status'] = $status;
        }
        if (isset($cartoon_name)) {
            $where['compages_toys.name'] = array(
                "like",
                "%" . $cartoon_name . "%"
            );
        }

        $join = "RIGHT JOIN compages_toys on compages_toys.series_id = series.id";

        $field = array(
            'compages_toys.id' => 'cartoon_id',
            'compages_toys.name',
            'compages_toys.image_name',
            'compages_toys.show_type',
            'compages_toys.moddate',
            'compages_toys.status',
            'series.name' => 'series_name'
        );
        return $column->join($join)
            ->where($where)
            ->limit($p * C("DEFAULT_PAGESIZE"), C("DEFAULT_PAGESIZE"))
            ->field($field)
            ->select();
    }
}
