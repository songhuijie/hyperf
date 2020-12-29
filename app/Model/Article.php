<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 15:20
 */

namespace App\Model;

class Article extends Model{

    protected $table = 'article';

    protected $dateFormat = 'U';

    protected $fillable = ['cate_id','rotation_chart','title','content'];

    protected $casts = ['created_at'=>'datetime:Y-m-d H:i:s','updated_at'=>'datetime:Y-m-d H:i:s'];


    public function getRotationChartAttribute($value){

        if(is_string($value)){
            $value = json_decode($value,true);
            foreach($value as &$v){
                $v = config('app_url').$v;
            }
        }
        return $value;
    }
}
