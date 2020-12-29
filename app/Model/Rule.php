<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 17:03
 */
namespace App\Model;


class Rule extends Model{

    protected $table = 'rule';

    public $timestamps = false;

    protected $fillable = ['title','href','rule','pid','check','status','level','icon','sort'];


}
