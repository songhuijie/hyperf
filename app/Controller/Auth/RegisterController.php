<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-28
 * Time: 22:51
 */
declare(strict_types = 1);

namespace App\Controller\Auth;

use App\Model\User;
use App\Controller\Controller;
use Hyperf\Di\Annotation\Inject;

class RegisterController extends Controller
{

    /**
     * 用户注册
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function register()
    {
        // $hash = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
        // return $this->failed($hash);

        $param = $this->request->all(['account','password']);

        //验证用户账户密码
        if  (1)  {
            //注册
            $param['password'] = password_hash($param['password'],PASSWORD_DEFAULT);
            User::create($param);
            return $this->success([],'注册成功');
        }

        return $this->failed('注册失败');
    }

}
