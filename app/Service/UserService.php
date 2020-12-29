<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-27
 * Time: 08:53
 */
namespace App\Service;

class UserService{

    /**
     * 返回结果
     * @param int $id
     * @return string
     */
    public function getInfoById(int $id){

        return 'true_'.$id;
    }
}
