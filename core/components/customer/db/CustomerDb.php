<?php
use core\components\customer\models as Models;

class CustomerDb extends BaseDbImplementation
{
    public $containerRules = array(
        'CustomerContainer' => array(
            'id' => array('skipOnUpdate' => true, 'skipOnInsert' => true),
            'firstName' => array('dbKey' => 'first_name', 'purify' => 'purifyText'),
            'lastName' => array('dbKey' => 'last_name', 'purify' => 'purifyText'),
            'privateEmail' => array('dbKey' => 'private_email', 'purify' => 'purifyText'),
            'mobilePhone' => array('dbKey' => 'mobile_phone', 'purify' => 'purifyText'),
            'password' => array('skipOnUpdate' => true, 'skipOnInsert' => false, 'purify' => 'purifyText'),
        ),
    );

    public $containerToModel = array(
        'CustomerContainer' => 'core\components\customer\models\Customer',
    );

    public function findById($id)
    {
        return $this->selectContainerByPk($id, 'CustomerContainer', new Models\Customer());
    }

    public function findIdByPrivateEmail($email)
    {
        $customer = Models\Customer::model()->findByAttributes(array('private_email' => $email));
        return $customer ? $customer->id : null;
    }

    public function findIdByPinCode($pinCode)
    {
        $customer = Models\Customer::model()->findByAttributes(array('pos_pin_code' => $pinCode));
        return $customer ? $customer->id : null;
    }
    
    public function findIdByCompanyEmail($email)
    {
        $customer = Models\Customer::model()->findByAttributes(array('company_email' => $email));
        return $customer ? $customer->id : null;
    }

    public function findIdByMobilePhone($mobilePhone)
    {
        $customer = Models\Customer::model()->findByAttributes(array('mobile_phone' => $mobilePhone));
        return $customer ? $customer->id : null;
    }

    public function findAllByIds($id)
    {
        return $this->selectAllContainersByPk($id, 'CustomerContainer', new Models\Customer());
    }

    public function create($container)
    {
        return $this->insertContainer($container, new Models\Customer());
    }

    public function update($container)
    {
        return $this->updateContainer($container, new Models\Customer());
    }

    public function updateAttribute($customerId, $attribute, $value)
    {
        return $this->updateContainerAttributeById($customerId, 'CustomerContainer', new Models\Customer(), $attribute, $value);
    }

    protected function convertResultToContainersArray($result)
    {
        $containers = array();
        foreach($result as $node){
            $containers[] = $this->getConverter('CustomerContainer')->convertArrayToContainer($node);
        }

        return $containers;
    }

    public function findAllIds()
    {
        $result = array();

        $dbConnection = Models\Customer::model()->getDbConnection();
        $resultDb = $dbConnection->createCommand()
            ->select('id')
            ->from('customer')
            ->queryAll();

        if (is_array($resultDb)) {
            foreach ($resultDb as $row) {
                $result[] = $row['id'];
            }
        }
        return $result;
    }

    public function updatePassword( $customer )
    {

    }
}
