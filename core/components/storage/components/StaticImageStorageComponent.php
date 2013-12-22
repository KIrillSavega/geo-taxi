<?php
class StaticImageStorageComponent extends BaseStorageComponent
{
    /**
     * @var Storage
     */
    protected $storage;

    public function createImage($instance)
    {
        $newFilename = $this->storage->getUniqueName();
        $ext = $instance->getExtensionName();
        if ($instance->saveAs($this->storage->getPathById(STATIC_IMAGE) . DIRECTORY_SEPARATOR . $newFilename . '.' . $ext)) {
            $container = new FileContainer();
            $container->uid = $newFilename;
            $container->ext = $ext;
            $container->pathId = STATIC_IMAGE;
            $imagePath = $this->storage->getFilePathFromContainer($container);
            $settings = $this->storage->settings[STATIC_IMAGE];
            try {
                $originalImage = Yii::app()->imageLib->load($imagePath);
                $thumbImagePath = Yii::app()->fileUploader->uploaderOptions['uploadFolder'] . $this->storage->getPathById(STATIC_IMAGE) .
                    DIRECTORY_SEPARATOR . 'thumb_' . $newFilename . '.' . $ext;
                $this->storage->resizeImage($originalImage, $imagePath, $thumbImagePath, $settings);
            } catch(Exception $e) {
                $this->storage->addErrors(array($e->getMessage()));
            }
            return $container->uid.'.'.$ext;
        } else {
            $this->storage->errors = CMap::mergeArray($this->storage->errors, array($instance->getError()));
        }
    }

    public function deleteImage($filename)
    {
        if($filename){
            $path = $this->storage->getBasePath().$this->storage->getPathById(STATIC_IMAGE).'/'.$filename;
            $thumbPath = $this->storage->getBasePath().$this->storage->getPathById(STATIC_IMAGE).'/'.'thumb_'.$filename;
            if($path){
                @unlink($path);
            }
            if($thumbPath){
                @unlink($thumbPath);
            }
            return true;
        }
    }
}