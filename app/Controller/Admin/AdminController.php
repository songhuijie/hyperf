<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 17:21
 */

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Service\ToolsService;
use Hyperf\Di\Annotation\Inject;
use App\Model\Admin;

class AdminController extends Controller{


    /**
     * @Inject
     * @var Admin
     */

    private $admin;

    public function index(){

        $list = $this->admin->all();
        return $this->success($list);

    }

    //todo  添加管理员
    public function store(){

        $param = $this->request->all();

        $this->validatorFrom([
            'username'=>'required',
            'password'=>'required',
            'name'=>'required',
            'role_ids'=>'required',
        ]);

        $admin = $this->admin->create($param);

        if($admin){

            $role_ids = explode(',',$param['role_ids']);
            $admin_role = [];
            foreach($role_ids as $v){
                $admin_role[] = ['admin_id'=>$admin->id,'role_id'=>$v];
            }
            $this->admin->role()->insert($admin_role);
            return $this->success();

        }
        return $this->success('添加失败');

    }

    //todo 更新管理员信息
    public function update($id){

        $admin = $this->request->getAttribute('admin');
        $admin_id = $admin->id;
        $param = $this->request->all();
        $this->admin->where('id',$id)->update($param);

        return $this->success();

    }

    //todo 删除管理员
    public function delete($id){


        $this->admin->destroy($id);
        return $this->success();

    }

}
