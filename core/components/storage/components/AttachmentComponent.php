<?php

class AttachmentComponent extends BaseStorageComponent
{
    /**
     * @var Storage
     */
    protected $storage;

    public function getAttachmentsByProductId($productId)
    {
        $UIDs = $this->storage->getCache()->getAttachmentsByProductId($productId);
        if(empty($UIDs)){
            $UIDs = $this->storage->getDb()->findAttachmentsIdsByProductId($productId);
            $this->storage->getCache()->setAttachmentsByProductId($UIDs, $productId);
        }
        return $this->storage->getAllFilesByUIDs($UIDs);
    }

    public function createAttachmentForProductId($container, $instance, $productId)
    {
        $createdContainer = $this->storage->createStaticFile(PRODUCT_ATTACHMENT_ID, $container, $instance);
        if($createdContainer)
        {
            if ($this->addAttachmentToProductId($createdContainer->uid, $productId)){
                $this->storage->getCache()->deleteAttachmentsForProductId($productId);
                return $createdContainer;
            }
        }
    }

    public function addAttachmentToProductId($fileUid, $productId)
    {
        if ($this->storage->getDb()->addAttachmentToProductId($fileUid, $productId)){
            $this->storage->getCache()->deleteAttachmentsForProductId($productId);
            return true;
        }
    }

    public function removeAttachmentFromProduct($fileUid, $productId)
    {
        if($this->storage->getDb()->deleteAttachmentFromProduct($fileUid, $productId)){
            $this->storage->getCache()->deleteAttachmentsForProductId($productId);
            return true;
        }
    }
}