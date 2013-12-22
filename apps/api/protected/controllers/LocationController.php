<?php

class LocationController extends BaseApiController
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('deny',
                'users'=>array('?'),
                'actions'=>array('countrylist'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'countrylist' => 'application.controllers.location.CountriesListAction',
        );
    }
}