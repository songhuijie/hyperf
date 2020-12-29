<?php
namespace App\Exception\Handler;

use Hyperf\Database\Exception\QueryException;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;

use Nette\Schema\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CustomExceptionHandler extends  ExceptionHandler
{

    const HTTP_OK = 200; //请求失败
    const HTTP_BAD_REQUEST = 400; //请求失败
    const HTTP_NOT_FOUND = 404;  //请求未找到
    const HTTP_INTERNAL_SERVER_ERROR = 500; //服务器异常错误

    public function handle(Throwable $throwable, ResponseInterface $response)
    {


        // 判断被捕获到的异常是希望被捕获的异常
//        if ($throwable instanceof ModelNotFoundException) {
//            // 格式化输出
//            $data = json_encode([
//                'code' => $throwable->getCode(),
//                'message' => $throwable->getMessage(),
//            ], JSON_UNESCAPED_UNICODE);
//
//            // 阻止异常冒泡
//            $this->stopPropagation();
//            return $response->withStatus(500)->withBody(new SwooleStream($data));
//        }

        if($throwable instanceof ValidationException){
            //拦截表单验证出现的异常
            $code = self::HTTP_OK;
            $message = '验证失败';
        }elseif($throwable instanceof NotFoundException){
            //404
            $code = self::HTTP_NOT_FOUND;
            $message = '页面未找到';
        }elseif($throwable instanceof QueryException){
            //sql 查询错误
            $code = self::HTTP_INTERNAL_SERVER_ERROR;
            $message = '查询错误';
        }else{
            //其他异常  服务器
            $code = self::HTTP_INTERNAL_SERVER_ERROR;
            $message = '其他错误';
        }

        $data = json_encode([
            'code' => $throwable->getCode(),
            'message' => $message,
            'message_detail' => $throwable->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
        // 交给下一个异常处理器
        $this->stopPropagation();
        return $response->withStatus($code)->withBody(new SwooleStream($data));

        // 或者不做处理直接屏蔽异常
    }

    /**
     * 判断该异常处理器是否要对该异常进行处理
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
