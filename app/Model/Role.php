<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 16:59
 */
namespace App\Model;


class Role extends Model{

    protected $table = 'role';

    protected $dateFormat = 'U';

    protected $fillable = ['name'];

}
