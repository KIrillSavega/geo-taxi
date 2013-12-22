<?php

class SubscribitionCache extends BaseCacheImplementation
{
    public function getEmployeeIdsSubscribedForEvent( $eventId )
    {
        return $this->cache->get( CacheKey::employeeIdsBySubscribeEvent($eventId) );
    }
    
    public function deleteEmployeeIdsSubscribedForEvent( $eventId )
    {
        return $this->cache->delete( CacheKey::employeeIdsBySubscribeEvent($eventId) );
    }
    
    public function setEmployeeIdsSubscribedForEvent($eventId, array $ids)
    {
        return $this->cache->set( CacheKey::employeeIdsBySubscribeEvent($eventId), $ids );
    }
    
    public function getSubscribitionIdsByEmployeeId( $employeeId )
    {
        return $this->cache->get( CacheKey::subscribeEventsByEmployeeId($employeeId) );
    }
    
    public function deleteSubscribitionIdsByEmployeeId( $employeeId )
    {
        return $this->cache->delete( CacheKey::subscribeEventsByEmployeeId($employeeId) );
    }
    
    public function setSubscribitionIdsByEmployeeId( $employeeId, array $ids )
    {
        return $this->cache->set( CacheKey::subscribeEventsByEmployeeId($employeeId), $ids );
    }
}
