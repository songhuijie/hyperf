<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 17:00
 */
namespace App\Model;



class Admin_Role extends Model{

    protected $table = 'admin_role';

    protected $dateFormat = 'U';


    protected $fillable = ['role_id'];


}
