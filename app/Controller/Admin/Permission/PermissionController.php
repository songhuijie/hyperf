<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-06-22
 * Time: 16:54
 */

namespace App\Controller\Admin\Permission;

use App\Model\Admin_Role;
use App\Model\Role;
use App\Model\Role_Rule;
use App\Model\Rule;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Controller;

class PermissionController extends Controller{


    /**
     * @Inject
     * @var Role
     */
    private $role;


    /**
     * @Inject
     * @var Role_Rule
     */
    private $role_rule;

    /**
     * @Inject
     * @var Rule
     */
    private $rule;


    /**
     * 角色列表信息
     */

    //todo 角色列表
    public function Role(){
        return $this->success($this->role->all());
    }
    //todo  添加角色
    public function addRole(){


        $param = $this->request->all(['name']);
        $this->validatorFrom([
            'name' => 'required',
        ]);
        //获取用户数据
        $this->role->create($param);

        return $this->success();
    }

    //todo  修改角色名
    public function editRole($id){

        $param = $this->request->all(['name']);
        $this->validatorFrom([
            'name' => 'required',
        ]);
        $this->role->where('id',$id)->update($param);
        return $this->success();

    }

    //todo  删除角色
    public function delRole($id){

        $this->role->destroy($id);
        return $this->success();

    }


    /**
     * 关联信息
     */

    //todo 权限关联
    public function Jurisdiction(){

        $list = $this->role_rule->all();

        return $this->success($list);
    }

    //todo  对应角色添加权限
    public function addJurisdiction(){



        $param = $this->request->all(['role_id','rule_ids']);

        $role_id = $param['role_id'];
        $rule_data = explode(',',$param['rule_ids']);

        $role_rules_data = [];
        foreach($rule_data as $k=>$v){
            $role_rules_data[] = ['role_id'=>$role_id,'rule_id'=>$v];
        }

        $this->role_rule->insert($role_rules_data);

        return $this->success();

    }

    //todo  对应角色编辑权限
    public function editJurisdiction($id){

        $param = $this->request->all(['rule_ids']);

        $own_role_ids = $this->role_rule->where('role_id',$id)->pluck('rule_id');

        $role_rules_array = $own_role_ids->toArray();
        $rule_data = explode(',',$param['rule_ids']);
        $delete_ids = array_diff($role_rules_array,$rule_data);
        $create_ids = array_diff($rule_data,$role_rules_array);
        if($delete_ids){
            $this->role_rule->where('role_id',$id)->whereIn('rule_id',$delete_ids)->delete();
        }
        $role_rules_data = [];
        foreach($create_ids as $k=>$v){
            $role_rules_data[] = ['role_id'=>$id,'rule_id'=>$v];
        }
        $this->role_rule->insert($role_rules_data);
        return $this->success();

    }

    /**
     * 规则信息
     */

    //todo 规则列表
    public function Rule(){

        $rules = $this->rule->all();
        return $this->success($rules);
    }

    //todo 添加规则
    public function addRule(){

        $param = $this->request->all(['title','href','rule','pid']);
        $this->rule->create($param);
        return $this->success();
    }

    //todo 修改规则
    public function editRule($id){

        $param = $this->request->all(['title','href','rule','pid']);
        $this->rule->where('id',$id)->update($param);
        return $this->success();
    }

    //todo 删除规则
    public function delRule($id){

        $this->rule->destroy($id);
        return $this->success();
    }

}
