<?php
declare(strict_types=1);

namespace App\Controller\Home;

use App\Controller\Controller;
use App\Model\User;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\RenderInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Illuminate\Support\Facades\Cookie;
use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @AutoController
 */
class ViewController extends Controller
{



    public function index(RenderInterface $render)
    {
        return $render->render('index.index',['name'=>'jiedage']);
    }

    public function SimpleRegister(RequestInterface $request,RenderInterface $render){

       $param = $request->all(['account','password']);

        $user = User::query()->where('account', $param['account'])->first();
        //验证用户账户密码
        if (!empty($user->password) && password_verify($param['password'], $user->password)) {

            $request = Context::get(ServerRequestInterface::class);
            $request = $request->withAttribute('web_info', $user);
            Context::set(ServerRequestInterface::class, $request);
            return $render->render('index.ws',['id'=>$user->id]);
        }

        return ['msg'=>'验证失败'];
    }


    /**
     * websocket 连接
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function Ws(RenderInterface $render){

        //获取用户数据
        $user = $this->request->getAttribute('web_info');

        if($user){
            return $render->render('index.ws',compact($user));
        }

    }
}
