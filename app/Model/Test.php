<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-28
 * Time: 17:12
 */
namespace App\Model;

use Hyperf\Database\Model\Events\Saving;

class Test extends Model{

    protected $table = 'test';

    protected $dateFormat = 'U';

    protected $fillable = ['title','content'];

    protected $casts = ['created_at'=>'datetime:Y-m-d H:i:s','updated_at'=>'datetime:Y-m-d H:i:s'];


}
