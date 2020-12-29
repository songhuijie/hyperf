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

namespace App\Controller;

class IndexController extends AbstractController
{
    public function index()
    {



        $redis = $this->container->get(\Redis::class);
        $fdList = $redis->sMembers('websocket_sjd_1');

        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
            'redis' => $fdList,
        ];
    }

    public function gets(){
        $method = $this->request->getMethod();
        return [
            'method'=>$method,
            'message'=>'Hello word'
        ];
    }
}
