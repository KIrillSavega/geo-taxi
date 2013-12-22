<?php

class CountriesListAction extends BaseApiAction
{
    public function run()
    {
        $result = array();
        $countries = Yii::app()->location->getCountryList();
        foreach ($countries as $key => $value) {
            $country = new Country();
            $country->country = $key;
            $country->name = $value;
            $result[] = $country;
        }
        $this->renderSuccess($result);
    }
}
