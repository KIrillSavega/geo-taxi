<?php

class ModifierStorageComponent extends BaseStorageComponent
{
    /**
     * @var Storage
     */
    protected $storage;

    public function createImageForModifierId($container, $instance, $modifierId)
    {
        $createdContainer = $this->storage->createImageFile(MODIFIER_IMAGE_ID, $container, $instance);
        if($createdContainer){
            $modifier = Yii::app()->modifier->getById($modifierId);
            $modifier->imageUid = $createdContainer->uid;
            if (Yii::app()->modifier->update($modifier) == true){
                return $createdContainer;
            }
        }
    }

}