<?php

class EmployeeAccessControl extends CAccessControlFilter
{
    protected $rules;
    
    public function setRules($rules)
    {
        foreach($rules as $rule){
            if(is_array($rule) && isset($rule[0])){
                $r = new EmployeeAccessRule();
                $r->allow=$rule[0]==='allow';
                foreach(array_slice($rule,1) as $name=>$value){
                        if($name==='expression' || $name==='roles' || $name==='message' 
                                || $name==='deniedCallback' || $name == 'permission')
                                $r->$name=$value;
                        else
                                $r->$name=array_map('strtolower',$value);
                }
                $this->rules[]=$r;
            }
        }
    }
    
    public function getRules()
    {
        return $this->rules;
    }
}



class EmployeeAccessRule extends CAccessRule
{
    public $permission;
    
    public function isUserAllowed($user,$controller,$action,$ip,$verb)
    {
        $allowed = parent::isUserAllowed($user, $controller, $action, $ip, $verb);
        if( $allowed == 0 || $allowed == -1 ){
            return $allowed;
        } elseif( $allowed == 1 ) {
            return $this->getEmployeeHasPermission() ? 1 : -1;
        }
            
    }
    
    protected function getEmployeeHasPermission()
    {
        if( $this->permission ){
            $employeeId = Yii::app()->user->getId();
            return Yii::app()->employee->checkHasPermissionById($employeeId, $this->permission);
        }
        return true;
    }
}
