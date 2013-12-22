<?php

class SubscribitionCache extends BaseCacheImplementation
{
    public function getCustomerIdsSubscribedForEvent( $eventId )
    {
        return $this->cache->get( CacheKey::customerIdsBySubscribeEvent($eventId) );
    }
    
    public function deleteCustomerIdsSubscribedForEvent( $eventId )
    {
        return $this->cache->delete( CacheKey::customerIdsBySubscribeEvent($eventId) );
    }
    
    public function setCustomerIdsSubscribedForEvent($eventId, array $ids)
    {
        return $this->cache->set( CacheKey::customerIdsBySubscribeEvent($eventId), $ids );
    }
    
    public function getSubscribitionIdsByCustomerId( $customerId )
    {
        return $this->cache->get( CacheKey::subscribeEventsByCustomerId($customerId) );
    }
    
    public function deleteSubscribitionIdsByCustomerId( $customerId )
    {
        return $this->cache->delete( CacheKey::subscribeEventsByCustomerId($customerId) );
    }
    
    public function setSubscribitionIdsByCustomerId( $customerId, array $ids )
    {
        return $this->cache->set( CacheKey::subscribeEventsByCustomerId($customerId), $ids );
    }
}
