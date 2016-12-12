<?php
namespace Home\Service;

class Toys
{

    public function getCompagesToysById($id)
    {
        $compages = D("Compages");
        $where['series_id'] = $id;

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
        $field = "shoppings.id, shoppings.tburl, shoppings.filepath, shoppings.image_name";
        return $column->join($join)
            ->where($where)
            ->field($field)
            ->select();
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
        if (! empty($cartoon_name)) {
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

    public function getCartoonDetailList($cartoon_id){
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
        if (! empty($cartoon_name)) {
            $where['compages_toys.name'] = array(
                "like",
                "%" . $cartoon_name . "%"
            );
        }

        $join = "RIGHT JOIN compages_toys on compages_toys.series_id = series.id";

        $field = array(
            'compages_toys.id'=>'cartoon_id',
            'compages_toys.name',
            'compages_toys.image_name',
            'compages_toys.save_path',
            'compages_toys.show_type',
            'compages_toys.moddate',
            'compages_toys.status',
            'series.name'=>'series_name'
        );
        return $column->join($join)
            ->where($where)
            ->limit($p, C("DEFAULT_PAGESIZE"))
            ->field($field)
            ->select();
    }
}
