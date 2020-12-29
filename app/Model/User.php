<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-28
 * Time: 17:12
 */
namespace App\Model;

use Hyperf\Database\Model\Events\Saving;

class User extends Model{

    protected $table = 'users';

    protected $dateFormat = 'U';

    protected $fillable = ['account','password'];


}
