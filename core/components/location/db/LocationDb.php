<?php

use core\components\location\models as Models;

class LocationDb extends BaseDbImplementation
{
    public $containerRules = array(
        'AddressContainer' => array(
            'id' => array('skipOnUpdate' => true, 'skipOnInsert' => true),
            'addressLine1' => array('dbKey' => 'address_line_1', 'purify' => 'purifyText'),
            'addressLine2' => array('dbKey' => 'address_line_2', 'purify' => 'purifyText'),
            'postalCode' => array('dbKey' => 'postal_code', 'purify' => 'purifyText'),
            'city' => array('purify' => 'purifyText'),
            'region' => array('purify' => 'purifyText'),
            'country' => array('purify' => 'purifyText'),
        ),
    );

    public function findAddressById($id)
    {
        return $this->selectContainerByPk($id, 'AddressContainer', new Models\Address());
    }

    public function findAllAddressesByIds($id)
    {
        return $this->selectAllContainersByPk($id, 'AddressContainer', new Models\Address());
    }

    public function createAddress($container)
    {
        return $this->insertContainer($container, new Models\Address());
    }

    public function updateAddress($container)
    {
        return $this->updateContainer($container, new Models\Address());
    }

    public function updateAddressAttribute($addressId, $attribute, $value)
    {
        return $this->updateContainerAttributeById($addressId, 'AddressContainer', new Models\Address(), $attribute, $value);
    }

    public function deleteAddressById($id)
    {
        if (Models\Address::model()->deleteByPk($id)) {
            return true;
        }
    }

}