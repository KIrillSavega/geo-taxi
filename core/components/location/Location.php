<?php

class Location extends CApplicationComponent
{
    public $countryCodes = array();

    /**
     * object that implements DB operations
     * @var db
     */
    protected $_db;

    public function init()
    {
        parent::init();
        $this->countryCodes = require(dirname(__FILE__) . '/countryCodes.php');
    }

    public function getById($id)
    {
        return $this->_db->findById($id);
    }

    public function getAllByIds($ids)
    {
        return $this->_db->findAllByIds($ids);
    }

    public function create($model)
    {
        return $this->_db->create($model);
    }

    public function update($model)
    {
        return $this->_db->update($model);
    }

    public function getCountryNameByCode($countryCode)
    {
        $countryCode = strtolower($countryCode);
        if (isset($this->countryCodes[$countryCode])) {
            return $this->countryCodes[$countryCode];
        }
    }

    public function getCountryCodeByName($countryName)
    {
        $countryName = strtolower($countryName);
        foreach ($this->countryCodes as $country_code => $country_name) {
            $country_name = strtolower($country_name);
            if ($country_name == $countryName) {
                return strtolower($country_code);
            }
        }
    }

    public function getCountryList()
    {
        $countryList = $this->countryCodes;
        asort($countryList);

        return $countryList;
    }

    public function getAddressStringById( $id )
    {
        $address = $this->getById( $id );
        if( $address ) {
            return $this->getAddressStringByModel( $address );
        }
    }

    public function getAddressStringByContainer($address)
    {
        $addressString = $address->addressLine1;
        $addressString .= ($address->addressLine2)? ',' . $address->addressLine2 : '';
        $addressString .= ',' . $address->city;
        $addressString .= ($address->region)? ', ' . $address->region : '';
        $addressString .= ', ' . $address->postalCode;
        $addressString .= ', ' . $this->getCountryNameByCode($address->country);

        return $addressString;
    }
}