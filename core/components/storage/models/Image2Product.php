<?php

namespace core\components\storage\models;
class Image2Product extends \Image2ProductGii
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function afterDelete()
    {
        parent::afterDelete();
    }

    public function afterSave()
    {
        parent::afterSave();
    }

    public function rules()
    {
        return \CMap::mergeArray(parent::rules(), array(
            array('product_id', 'core.validators.products.ProductExistValidator'),
            array('product_id, order', 'numerical'),
            array('image_uid', 'core.validators.storage.FileExistValidator'),
        ));
    }
}
