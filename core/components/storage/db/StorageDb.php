<?php
use core\components\storage\models as Models;

class StorageDb extends BaseDbImplementation
{
    public $containerRules = array(
        'FileContainer' => array(
            'uid' => array('skipOnUpdate' => true),
            'pathId' => array('dbKey' => 'path_id'),
            'title' => array('purify' => 'purifyText'),
            'description' => array('purify' => 'purifyText'),
        )
    );

    public function findFileByUid($uid)
    {
        return $this->selectContainerByPk($uid, 'FileContainer', new Models\File());
    }

    public function findAllFilesByUIDs($UIDs)
    {
        return $this->selectAllContainersByPk($UIDs, 'FileContainer', new Models\File());
    }

    public function createFile($container)
    {
        return $this->insertContainer($container, new Models\File());
    }

    public function updateFile($container)
    {
        $containerClass = get_class($container);
        if (!empty($container->uid)) {
            $ar = new Models\File();
            $model = $ar->findByPk($container->uid);
            if (!$model) {
                return null;
            } else {
                $updateAttributes = $this->getConverter($containerClass)
                    ->convertContainerToModelAttributes($container, ModelContainerConverter::UPDATE_SCENARIO);
                $model->setAttributes($updateAttributes, false);
                if ($model->save()) {
                    return $this->getConverter($containerClass)->convertModelToContainer($model);
                } else {
                    $this->addErrors($model->getErrors(), $containerClass);
                    return null;
                }
            }
        } else {
            throw new CException($containerClass . '.uid field was not set before update');
        }
    }

    public function deleteFileByUid($uid)
    {
        if(Models\File::model()->deleteByPk($uid)){
            return true;
        }
    }

    public function findImagesUIDsByProductId($id)
    {
        $dbConnection = Models\Image2Product::model()->getDbConnection();
        $result = $dbConnection->createCommand()
            ->select('image_uid')
            ->from('image2product')
            ->where('product_id = :product_id', array(':product_id'=>$id))
            ->order('order')
            ->queryAll();

        $UIDs = array();
        if(is_array($result)){
            foreach($result as $modifier){
                $UIDs[] = $modifier['image_uid'];
            }
        }

        return $UIDs;
    }

    public function addImageToProduct($imageUid, $productId, $order = 0)
    {
        $model = new Models\Image2Product();
        $model->image_uid = $imageUid;
        $model->product_id = $productId;
        $model->order = $order;
        if ($model->save()){
            return true;
        } else {
            $this->errors = CMap::mergeArray($this->errors, $model->getErrors());
            return null;
        }
    }

    public function removeImageFromProduct($imageUid, $productId)
    {
        $delete = Models\Image2Product::model()->deleteAll(
            'image_uid = :image_uid AND product_id = :product_id',
            array(
                ':image_uid' => $imageUid,
                ':product_id' => $productId
            ));
        if ($delete > 0) {
            return true;
        }
    }

    public function isImageUsedForProducts($imageUid)
    {
        return Models\Image2Product::model()->exists('image_uid = :image_uid', array(':image_uid'=>$imageUid));
    }

    public function updateImageToProduct($imageUid, $productId, $order)
    {
        $model = Models\Image2Product::model()->findByPk(array('image_uid'=>$imageUid, 'product_id'=>$productId));
        $model->order = $order;
        if ($model->save()){
            return true;
        } else {
            $this->errors = CMap::mergeArray($this->errors, $model->getErrors());
            return null;
        }
    }

    public function findAttachmentsIdsByProductId($productId)
    {
        $dbConnection = Models\Attachment2Product::model()->getDbConnection();
        $result = $dbConnection->createCommand()
            ->select('file_uid')
            ->from('attachment2product')
            ->where('product_id = :product_id', array(':product_id'=>$productId))
            ->queryAll();

        $ids = array();
        if(is_array($result)){
            foreach($result as $file){
                $ids[] = $file['file_uid'];
            }
        }

        return $ids;
    }

    public function addAttachmentToProductId($fileUid, $productId)
    {
        $attachment = new Models\Attachment2Product();
        $attachment->file_uid = $fileUid;
        $attachment->product_id = $productId;
        if($attachment->save()){
            return true;
        }else{
            $this->errors = CMap::mergeArray($this->errors, $attachment->getErrors());
            return null;
        }
    }

    public function deleteAttachmentFromProduct($fileUid, $productId)
    {
        if(Models\Attachment2Product::model()->deleteByPk(array('file_uid'=>$fileUid, 'product_id'=>$productId))){
            return true;
        }
    }
    
    public function addGalleryImageToSalesOutlet($imageUid, $salesOutletId, $order = 0)
    {
        $model = new Models\Image2SalesOutletGallery();
        $model->image_uid = $imageUid;
        $model->sales_outlet_id = $salesOutletId;
        $model->order = $order;
        if ($model->save()){
            return true;
        } else {
            $this->errors = CMap::mergeArray($this->errors, $model->getErrors());
            return null;
        }
    }
    
    public function removeGalleryImageFromSalesOutlet($imageUid, $salesOutletId)
    {
        $delete = Models\Image2SalesOutletGallery::model()->deleteAll(
            'image_uid = :image_uid AND sales_outlet_id = :sales_outlet_id',
            array(
                ':image_uid' => $imageUid,
                ':sales_outlet_id' => $salesOutletId
            ));
        if ($delete > 0) {
            return true;
        }
    }
    
    public function updateGalleryImageToSalesOutlet($imageUid, $salesOutletId, $order)
    {
        $model = Models\Image2SalesOutletGallery::model()->findByPk(array('image_uid'=>$imageUid, 'sales_outlet_id'=>$salesOutletId));
        $model->order = $order;
        if ($model->save()){
            return true;
        } else {
            $this->errors = CMap::mergeArray($this->errors, $model->getErrors());
            return null;
        }
    }

    public function findImagesUIDsBySalesOutletId($id)
    {
        $dbConnection = Models\Image2SalesOutletGallery::model()->getDbConnection();
        $result = $dbConnection->createCommand()
            ->select('image_uid')
            ->from('image2sales_outlet_gallery')
            ->where('sales_outlet_id = :sales_outlet_id', array(':sales_outlet_id'=>$id))
            ->order('order')
            ->queryAll();

        $UIDs = array();
        if(is_array($result)){
            foreach($result as $modifier){
                $UIDs[] = $modifier['image_uid'];
            }
        }

        return $UIDs;
    }

}