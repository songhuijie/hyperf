<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 17:37
 */
namespace App\Controller\Admin\Auth;

use App\Controller\Controller;
use App\Model\Admin;
use Illuminate\Support\Facades\Auth;
use Phper666\JWTAuth\JWT;
use Hyperf\Di\Annotation\Inject;

class LoginController extends Controller{


    /**
     * @Inject
     * @var JWT
     */

    private $jwt;

    public function login()
    {

        $this->validatorFrom([
            'username'=>'required',
            'password'=>'required',
        ]);
        $user = Admin::query()->where('username', $this->request->input('username'))->first();
        //验证用户账户密码
        if (!empty($user->password) && password_verify($this->request->input('password'), $user->password)) {
            $userData = [
                'uid' => $user->id,
                'account' => $user->account,
            ];
            $token = $this->jwt->getToken($userData);
            $data = [
                'token' => (string)$token,
                'exp' => $this->jwt->getTTL(),
                'name'=>$user->username,
                'avatar'=>'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
                'roles'=>['admin'],
                'introduction'=>'捷大哥',
            ];
            return $this->success($data);
        }
        return $this->failed('登录失败');
    }


    /**
     * 管理员信息
     * @return array
     */
    public function info()
    {

        $admin = $this->request->getAttribute('admin');
        $admin->name = $admin->username;
        $admin->avatar = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $admin->roles = ['admin'];
        $admin->introduction = '捷大哥';
        return $this->success($admin);
    }
}
