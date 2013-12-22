<?php

class LocationCache extends BaseCacheImplementation
{
    /**
     * @var Redis
     */
    public $cache;

    public function getAddressById($id)
    {
        return $this->cache->get(CacheKey::address($id));
    }

    public function setAddress($address)
    {
        if ($address instanceof AddressContainer) {
            $this->cache->set(CacheKey::address($address->id), $address);

            return true;
        }
    }

    public function getAllAddressesByIds($ids)
    {
        $keys = array();
        foreach ($ids as $id) {
            $keys[] = CacheKey::address($id);
        }

        return $this->cache->mget($keys);
    }

    public function setAllAddresses($addresses)
    {
        $keys = array();
        foreach ($addresses as $address) {
            if ($address instanceof AddressContainer) {
                $keys[CacheKey::address($address->id)] = $address;
            }
        }

        return $this->cache->mset($keys);
    }

    public function getAddressIdByAttribute($cacheKeyCallback, $attributeValue)
    {
        $cachingKey = $this->getCacheKeyByAttributeCallback($cacheKeyCallback, $attributeValue);
        return $this->cache->get($cachingKey);
    }

    public function getCacheKeyByAttributeCallback($cacheKeyCallback, $id)
    {
        return call_user_func($cacheKeyCallback, $id);
    }

    public function setAddressIdByAttribute($cacheKeyCallback, $attributeValue, $id)
    {
        if ($attributeValue && $id) {
            $cachingKey = $this->getCacheKeyByAttributeCallback($cacheKeyCallback, $attributeValue);
            $this->cache->set($cachingKey, $id);
        }
    }

    public function clearAddress(AddressContainer $container)
    {
        $this->cache->delete(CacheKey::address($container->id));
    }

}