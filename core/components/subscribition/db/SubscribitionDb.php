<?php
use core\components\subscribition\models as Models;

class SubscribitionDb extends BaseDbImplementation
{
    public function findCustomerIdsSubscribedForEvent($eventId)
    {
        $result = array();
        $records = Models\CustomerSubscribe::model()->findAllByAttributes( array('event_id'=>$eventId) );
        foreach($records as $record){
            $result[] = $record->customer_id;
        }
        return $result;
    }
    
    public function findSubscribitionIdsByCustomerId( $customerId )
    {
        $result = array();
        $records = Models\CustomerSubscribe::model()->findAllByAttributes( array('customer_id'=>$customerId) );
        foreach($records as $record){
            $result[] = $record->event_id;
        }
        return $result;
    }
    
    public function subscribe($customerId, $eventId)
    {
        $model = new Models\CustomerSubscribe();
        $model->customer_id = $customerId;
        $model->event_id = $eventId;
        return $model->save() ? true : false;
    }
    
    public function unsubscribe($customerId, $eventId)
    {
        return Models\CustomerSubscribe::model()->deleteByPk(array(
            'customer_id' => $customerId,
            'event_id' => $eventId,
        ));
    }
}
