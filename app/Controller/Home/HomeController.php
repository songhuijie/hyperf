<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Home;

use App\Service\AdminService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\UserService;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Parallel;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\View\RenderInterface;

/**
 * @AutoController()
 */
class HomeController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;


    public function index(RequestInterface $request)
    {

        co(function () {
            $channel = new \Swoole\Coroutine\Channel();
            co(function () use ($channel) {
                $channel->push('data');
            });
            $data = $channel->pop();
            echo '创建协程';
            var_dump($data);
        });

        $id = $request->input('id',1);
        return $this->userService->getInfoById((int)$id);
    }

    /**
     * @Inject(required=false)
     * @var AdminService
     */
    private $adminService;


    public function admin(RequestInterface $request)
    {


        $id = 1;
        if ($this->adminService instanceof AdminService) {
            // 仅值存在时 $userService 可用

            return $this->adminService->getInfoById($id);
        }
        return null;
    }


    public function test(){


            //简单写法
//        $result = parallel([
//            function () {
//                sleep(1);
//                return Coroutine::id();
//            },
//            function () {
//                sleep(1);
//                return Coroutine::id();
//            }
//        ]);
//        var_dump($result);

        $parallel = new Parallel(5);
        for ($i = 0; $i < 20; $i++) {
            $parallel->add(function () {
                sleep(1);
                return Coroutine::id();
            });
        }

        try{
            $results = $parallel->wait();
            var_dump($results);
        } catch(ParallelExecutionException $e){
            var_dump($e->getResults());
            var_dump($e->getThrowables());
            // $e->getResults() 获取协程中的返回值。
            // $e->getThrowables() 获取协程中出现的异常。
        }
    }



    public function redis(){

        $container = ApplicationContext::getContainer();

        $redis = $container->get(RedisFactory::class)->get('default');
        $redis2 = $container->get(RedisFactory::class)->get('foo');
        $result = $redis->keys('*');
        $result2 = $redis2->keys('*');
        return [$result,$result2];
    }


    /**
     * 视图
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function homeView(RenderInterface $render)
    {

        return $render->render('index', ['name' => 'Hyperf']);
    }
}
