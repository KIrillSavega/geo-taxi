<?php

class CustomerCache extends BaseCacheImplementation
{
    /**
     * @var Redis
     */
    public $cache;

    public function getById($id)
    {
        return $this->cache->get(CacheKey::customer($id));
    }

    public function set($customer)
    {
        if ($customer instanceof CustomerContainer) {
            return $this->cache->set(CacheKey::customer($customer->id), $customer);
        }
    }

    public function getAllById($ids)
    {
        $keys = array();
        foreach ($ids as $id) {
            $keys[] = CacheKey::customer($id);
        }

        return $this->cache->mget($keys);
    }

    public function setAll($customers)
    {
        $keys = array();
        foreach ($customers as $customer) {
            if ($customer instanceof CustomerContainer) {
                $keys[CacheKey::customer($customer->id)] = $customer;
            }
        }
        return $this->cache->mset($keys);
    }

    public function getCustomerIdByAttribute($cacheKeyCallback, $attributeValue)
    {
        $cachingKey = $this->getCacheKeyByAttributeCallback($cacheKeyCallback, $attributeValue);
        return $this->cache->get($cachingKey);
    }

    public function getCacheKeyByAttributeCallback($cacheKeyCallback, $id)
    {
        return call_user_func($cacheKeyCallback, $id);
    }

    public function setCustomerIdByAttribute($cacheKeyCallback, $attributeValue, $id)
    {
        if ($attributeValue && $id) {
            $cachingKey = $this->getCacheKeyByAttributeCallback($cacheKeyCallback, $attributeValue);
            $this->cache->set($cachingKey, $id);
        }
    }

    public function setAllCustomerIds($IDs)
    {
        return $this->cache->set(CacheKey::customers(), $IDs);
    }

    public function getAllCustomerIds()
    {
        return $this->cache->get(CacheKey::customers());
    }

    public function clearAllCustomerIds()
    {
        $this->cache->delete(CacheKey::customers());
    }

    public function clearIdByPhone($phone)
    {
        $this->cache->delete(CacheKey::customerIdByMobilePhone($phone));
    }

    public function clearIdByEmail($email)
    {
        $this->cache->delete(CacheKey::customerIdByPrivateEmail($email));
    }
}
