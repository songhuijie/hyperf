<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-28
 * Time: 11:43
 */
declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\WebSocketServer\Context;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class WebSocketController extends Controller implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
//    public function onMessage(WebSocketServer $server, Frame $frame): void
//    {
//        $server->push($frame->fd, 'Recv: ' . $frame->data);
//    }

    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        //删掉客户端id
        $redis = $this->container->get(\Redis::class);
        //移除集合中指定的value
        $redis->sRem('websocket_sjd_1', $fd);
        $server->close($fd);
        $send_data = [
            'type'=>'leave',
            'message'=>$fd.'用户离开聊天室'
        ];
        $this->sendAll($send_data,$server);
    }

//    public function onOpen(WebSocketServer $server, Request $request): void
//    {
//        $server->push($request->fd, 'Opened');
//    }

    //连接上下文
    //WebSocket 服务的 onOpen, onMessage, onClose 回调并不在同一个协程下触发，因此不能直接使用协程上下文存储状态信息。WebSocket Server 组件提供了 连接级 的上下文，API 与协程上下文完全一样。

    public function onMessage(WebSocketServer $server, Frame $frame): void
    {

        $info = json_decode($frame->data);

        //心跳刷新缓存
        $redis = $this->container->get(\Redis::class);
        //获取所有的客户端id
        $fdList = $redis->sMembers('websocket_sjd_1');
        //如果当前客户端在客户端集合中,就刷新
        if (in_array($frame->fd, $fdList)) {
            $redis->sAdd('websocket_sjd_1', $frame->fd);
            $redis->expire('websocket_sjd_1', 7200);
        }

        switch ($info->type){
            case 'ping':
                break;
            case 'sendTo':


                //发送给某个人
                $send_to_your_data = [
                    'type'=>'to_someone',
                    'from'=> Context::get('username'.$frame->fd),
                    'to'=> User::query()->where('fid',$info->to)->value('account'),
                    'message'=>$info->message,
                ];
                $server->push((int)$info->to,json_encode($send_to_your_data));
                break;
            case 'send':
                //发送给所有人
                $send_data = [
                    'type'=>'to_all',
                    'from'=> Context::get('username'.$frame->fd),
                    'message'=>$info->message,
                ];
                $this->sendAllExceptMine($send_data,$frame->fd,$server);
                break;
            default:
                $server->push($frame->fd, 'Recv: ' . $frame->data);
//                $server->push($frame->fd, 'Username: ' . Context::get('username'));
        }



    }

    public function onOpen(WebSocketServer $server, Request $request): void
    {

        //接受用户id
//        $uid = $request->input('id');
        //保存客户端id
        $uid = $this->request->input('id');

        $redis = $this->container->get(\Redis::class);

        $res1 = $redis->sAdd('websocket_sjd_1', $request->fd);
        $res = $redis->expire('websocket_sjd_1', 7200);

        /**
         * 绑定fid
         */
        User::where('id',$uid)->update(['fid'=>$request->fd]);
        //更新




        $account = User::query()->where('id',$uid)->value('account');

//        $bind = $server->bind($request->fd,(int)$uid);
        $data =  [
            'type'=>'connect',
            'message'=>'连接成功',
            'uid'=>$uid,
            'account'=>$account,
        ];
        $server->push($request->fd,json_encode($data));

        $fdList = $redis->sMembers('websocket_sjd_1');
        Context::set('username'.$request->fd, $account);
        $send_data = [
            'type'=>'notice',
            'message'=>$account.'用户已上线'
        ];
        $fdList = User::query()->whereIn('fid',$fdList)->pluck('account','fid')->toArray();
        $send_all_data = [
            'type'=>'users',
            'data'=>$fdList
        ];
        $this->sendAll($send_data,$server);
        $this->sendAll($send_all_data,$server);


//        Context::set('username', $request->get['username']);
//        $server->push($request->fd, 'Opened');
    }


    public function sendAll($message,$server){
        if($server->connections){
            foreach($server->connections as $fd)
            {
                $server->push($fd, json_encode($message));
            }
        }

    }

    public function sendAllExceptMine($message,$mine_fd,$server){
        if($server->connections){
            foreach($server->connections as $fd)
            {
                if($mine_fd == $fd){

                }else{
                    $server->push($fd, json_encode($message));
                }

            }
        }

    }
}
