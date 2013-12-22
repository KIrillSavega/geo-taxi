<?php

class Employee extends BaseAppComponent
{
    const EMPLOYEE_AVAILABLE = 1;
    const EMPLOYEE_DELETED = 2;

    const PERMISSION_GROUP_FOR_DELETED_EMPLOYEE = 2;

    /**
     * object that implements DB operations
     * @var EmployeeDb
     */
    protected $_db;
    /**
     * object that implements Cache operations
     * @var EmployeeCache
     */
    protected $_cache;

    public $statuses = array(
        self::EMPLOYEE_AVAILABLE => 'Available',
        self::EMPLOYEE_DELETED => 'Deleted'
    );

    public function getEmployeeStatusTitleById($status)
    {
        if(array_key_exists($status, $this->statuses)){
            return $this->statuses[$status];
        }
    }

    /**
     * @param EmployeeContainer $container
     * @return EmployeeContainer
     */
    public function createEmployee(EmployeeContainer $container)
    {
        $container->password = UserHelper::hashPassword($container->password);
        $container->status = self::EMPLOYEE_AVAILABLE;
        $container->mobilePhone = UserHelper::formatPhoneNumber($container->mobilePhone);
        $container = $this->_db->create($container);
        if ($container) {
            $this->_cache->set($container);
            $this->_cache->clearAllEmployeeIds();
            $event = new CoreEventContainer($this, $container);
            $event->eventId = 'employeeCreated';
            $event->message = Yii::t('employee.event', "Employee {firstName} {lastName} with id {id} was created.", array(
                '{id}' => $container->id,
                '{firstName}' => $container->firstName,
                '{lastName}' => $container->lastName,
            ));
            Yii::app()->subscribition->pushEvent($event);
            Yii::app()->sync->reniewTimestampForCollection("employees");
        }
        return $container;
    }

    /**
     * @param $id
     * @param bool $showDeleted
     * @return EmployeeContainer|null
     */
    public function getById( $id, $showDeleted = true )
    {
        $employee = $this->baseGetById($id, array(
            'cacheGetter' => 'getById',
            'cacheSetter' => 'set',
            'dbFinderById' => 'findEmployeeById'
        ));
        if ( $employee ) {
            if ( $showDeleted || ( !$showDeleted && $employee->status == self::EMPLOYEE_AVAILABLE ) ) {
                return $employee;
            }
        }
    }

    /**
     * @param $ids
     * @param bool $showDeleted
     * @return array
     */
    public function getAllByIds( $ids, $showDeleted = true )
    {
        $employees = $this->baseGetAllByIds($ids, array(
            'cacheGetter' => 'getAllById',
            'cacheSetterAll' => 'setAll',
            'dbFinderAllByIds' => 'findAllEmployeesByIds'
        ));

        $result = array();
        foreach ( $employees as $employee ) {
            if ( $showDeleted || ( !$showDeleted && $employee->status == self::EMPLOYEE_AVAILABLE ) ) {
                $result[] = $employee;
            }
        }

        return $result;
    }

    /**
     * @param EmployeeContainer $container
     * @return EmployeeContainer
     */
    public function updateEmployee(EmployeeContainer $container)
    {
        $notUpdatedEmployee = $this->getById( $container->id );
        if ( $container->status == self::EMPLOYEE_DELETED ) {
            if ( Yii::app()->warehouse->isEmployeeUsedByWarehouses( $container->id ) ) {
                $this->addErrors( array("You cannot delete Employee with id $container->id. Employee with id $container->id is used by Warehouses. ") );
            } elseif ( Yii::app()->salesOutlet->isEmployeeUsedBySalesOutlet( $container->id ) ) {
                $this->addErrors( array("You cannot delete Employee with id $container->id. Employee with id $container->id is used by Sales Outlets. ") );
            } else {
                $container->permissionGroupId = self::PERMISSION_GROUP_FOR_DELETED_EMPLOYEE;
            }
        }
        if ( !$this->getErrors() ) {
            $container->mobilePhone = UserHelper::formatPhoneNumber($container->mobilePhone);
            $updated = $this->_db->update( $container );
            if ( $updated ) {
                if ( $updated->status == self::EMPLOYEE_DELETED ) {
                    Yii::app()->subscribition->unsubscribeEmployeeFromAllSubscribitions( $container->id );
                }
                $this->_cache->clearIdByEmail( $notUpdatedEmployee->companyEmail );
                $this->_cache->clearIdByPhone( $notUpdatedEmployee->mobilePhone );
                $this->_cache->clearIdByPinCode( $notUpdatedEmployee->posPinCode );
                $this->_cache->set($updated);
                $event = new CoreEventContainer($this, $container);
                $event->eventId = 'employeeUpdated';
                $event->message = Yii::t('employee.event', "Employee {firstName} {lastName} with id {id} was updated.", array(
                    '{id}' => $container->id,
                    '{firstName}' => $container->firstName,
                    '{lastName}' => $container->lastName,
                ));
                Yii::app()->subscribition->pushEvent($event);
                Yii::app()->sync->reniewTimestampForCollection("employees");
                return $updated;
            }
        }
    }

    /**
     * @param EmployeeContainer $container
     * @return EmployeeContainer|null
     */
    public function updateEmployeePassword(EmployeeContainer $container)
    {
        $defaultContainer = $this->getById($container->id);
        $newPassword = UserHelper::hashPassword($container->password);
        if ($newPassword != $defaultContainer->password) {
            $container->password = $newPassword;
        }
        $updated = $this->_db->updateAttribute($container->id, "password", $container->password);
        if ( $updated ) {
            $this->_cache->clearIdByEmail( $defaultContainer->companyEmail );
            $this->_cache->clearIdByPhone( $defaultContainer->mobilePhone );
            $this->_cache->clearIdByPinCode( $defaultContainer->posPinCode );
            $this->_cache->set( $updated );
            Yii::app()->sync->reniewTimestampForCollection("employees");
            return $updated;
        }
    }

    /**
     * @param $permissionGroupId
     * @return array
     */
    public function getAllByPermissionGroupId($permissionGroupId)
    {
        return $this->_db->findAllByPermissionGroupId($permissionGroupId);
    }

    /**
     * @param $id
     * @param bool $isActive
     * @return EmployeeContainer|null
     */
    public function setIsActiveById($id, $isActive = true)
    {
        $result = $this->_db->updateAttribute($id, "isActive", $isActive);
        if( $result ){
            Yii::app()->sync->reniewTimestampForCollection("employees");
        }
        return $result;
    }

    /**
     * @param EmployeeContainer $employee
     * @param $permission
     * @return bool
     * @throws CException
     */
    public function checkHasPermission(EmployeeContainer $employee, $permission)
    {
        $permissionGroup = $this->getEmployeePermissionGroupById($employee->permissionGroupId);
        if (property_exists($permissionGroup, $permission)) {
            return $permissionGroup->{$permission} ? true : false;
        } else {
            throw new CException("Permission $permission does not exist in EmployeePermissionGroupContainer");
        }
    }

    /**
     * @param $employeeId
     * @param String $permission
     * @return bool
     * @throws Exception
     */
    public function checkHasPermissionById($employeeId, $permission)
    {
        if ($employeeId) {
            $employee = $this->getById($employeeId);
            if ($employee) {
                return $this->checkHasPermission($employee, $permission);
            } else {
                throw new Exception("Employee with id $employeeId was not found");
            }
        }
    }

    /**
     * @param $attributeValue
     * @param $employeeDbMethod
     * @param $cacheKeyCallback
     * @return bool|null|string
     */
    protected function getIdByAttribute($attributeValue, $employeeDbMethod, $cacheKeyCallback)
    {
        if (empty($attributeValue)) {
            return null;
        }
        $id = $this->_cache->getEmployeeIdByAttribute($cacheKeyCallback, $attributeValue);
        if (!$id) {
            $id = $this->_db->{$employeeDbMethod}($attributeValue);
            $this->_cache->setEmployeeIdByAttribute($cacheKeyCallback, $attributeValue, $id);
        }

        return $id;
    }

    /**
     * @param $phone
     * @return bool|null|string
     */
    public function getIdByPhone($phone)
    {
        return $this->getIdByAttribute($phone, 'findEmployeeIdByMobilePhone', 'CacheKey::employeeIdByMobilePhone');
    }

    /**
     * @param $email
     * @return bool|null|string
     */
    public function getIdByCompanyEmail($email)
    {
        return $this->getIdByAttribute($email, 'findEmployeeIdByCompanyEmail', 'CacheKey::employeeIdByCompanyEmail');
    }

    /**
     * @param $pinCode
     * @param $showDeleted
     * @return bool|null|string
     */
    public function getIdByPinCode($pinCode, $showDeleted = false)
    {
        if ($pinCode) {
            $id = $this->getIdByAttribute($pinCode, 'findEmployeeIdByPinCode', 'CacheKey::employeeIdByPinCode');
            if ( $id ) {
                $employee = $this->getById( $id, $showDeleted );
                return $employee ? $id : null;
            }
        }
    }

    /**
     * @param $email
     * @return EmployeeContainer
     */
    public function getEmployeeByCompanyEmail($email)
    {
        $id = $this->getIdByCompanyEmail($email);
        if ($id) {
            return $this->getById( $id, false );
        }
    }

    /**
     * @param $pinCode
     * @return EmployeeContainer|null
     */
    public function getEmployeeByPinCode( $pinCode )
    {
        $id = $this->getIdByPinCode( $pinCode );
        return $this->getById( $id, false );
    }

    /**
     * @param $id
     * @return EmployeePermissionGroupContainer
     */
    public function getEmployeePermissionGroupById($id)
    {
        return $this->baseGetById($id, array(
            'cacheGetter' => 'getEmployeePermissionGroupById',
            'cacheSetter' => 'setEmployeePermissionGroup',
            'dbFinderById' => 'findEmployeePermissionGroupById'
        ));
    }

    public function getAllPermissionGroupsByIds($ids)
    {
        return $this->baseGetAllByIds($ids, array(
            'cacheGetter' => 'getEmployeePermissionGroupById',
            'cacheSetterAll' => 'setEmployeePermissionGroups',
            'dbFinderAllByIds' => 'findAllPermissionGroupsByIds'
        ));
    }

    /**
     * @param EmployeePermissionGroupContainer $container
     * @return EmployeePermissionGroupContainer|null
     */
    public function createEmployeePermissionGroup(EmployeePermissionGroupContainer $container)
    {
        $container = $this->_db->createEmployeePermissionGroup($container);
        if ($container) {
            $this->_cache->setEmployeePermissionGroup($container);
        }
        return $container;
    }


    /**
     * @param EmployeePermissionGroupContainer $container
     * @return EmployeePermissionGroupContainer|null
     */
    public function updateEmployeePermissionGroup(EmployeePermissionGroupContainer $container)
    {
        $updated = $this->_db->updateEmployeePermissionGroup($container);
        if ($updated) {
            $this->_cache->setEmployeePermissionGroup($updated);
        }
        return $updated;
    }

    /**
     * @return bool|string
     */
    public function getAllEmployeePermissionGroups()
    {
        $result = $this->_cache->getEmployeePermissionGroups();
        if (empty($result)) {
            $result = $this->_db->getAllPermissionGroups();
            $this->_cache->setEmployeePermissionGroups($result);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getPermissionList()
    {
        $result = array();
        $exclude = array('id','title');
        $reflection = new ReflectionClass('EmployeePermissionGroupContainer');
        $properties = $reflection->getProperties();
        foreach( $properties as $property ){
            $name = $property->getName();
            if( $property->isPublic() && !in_array($name, $exclude) ){
                $result[] = $name;
            }
        }
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getEmployeePermissionGroupTitleById($id)
    {
        $employeePermissionGroup = $this->getEmployeePermissionGroupById($id);
        if ($employeePermissionGroup) {
            return $employeePermissionGroup->title;
        }
    }

    /**
     * @param $container
     * @param array $params
     * @return array
     */
    public function searchByContainerCriteria($container, $params = array())
    {
        $IDs = $this->_db->searchIDsByContainerCriteria($container, $params);
        if ($container instanceof EmployeePermissionGroupContainer) {
            return $this->getAllPermissionGroupsByIds($IDs);
        } else {
            return $this->getAllByIds($IDs);
        }
    }

    /**
     * @param $container
     * @param array $params
     * @return int
     */
    public function getTotalCountForContainer($container, $params = array())
    {
        return $this->_db->getTotalCountForContainer($container, $params);
    }

    /**
     * @return int
     */
    public function getSuperAdminPermissionGroupId()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getNoPermissionGroupId()
    {
        return 2;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteEmployeePermissionGroupById($id)
    {
        if(($id != $this->getSuperAdminPermissionGroupId()) || ($id != $this->getNoPermissionGroupId())){
            $subEmployees = $this->getAllByPermissionGroupId($id);
            if(empty($subEmployees)){
                $employeePermissionGroup = $this->getEmployeePermissionGroupById($id);
                if($this->_db->deleteEmployeePermissionGroupById($id)){
                    $this->_cache->clearEmployeePermissionGroup($employeePermissionGroup);
                    return true;
                }
            }
        }
        return false;
    }

    public function getEmployeeFullNameById( $id )
    {
        $employee = $this->getById( $id );
        if ( $employee ){
            return $employee->firstName." ".$employee->lastName;
        } else {
            return $this->getGuest();
        }
    }

    public function getGuest()
    {
        return 'Guest';
    }

    /**
     * @param bool $showDeleted
     * @return EmployeeContainer[]
     */
    public function getAllEmployees( $showDeleted = true )
    {
        $IDs = $this->getAllEmployeeIds();
        return $this->getAllByIds( $IDs, $showDeleted );
    }

    /**
     * @return array
     */
    public function getAllEmployeeIds()
    {
        $IDs = $this->_cache->getAllEmployeeIds();
        if (empty($IDs)) {
            $IDs = $this->_db->getAllEmployeeIds();
            $this->_cache->setAllEmployeeIds($IDs);
        }

        return $IDs;
    }

    /**
     * @param bool $showDeleted
     * @return array
     */
    public function getAllEmployeesFullNames( $showDeleted = true )
    {
        return CHtml::listData($this->getAllEmployees( $showDeleted ),'id',function($employee) {
            return CHtml::encode($employee->firstName . ' ' . $employee->lastName);
        });
    }

    /**
     * @param $id
     * @return EmployeeContainer|null
     */
    public function deleteById( $id )
    {
        $employee = $this->getById( $id );
        if ( $employee ) {
            $employee->status = self::EMPLOYEE_DELETED;
            return $this->updateEmployee( $employee );
        }
    }

    public function getCurrentEmployee()
    {
        switch(Yii::app()->name){
            case 'admin':
                return $this->getEmployeeFromSession();
            case 'employee':
                return $this->getEmployeeFromSession();
            case 'api':
                return $this->getEmployeeFromSession();
            case 'eshop':
                return null;
            default:
                return null;
        }
    }

    private function getEmployeeFromSession()
    {
        $employeeId = Yii::app()->user->id;
        if($employeeId){
            return $this->getById($employeeId, false);
        }
    }
}