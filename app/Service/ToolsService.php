<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-23
 * Time: 17:41
 */
namespace App\Service;

use Hyperf\DbConnection\Db;

class ToolsService{


    //todo  内部调用批量更改数据
    protected static function updateBatch($multipleData = [],$table)
    {
        try {
            $tableName = $table;
            $firstRow  = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默认以id为条件更新
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets      = [];
            $bindings  = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn   = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings  = array_merge($bindings, $whereIn);
            $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            return Db::update($updateSql, $bindings);
        } catch (\Exception $e) {
            return false;
        }
    }


    //todo  切分多次 来更新  默认按照200来分组
    public static function SectionUpdateBatch($multipleData = [],$table,$limit = 200){

        if(count($multipleData) > $limit){
            $chunk_datas = array_chunk($multipleData, $limit, true);
            foreach ($chunk_datas as $chunk_data) {
                self::updateBatch($chunk_data,$table);
            }
        }else{
            self::updateBatch($multipleData,$table);
        }
    }
}
