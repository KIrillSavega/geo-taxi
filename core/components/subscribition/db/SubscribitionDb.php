<?php
use core\components\subscribition\models as Models;

class SubscribitionDb extends BaseDbImplementation
{
    public function findEmployeeIdsSubscribedForEvent($eventId)
    {
        $result = array();
        $records = Models\EmployeeSubscribe::model()->findAllByAttributes( array('event_id'=>$eventId) );
        foreach($records as $record){
            $result[] = $record->employee_id;
        }
        return $result;
    }
    
    public function findSubscribitionIdsByEmployeeId( $employeeId )
    {
        $result = array();
        $records = Models\EmployeeSubscribe::model()->findAllByAttributes( array('employee_id'=>$employeeId) );
        foreach($records as $record){
            $result[] = $record->event_id;
        }
        return $result;
    }
    
    public function subscribe($employeeId, $eventId)
    {
        $model = new Models\EmployeeSubscribe();
        $model->employee_id = $employeeId;
        $model->event_id = $eventId;
        return $model->save() ? true : false;
    }
    
    public function unsubscribe($employeeId, $eventId)
    {
        return Models\EmployeeSubscribe::model()->deleteByPk(array(
            'employee_id' => $employeeId,
            'event_id' => $eventId,
        ));
    }
}
