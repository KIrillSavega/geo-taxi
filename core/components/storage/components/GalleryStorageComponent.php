<?php

class GalleryStorageComponent extends BaseStorageComponent
{
    /**
     * @var Storage
     */
    protected $storage;
    
    public function createImageForSalesOutlet($container, $instance, $salesOutletId)
    {
        $createdContainer = $this->storage->createImageFile(GALLERY_IMAGE_ID, $container, $instance);
        if ($this->addGalleryImageToSalesOutlet($createdContainer->uid, $salesOutletId)){
            return $createdContainer;
        }
    }

    public function addGalleryImageToSalesOutlet($imageUid, $salesOutletId, $order = 0)
    {
        if ($this->storage->getDb()->addGalleryImageToSalesOutlet($imageUid, $salesOutletId, $order)){
            $this->storage->getCache()->deleteGalleryImagesForSalesOutlet($salesOutletId);
            return true;
        }
    }

    public function removeImageFromSalesOutlet($imageUid, $salesOutletId)
    {
        if ($this->storage->getDb()->removeGalleryImageFromSalesOutlet($imageUid, $salesOutletId)){
            $this->storage->deleteFile($imageUid);
            $this->storage->getCache()->deleteGalleryImagesForSalesOutlet($salesOutletId);
            return true;
        }
    }

    public function changeImagesOrderForSalesOutlet($imageUIDsArray, $salesOutletId)
    {
        if(is_array($imageUIDsArray)){
            foreach($imageUIDsArray as $order => $imageUid){
                $result = $this->storage->getDb()->updateGalleryImageToSalesOutlet($imageUid, $salesOutletId, $order);
                if($result != true){
                    return $result;
                }
            }
            $this->storage->getCache()->deleteGalleryImagesForSalesOutlet($salesOutletId);
            return true;
        }
    }

    public function getGalleryImagesBySalesOutletId($id)
    {
        $UIDs = $this->storage->getCache()->getGalleryImagesBySalesOutletId($id);
        if (empty($UIDs)) {
            $UIDs = $this->storage->getDb()->findImagesUIDsBySalesOutletId($id);
            if($UIDs){
                $this->storage->getCache()->setGalleryImagesForSalesOutlet($id, $UIDs);
            }
        }

        return $this->storage->getAllFilesByUIDs($UIDs);
    }

    public function getGalleryImagesUrlsBySalesOutlet($id)
    {
        $containers = $this->getGalleryImagesBySalesOutletId($id);
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

    public function getFirstThumbImageUrlBySalesOutlet($id)
    {
        $images = $this->getGalleryImagesUrlsBySalesOutlet($id);
        if($images){
            return $images[0]['thumb'];
        }
        return null;
    }
}