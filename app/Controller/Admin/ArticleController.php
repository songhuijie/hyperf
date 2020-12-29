<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 15:40
 */

namespace App\Controller\Admin;

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

        $list = $this->article->all();

        return $this->success($list);
    }


    public function store(){

        $param = $this->request->all(['title','content','rotation_chart','cate_id']);
        $this->validatorFrom([
            'title' => 'required',
            'content' => 'required',
            'rotation_chart' => 'required',
            'cate_id' => 'required',
        ]);
        $this->article->create($param);

        return $this->success();
    }

    public function update($id){

        $param = $this->request->all(['title','content','rotation_chart','cate_id']);
        $this->article->where('id',$id)->update($param);
        return $this->success();

    }

    public function delete($id){

        $this->article->destroy($id);
        return $this->success();

    }
}
