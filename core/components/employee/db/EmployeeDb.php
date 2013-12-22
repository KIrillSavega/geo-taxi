<?php
use core\components\employee\models as Models;

class EmployeeDb extends BaseDbImplementation
{
    public $containerRules = array(
        'EmployeeContainer' => array(
            'id' => array('skipOnUpdate' => true, 'skipOnInsert' => true),
            'permissionGroupId' => array('dbKey' => 'permission_group_id'),
            'firstName' => array('dbKey' => 'first_name', 'purify' => 'purifyText'),
            'lastName' => array('dbKey' => 'last_name', 'purify' => 'purifyText'),
            'companyEmail' => array('dbKey' => 'company_email', 'purify' => 'purifyText'),
            'privateEmail' => array('dbKey' => 'private_email', 'purify' => 'purifyText'),
            'mobilePhone' => array('dbKey' => 'mobile_phone', 'purify' => 'purifyText'),
            'password' => array('skipOnUpdate' => true, 'skipOnInsert' => false, 'purify' => 'purifyText'),
            'posPinCode' => array('dbKey' => 'pos_pin_code', 'purify' => 'purifyText'),
            'isActive' => array('dbKey' => 'is_active'),
            'companyId' => array('dbKey' => 'company_id'),
            'status' => array(),
            'timezone' => array(),
        ),
        'EmployeePermissionGroupContainer' => array(
            'id' => array('skipOnUpdate' => true, 'skipOnInsert' => true),
            'title' => array('purify' => 'purifyText'),
            'permissions' => array('skipOnConvertToContainer' => true),
        ),
    );

    public $containerToModel = array(
        'EmployeeContainer' => 'core\components\employee\models\Employee',
        'EmployeePermissionGroupContainer' => 'core\components\employee\models\EmployeePermissionGroup',
    );

    public $jsonSettings = array(
        'storeAsJsonAttr' => 'permissions',
        'exclude' => array('id', 'title', 'permissions'),
    );

    public function findEmployeeById($id)
    {
        return $this->selectContainerByPk($id, 'EmployeeContainer', new Models\Employee());
    }

    public function findEmployeePermissionGroupById($id)
    {
        $record = Models\EmployeePermissionGroup::model()->findByPk($id);
        if (is_object($record)) {
            return $this->getConverter('EmployeePermissionGroupContainer')
                    ->convertModelToContainerWithJsonAttr($record, $this->jsonSettings);
        } else {
            return null;
        }
    }

    public function findAllPermissionGroupsByIds($ids)
    {
        $result = array();

        $records = Models\EmployeePermissionGroup::model()->findAllByPk($ids);

        foreach($records as $record){
            if (is_object($record)) {
                $result[] = $this->getConverter('EmployeePermissionGroupContainer')
                    ->convertModelToContainerWithJsonAttr($record, $this->jsonSettings);
            }
        }

        return $result;
    }

    public function findEmployeeIdByPrivateEmail($email)
    {
        $employee = Models\Employee::model()->findByAttributes(array('private_email' => $email));
        return $employee ? $employee->id : null;
    }

    public function findEmployeeIdByPinCode($pinCode)
    {
        $employee = Models\Employee::model()->findByAttributes(array('pos_pin_code' => $pinCode));
        return $employee ? $employee->id : null;
    }
    
    public function findEmployeeIdByCompanyEmail($email)
    {
        $employee = Models\Employee::model()->findByAttributes(array('company_email' => $email));
        return $employee ? $employee->id : null;
    }

    public function findEmployeeIdByMobilePhone($mobilePhone)
    {
        $employee = Models\Employee::model()->findByAttributes(array('mobile_phone' => $mobilePhone));
        return $employee ? $employee->id : null;
    }

    public function findAllEmployeesByIds($id)
    {
        return $this->selectAllContainersByPk($id, 'EmployeeContainer', new Models\Employee());
    }

    public function create($container)
    {
        return $this->insertContainer($container, new Models\Employee());
    }

    public function createEmployeePermissionGroup($container)
    {
        $converter = $this->getConverter('EmployeePermissionGroupContainer');
        $settings = $this->jsonSettings;
        $insertAttributes = $converter->convertContainerToModelAttributesWithJsonAttr($container, 
                ModelContainerConverter::INSERT_SCENARIO, $settings);
        $ar = new Models\EmployeePermissionGroup();
        $ar->setAttributes($insertAttributes, false);
        if( $ar->save() ) {
            return $converter->convertModelToContainerWithJsonAttr($ar, $settings);
        } else {
            $this->addErrors($ar->getErrors(), 'EmployeePermissionGroupContainer');
            return null;
        }
    }

    public function update($container)
    {
        return $this->updateContainer($container, new Models\Employee());
    }

    public function updateEmployeePermissionGroup($container)
    {
        $converter = $this->getConverter('EmployeePermissionGroupContainer');
        $settings = $this->jsonSettings;
        $insertAttributes = $converter->convertContainerToModelAttributesWithJsonAttr($container, 
                ModelContainerConverter::UPDATE_SCENARIO, $settings);
        $model = Models\EmployeePermissionGroup::model()->findByPk($container->id);
        if( !$model ){
            return null;
        }
        $model->setAttributes($insertAttributes, false);
        if( $model->save() ) {
            return $converter->convertModelToContainerWithJsonAttr($model, $settings);
        } else {
            $this->addErrors($model->getErrors(), 'EmployeePermissionGroupContainer');
            return null;
        }
    }

    public function findAllByPermissionGroupId($groupId)
    {
        $dbConnection = Models\Employee::model()->getDbConnection();
        $result = $dbConnection->createCommand()
            ->select('e.id, e.permission_group_id, e.first_name, e.last_name, e.company_email, e.private_email, e.mobile_phone, e.password, e.pos_pin_code, e.is_active, e.company_id')
            ->from('employee e')
            ->join('employee_permission_group g', 'e.permission_group_id=g.id')
            ->where('permission_group_id=:permission_group_id', array(':permission_group_id'=>$groupId))
            ->queryAll();
        return $this->convertResultToContainersArray($result);
    }

    public function updateAttribute($employeeId, $attribute, $value)
    {
        return $this->updateContainerAttributeById($employeeId, 'EmployeeContainer', new Models\Employee(), $attribute, $value);
    }

    protected function convertResultToContainersArray($result)
    {
        $containers = array();
        foreach($result as $node){
            $containers[] = $this->getConverter('EmployeeContainer')->convertArrayToContainer($node);
        }

        return $containers;
    }

    public function getAllPermissionGroups()
    {
        $dbConnection = Models\Employee::model()->getDbConnection();
        $resultDb = $dbConnection->createCommand()
            ->select('id, title')
            ->from('employee_permission_group')
            ->queryAll();
        $result = array();
        if(!empty($resultDb)){
            foreach($resultDb as $employeePermissionGroup){
                $result[$employeePermissionGroup['id']] = $employeePermissionGroup['title'];
            }
        }
        return $result;
    }

    public function deleteEmployeePermissionGroupById($id)
    {
        if(Models\EmployeePermissionGroup::model()->deleteByPk($id)){
            return true;
        }
    }

    public function getAllEmployeeIds()
    {
        $result = array();

        $dbConnection = Models\Employee::model()->getDbConnection();
        $resultDb = $dbConnection->createCommand()
            ->select('id')
            ->from('employee')
            ->queryAll();

        if (is_array($resultDb)) {
            foreach ($resultDb as $row) {
                $result[] = $row['id'];
            }
        }
        return $result;
    }

    public function updatePassword( $employee )
    {

    }
}
