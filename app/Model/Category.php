<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 15:20
 */

namespace App\Model;

class Category extends Model{

    protected $table = 'category';

    protected $dateFormat = 'U';

    protected $fillable = ['cate_name','sort'];

    protected $casts = ['created_at'=>'datetime:Y-m-d H:i:s','updated_at'=>'datetime:Y-m-d H:i:s'];
}
