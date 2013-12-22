<?php

class Customer extends BaseAppComponent
{
    /**
     * object that implements DB operations
     * @var CustomerDb
     */
    protected $_db;
    /**
     * object that implements Cache operations
     * @var CustomerCache
     */
    protected $_cache;

    /**
     * @param CustomerContainer $container
     * @return CustomerContainer
     */
    public function createCustomer(CustomerContainer $container)
    {
        $container->password = UserHelper::hashPassword($container->password);
        $container = $this->_db->create($container);
        if ($container) {
            $this->_cache->set($container);
            $this->_cache->clearAllCustomerIds();
        }
        return $container;
    }

    /**
     * @param $id
     * @return CustomerContainer|null
     */
    public function getById($id)
    {
        return $this->baseGetById($id, array(
            'cacheGetter' => 'getById',
            'cacheSetter' => 'set',
            'dbFinderById' => 'findById'
        ));
    }

    /**
     * @param $ids
     * @return array
     */
    public function getAllByIds($ids)
    {
        return $this->baseGetAllByIds($ids, array(
            'cacheGetter' => 'getAllById',
            'cacheSetterAll' => 'setAll',
            'dbFinderAllByIds' => 'findAllByIds'
        ));
    }

    /**
     * @param CustomerContainer $container
     * @return CustomerContainer
     */
    public function updateCustomer(CustomerContainer $container)
    {
        $notUpdatedCustomer = $this->getById($container->id);
        $updated = $this->_db->update($container);
        if ($updated) {
            $this->_cache->clearIdByEmail($notUpdatedCustomer->privateEmail);
            $this->_cache->clearIdByPhone($notUpdatedCustomer->mobilePhone);
            $this->_cache->set($updated);
            return $updated;
        }
    }

    /**
     * @param CustomerContainer $container
     * @return CustomerContainer|null
     */
    public function updateCustomerPassword(CustomerContainer $container)
    {
        $defaultContainer = $this->getById($container->id);
        $newPassword = UserHelper::hashPassword($container->password);
        if ($newPassword != $defaultContainer->password) {
            $container->password = $newPassword;
        }
        $updated = $this->_db->updateAttribute($container->id, "password", $container->password);
        if ($updated) {
            $this->_cache->clearIdByEmail($defaultContainer->privateEmail);
            $this->_cache->clearIdByPhone($defaultContainer->mobilePhone);
            $this->_cache->set($updated);
            return $updated;
        }
    }

    /**
     * @param $attributeValue
     * @param $customerDbMethod
     * @param $cacheKeyCallback
     * @return bool|null|string
     */
    protected function getIdByAttribute($attributeValue, $customerDbMethod, $cacheKeyCallback)
    {
        if (empty($attributeValue)) {
            return null;
        }
        $id = $this->_cache->getCustomerIdByAttribute($cacheKeyCallback, $attributeValue);
        if (!$id) {
            $id = $this->_db->{$customerDbMethod}($attributeValue);
            $this->_cache->setCustomerIdByAttribute($cacheKeyCallback, $attributeValue, $id);
        }

        return $id;
    }

    /**
     * @param $phone
     * @return bool|null|string
     */
    public function getIdByPhone($phone)
    {
        return $this->getIdByAttribute($phone, 'findIdByMobilePhone', 'CacheKey::customerIdByMobilePhone');
    }

    /**
     * @param $email
     * @return bool|null|string
     */
    public function getIdByPrivateEmail($email)
    {
        return $this->getIdByAttribute($email, 'findIdByPrivateEmail', 'CacheKey::customerIdByPrivateEmail');
    }

    /**
     * @param $email
     * @return CustomerContainer
     */
    public function getCustomerByPrivateEmail($email)
    {
        $id = $this->getIdByPrivateEmail($email);
        if ($id) {
            return $this->getById($id, false);
        }
    }

    public function getCustomerFullNameById($id)
    {
        $customer = $this->getById($id);
        if ($customer) {
            return $customer->firstName . " " . $customer->lastName;
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
     * @return CustomerContainer[]
     */
    public function getAllCustomers($showDeleted = true)
    {
        $IDs = $this->getAllCustomerIds();
        return $this->getAllByIds($IDs, $showDeleted);
    }

    /**
     * @return array
     */
    public function getAllCustomerIds()
    {
        $IDs = $this->_cache->getAllCustomerIds();
        if (empty($IDs)) {
            $IDs = $this->_db->findAllIds();
            $this->_cache->setAllCustomerIds($IDs);
        }

        return $IDs;
    }

    /**
     * @param bool $showDeleted
     * @return array
     */
    public function getAllCustomersFullNames($showDeleted = true)
    {
        return CHtml::listData($this->getAllCustomers($showDeleted), 'id', function ($customer) {
            return CHtml::encode($customer->firstName . ' ' . $customer->lastName);
        });
    }

    public function getCurrentCustomer()
    {
        switch (Yii::app()->name) {
            case 'api':
                return $this->getCustomerFromSession();
            default:
                return null;
        }
    }

    private function getCustomerFromSession()
    {
        $customerId = Yii::app()->user->id;
        if ($customerId) {
            return $this->getById($customerId, false);
        }
    }
}