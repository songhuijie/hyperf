<?php
declare(strict_types = 1);

namespace App\Controller;
use Hyperf\Di\Annotation\Inject;
use Nette\Schema\ValidationException;
use Psr\Container\ContainerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class Controller
{
    /**
     * @Inject
     *
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @Inject
     *
     * @var RequestInterface
     */
    protected $request;
    /**
     * @Inject
     *
     * @var ResponseInterface
     */
    protected $response;
    /**
     * 请求成功
     *
     * @param        $data
     * @param string $message
     * @param array $extra
     *
     * @return array
     */
    public function success($data = [], $message = 'success',$extra = [])
    {
        $code = $this->response->getStatusCode();
        return ['msg' => $message, 'code' => $code, 'data' => $data, 'extra'=>$extra];
    }
    /**
     * 请求失败.
     *
     * @param string $message
     *
     * @return array
     */
    public function failed($message = 'Request format error!')
    {
        return ['msg' => $message, 'code' => 500, 'data' => ''];
    }


    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    public function validatorFrom($rule, $mes = [])
    {
        $defaultMessages = [
            'required' => ':attribute 参数不存在',
            'email' => '邮箱不正确',
            'unique' => ':attribute 已被占用',
            'alpha_dash' => ':attribute 有不允许字符',
            'max' => ':attribute 超过最大限制',
            'confirmed' => '两次密码不一致',
        ];
        $defaultRule = [
            'page' => 'int',
            'pageSize' => 'int',
        ];

        // Do something
        $messages = array_merge($defaultMessages, $mes);
        $rule = array_merge($defaultRule, $rule);

        $validator = $this->validationFactory->make(
            $this->request->all(),
            $rule,
            $messages
        );

        if ($validator->fails()){
//            throw new ValidationException($validator->errors()->first());
//            $errorMessage = $validator->errors()->first();
            throw new ValidationException($validator->errors()->first());

        }

    }
}

