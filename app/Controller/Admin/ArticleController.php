<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 15:40
 */

namespace App\Controller\Admin;

use App\Model\Category;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Controller;
use App\Model\Article;

class ArticleController extends Controller{

    /**
     * @Inject
     * @var Article
     */

    private $article;

    public function index(){

        $param = $this->request->inputs(['page','pageSize']);
        $this->validatorFrom([
        ]);
        $list = $this->article->paginate((int)optional($param)['pageSize'] ?? DEFAULT_PAGE_SIZE);

        $category = Category::get(['id','cate_name']);
        return $this->success($list,'success',$category);
    }


    public function store(){

        $param = $this->request->inputs(['title','content','cate_id']);
        $this->validatorFrom([
            'title' => 'required',
            'content' => 'required',
            'cate_id' => 'required|int',
        ]);
        $this->article->create($param);

        return $this->success();
    }

    public function update($id){

        $param = $this->request->inputs(['title','content','cate_id']);
        $this->article->where('id',$id)->update($param);
        return $this->success();

    }

    public function delete($id){

        $this->article->destroy($id);
        return $this->success();

    }
}
