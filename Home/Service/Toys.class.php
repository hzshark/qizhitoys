<?php
namespace Home\Service;

class Toys
{

    public function getCompagesToysById($id)
    {
        $compages = D("Compages");
        $where['series_id'] = $id;

        return $compages
            ->where($where)
//             ->buildSql();
        ->select();
    }

    public function getCompagesToysDetail($toyid){
        $compages = D("CompagesDetail");
        $where['p_id'] = $toyid;

        return $compages
        ->where($where)
        ->select();
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
        return $column->join($join)->where($where)->select();
    }
}
