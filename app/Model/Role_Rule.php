<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 16:59
 */
namespace App\Model;


class Role_Rule extends Model{

    protected $table = 'role_rule';

//    protected $dateFormat = 'U';

    public $timestamps = false;

    protected $fillable = ['role_id','rule_id'];

}
