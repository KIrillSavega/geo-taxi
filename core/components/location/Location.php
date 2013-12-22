<?php

class Location extends BaseAppComponent
{
    public $countryCodes = array();

    /**
     * object that implements DB operations
     * @var LocationDb
     */
    protected $_db;
    /**
     * object that implements Cache operations
     * @var LocationCache
     */
    protected $_cache;

    public function init()
    {
        parent::init();
        $this->countryCodes = require(dirname(__FILE__) . '/countryCodes.php');
    }

    /**
     * @param $id
     * @return AddressContainer
     */
    public function getAddressById($id)
    {
        return $this->baseGetById($id, array(
            'cacheGetter' => 'getAddressById',
            'cacheSetter' => 'setAddress',
            'dbFinderById' => 'findAddressById'
        ));
    }

    /**
     * @param $IDs
     * @return array
     */
    public function getAllAddressesByIds($IDs)
    {
        return $this->baseGetAllByIds($IDs, array(
            'cacheGetter' => 'getAllAddressesByIds',
            'cacheSetterAll' => 'setAllAddresses',
            'dbFinderAllByIds' => 'findAllAddressesByIds'
        ));
    }

    /**
     * @param AddressContainer $container
     * @return AddressContainer
     */
    public function createAddress(AddressContainer $container)
    {
        $container->country = strtolower($container->country);
        $container = $this->_db->createAddress($container);
        if ($container) {
            $this->_cache->setAddress($container);
        }

        return $container;
    }

    /**
     * @param AddressContainer $container
     * @return AddressContainer
     */
    public function updateAddress(AddressContainer $container)
    {
        $container->country = strtolower($container->country);
        $updatedAddress = $this->_db->updateAddress($container);
        if ($updatedAddress) {
            $this->_cache->setAddress($updatedAddress);
        }

        return $updatedAddress;
    }

    /**
     * @param $attributeValue
     * @param $addressDbMethod
     * @param $cacheKeyCallback
     * @return integer
     */
    protected function getIdByAttribute($attributeValue, $addressDbMethod, $cacheKeyCallback)
    {
        if (empty($attributeValue)) {
            return null;
        }
        $id = $this->_cache->getAddressIdByAttribute($cacheKeyCallback, $attributeValue);
        if (!$id) {
            $id = $this->_db->{$addressDbMethod}($attributeValue);
            $this->_cache->setAddressIdByAttribute($cacheKeyCallback, $attributeValue, $id);
        }

        return $id;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function deleteAddressById($id)
    {
        $address = $this->getAddressById($id);
        if (!isset($address->id)) {
            return false;
        }
        if ($this->_db->deleteAddressById($address->id) == true) {
            $this->_cache->clearAddress($address);
            return true;
        }
    }

    /**
     * @param $countryCode
     * @return mixed
     */
    public function getCountryNameByCode( $countryCode )
    {
        $countryCode = strtolower($countryCode);
        if (isset($this->countryCodes[$countryCode])) {
            return $this->countryCodes[$countryCode];
        }
    }

    /**
     * @param $countryName
     * @return string
     */
    public function getCountryCodeByName( $countryName )
    {
        $countryName = strtolower($countryName);
        foreach ($this->countryCodes as $country_code => $country_name) {
            $country_name = strtolower($country_name);
            if ($country_name == $countryName) {
                return strtolower($country_code);
            }
        }
    }

    /**
     * @return array
     */
    public function getCountryList()
    {
        $countryList = $this->countryCodes;
        asort($countryList);

        return $countryList;
    }

    /**
     * @param $id
     * @return string
     */
    public function getAddressStringById( $id )
    {
        $address = $this->getAddressById( $id );
        if( $address ) {
            return $this->getAddressStringByContainer( $address );
        }
    }

    /**
     * @param AddressContainer $addressContainer
     * @return string
     */
    public function getAddressStringByContainer($addressContainer)
    {
        if ($addressContainer instanceof AddressContainer) {
            $address = $addressContainer->addressLine1;
            $address .= ($addressContainer->addressLine2)? ',' . $addressContainer->addressLine2 : '';
            $address .= ',' . $addressContainer->city;
            $address .= ($addressContainer->region)? ', ' . $addressContainer->region : '';
            $address .= ', ' . $addressContainer->postalCode;
            $address .= ', ' . $this->getCountryNameByCode($addressContainer->country);

            return $address;
        }
    }

    public function getAddressForReportByContainer($addressContainer)
    {
        if ($addressContainer instanceof AddressContainer) {
            $address = $addressContainer->addressLine1;
            $address .= ($addressContainer->addressLine2)? '<br/> ' . $addressContainer->addressLine2 : '';
            $address .= '<br/>' . $addressContainer->city . ', ';
            $address .= ($addressContainer->region) ? ' ' . $addressContainer->region : '';
            $address .= ' ' . $addressContainer->postalCode;

            return $address;
        }
    }
}