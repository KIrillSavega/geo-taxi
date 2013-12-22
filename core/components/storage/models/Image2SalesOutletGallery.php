<?php

namespace core\components\storage\models;
class Image2SalesOutletGallery extends \Image2SalesOutletGalleryGii
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return \CMap::mergeArray(parent::rules(), array(
            array('image_uid', 'core.validators.storage.FileExistValidator'),
            array('sales_outlet_id', 'core.validators.salesOutlet.SalesOutletExistValidator'),
        ));
    }
}
