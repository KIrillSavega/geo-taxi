<?php

namespace core\components\storage\models;
class File extends \FileGii
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
}
