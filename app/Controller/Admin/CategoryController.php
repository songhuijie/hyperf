<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-05-29
 * Time: 08:59
 */
namespace App\Controller\Admin;

use App\Model\Category;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Controller;

class CategoryController extends Controller{


    /**
     * @Inject
     * @var Category
     */
    private $category;

    public function index(){

        $param = $this->request->inputs(['page','pageSize']);
        $this->validatorFrom([
        ]);

        $list = $this->category->paginate((int)optional($param)['pageSize'] ?? DEFAULT_PAGE_SIZE);
        return $this->success($list);
    }

    public function store(){

        $param = $this->request->inputs(['cate_name','sort']);
        $this->validatorFrom([
            'cate_name' => 'required',
        ]);
        $this->category->create($param);
        return $this->success();
    }

    public function update($id){

        $param = $this->request->inputs(['cate_name','sort']);
        $this->validatorFrom([
            'cate_name' => 'required',
        ]);
        $this->category->where('id',$id)->update($param);
        return $this->success();

    }

    public function delete($id){

        $this->category->destroy($id);
        return $this->success();
    }
}
