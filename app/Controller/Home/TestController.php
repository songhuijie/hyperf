<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-29
 * Time: 08:59
 */
namespace App\Controller\Home;

use App\Model\Test;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Controller;

class TestController extends Controller{


    /**
     * @Inject
     * @var Test
     */
    private $test;

    public function index(){

        $list = $this->test->all();

        return $this->success($list);
    }


    public function store(){

        $param = $this->request->all(['title','content']);

        $this->validatorFrom([
            'title' => 'required',
            'content' => 'required',
        ]);
        $this->test->create($param);

        return $this->success();
    }

    public function update($id){

        $param = $this->request->all(['title','content']);

        $this->test->where('id',$id)->update($param);
        return $this->success();

    }

    public function delete($id){

        $this->test->destroy($id);

        return $this->success();
    }
}
