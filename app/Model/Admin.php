<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 16:59
 */
namespace App\Model;


use Hyperf\Database\Model\Events\Saving;

class Admin extends Model{

    protected $table = 'admin';

    protected $dateFormat = 'U';

    protected $fillable = ['username','password','name'];


    public function role(){

        return $this->hasMany(Admin_Role::class,'admin_id');
    }



//    public function creating(Saving $event)
//    {
//        $this->setPassword(password_hash($event->password,PASSWORD_DEFAULT));
//    }
}
