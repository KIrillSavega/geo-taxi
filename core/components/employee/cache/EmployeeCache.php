<?php

class EmployeeCache extends BaseCacheImplementation
{
    /**
     * @var Redis
     */
    public $cache;

    public function getById($id)
    {
        return $this->cache->get(CacheKey::employee($id));
    }

    public function set($employee)
    {
        if ($employee instanceof EmployeeContainer) {
            return $this->cache->set(CacheKey::employee($employee->id), $employee);
        }
    }

    public function getAllById($ids)
    {
        $keys = array();
        foreach ($ids as $id) {
            $keys[] = CacheKey::employee($id);
        }

        return $this->cache->mget($keys);
    }

    public function setAll($employees)
    {
        $keys = array();
        foreach ($employees as $employee) {
            if ($employee instanceof EmployeeContainer) {
                $keys[CacheKey::employee($employee->id)] = $employee;
            }
        }
        return $this->cache->mset($keys);
    }

    public function getEmployeeIdByAttribute($cacheKeyCallback, $attributeValue)
    {
        $cachingKey = $this->getCacheKeyByAttributeCallback($cacheKeyCallback, $attributeValue);
        return $this->cache->get($cachingKey);
    }

    public function getCacheKeyByAttributeCallback($cacheKeyCallback, $id)
    {
        return call_user_func($cacheKeyCallback, $id);
    }

    public function setEmployeeIdByAttribute($cacheKeyCallback, $attributeValue, $id)
    {
        if ($attributeValue && $id) {
            $cachingKey = $this->getCacheKeyByAttributeCallback($cacheKeyCallback, $attributeValue);
            $this->cache->set($cachingKey, $id);
        }
    }

    public function getEmployeePermissionGroupById($id)
    {
        return $this->cache->get(CacheKey::employeePermissionGroup($id));
    }

    public function setEmployeePermissionGroups($containers)
    {
        $keys = array();
        foreach ($containers as $container) {
            if ($container instanceof EmployeePermissionGroupContainer) {
                $keys[CacheKey::employeePermissionGroup($container->id)] = $container;
            }
        }
        return $this->cache->mset($keys);
    }

    public function setEmployeePermissionGroup($employeePermissionGroup)
    {
        if ($employeePermissionGroup instanceof EmployeePermissionGroupContainer) {
            return $this->cache->set(CacheKey::employeePermissionGroup($employeePermissionGroup->id), $employeePermissionGroup);
        }
    }

    public function getEmployeePermissionGroups()
    {
        return $this->cache->get(CacheKey::employeePermissionGroups());
    }

    public function deleteEmployeePermissionGroups()
    {
        $this->cache->delete(CacheKey::employeePermissionGroups());
    }

    public function clearEmployeePermissionGroup($employeePermissionGroup)
    {
        $this->cache->delete(CacheKey::employeePermissionGroup($employeePermissionGroup->id));
    }

    public function setAllEmployeeIds($IDs)
    {
        return $this->cache->set(CacheKey::employees(), $IDs);
    }

    public function getAllEmployeeIds()
    {
        return $this->cache->get(CacheKey::employees());
    }

    public function clearAllEmployeeIds()
    {
        $this->cache->delete(CacheKey::employees());
    }

    public function clearIdByPhone( $phone )
    {
        $this->cache->delete( CacheKey::employeeIdByMobilePhone( $phone ) );
    }

    public function clearIdByEmail( $email )
    {
        $this->cache->delete( CacheKey::employeeIdByCompanyEmail( $email ) );
    }

    public function clearIdByPinCode( $pinCode )
    {
        $this->cache->delete( CacheKey::employeeIdByPinCode( $pinCode ) );
    }
}
