<?php

class ProductsStorageComponent extends BaseStorageComponent
{
    /**
     * @var Storage
     */
    protected $storage;
    
    public function createImageForProductId($container, $instance, $productId)
    {
        $createdContainer = $this->storage->createImageFile(PRODUCT_IMAGE_ID, $container, $instance);
        if ($createdContainer && $this->addImageToProduct($createdContainer->uid, $productId)){
            Yii::app()->sync->reniewTimestampForCollection("products");
            return $createdContainer;
        } 
    }

    public function addImageToProduct($imageUid, $productId, $order = 0)
    {
        if ($this->storage->getDb()->addImageToProduct($imageUid, $productId, $order)){
            $this->storage->getCache()->deleteImagesForProductId($productId);
            Yii::app()->sync->reniewTimestampForCollection("products");
            return true;
        }
    }

    public function removeImageFromProduct($imageUid, $productId)
    {
        if ($this->storage->getDb()->removeImageFromProduct($imageUid, $productId)){
            if (!$this->storage->getDb()->isImageUsedForProducts($imageUid)) {
                $this->storage->deleteFile($imageUid);
            }
            $this->storage->getCache()->deleteImagesForProductId($productId);
            Yii::app()->sync->reniewTimestampForCollection("products");
            return true;
        }
    }

    public function changeImagesOrderForProduct($imageUIDsArray, $productId)
    {
        if(is_array($imageUIDsArray)){
            foreach($imageUIDsArray as $order => $imageUid){
                $result = $this->storage->getDb()->updateImageToProduct($imageUid, $productId, $order);
                if($result != true){
                    return $result;
                }
            }
            Yii::app()->sync->reniewTimestampForCollection("products");
            $this->storage->getCache()->deleteImagesForProductId($productId);
            return true;
        }
    }

    public function getImagesByProductId($id)
    {
        $UIDs = $this->getImagesUIDsByProductId($id);
        return $this->storage->getAllFilesByUIDs($UIDs);
    }

    public function getImagesUIDsByProductId($id)
    {
        $UIDs = $this->storage->getCache()->getImagesByProductId($id);
        if (empty($UIDs)) {
            $UIDs = $this->storage->getDb()->findImagesUIDsByProductId($id);
        }
        return $UIDs;
    }

    public function getImagesUrlsByProductId($id)
    {
        $containers = $this->getImagesByProductId($id);
        $urls = array();
        if(!empty($containers)){
            foreach($containers as $container){
                $urls[] = array(
                    'full' => $this->storage->getFileUrlFromContainer($container),
                    'thumb' => $this->storage->getThumbImageUrlFromContainer($container)
                );
            }
        }
        return $urls;
    }

    public function getFirstThumbImageUrlByProductId($id)
    {
        $images = $this->getImagesUrlsByProductId($id);
        if($images){
            return $images[0]['thumb'];
        }
        return null;
    }

    public function duplicateImage2ProductRecords($productIdFrom, array $productsIdsTo)
    {
        $UIDsToDuplicate = $this->getImagesUIDsByProductId($productIdFrom);
        foreach ($productsIdsTo as $productIdTo) {
            $order = 0;
            foreach ($UIDsToDuplicate as $UID) {
                $this->addImageToProduct($UID, $productIdTo, $order);
                $order++;
            }
        }
    }

}