<?php
/**
 * Created by Anton Logvinenko.
 * email: a.logvinenko@mobidev.biz
 * Date: 4/8/13
 * Time: 1:00 PM
 */

Yii::import('core.components.storage.components.*');

class Storage extends BaseAppComponent
{
    public $settings;

    /**
     * object that implements DB operations
     * @var StorageDb
     */
    protected $_db;
    /**
     * object that implements Cache operations
     * @var StorageCache
     */
    protected $_cache;


    /**
     * @var array
     */
    public $errors = array();

    public function getBaseUrl()
    {
        return Yii::app()->fileUploader->fileStorageUrl;
    }

    public function getBasePath()
    {
        return Yii::app()->fileUploader->uploaderOptions['uploadFolder'];
    }

    /**
     * return object that implements DB operations
     * @object StorageDb
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * return object that implements Cache operations
     * @object StorageCache
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     *
     * @return array of errors
     */
    public function getErrors()
    {
        return CMap::mergeArray($this->errors,  $this->_db->getErrors() );
    }

    /**
     * This function clears validators errors
     */
    public function clearErrors()
    {
        $this->errors = array();
        $this->_db->clearErrors();
    }

    public function getPathById( $pathId )
    {
        return $this->settings[$pathId]['path'];
    }

    /**
     * @param $id
     * @return FileContainer
     */
    public function getFileByUid($uid)
    {
        return $this->baseGetById($uid, array(
            'cacheGetter' => 'getFileByUid',
            'cacheSetter' => 'setFile',
            'dbFinderById' => 'findFileByUid'
        ));
    }

    /**
     * @param $UIDs
     * @return array
     */
    public function getAllFilesByUIDs($UIDs)
    {
        return $this->baseGetAllByIds($UIDs, array(
            'cacheGetter' => 'getAllFilesByUIDs',
            'cacheSetterAll' => 'setAllFiles',
            'dbFinderAllByIds' => 'findAllFilesByUIDs',
            'primaryKey' => 'uid'
        ));
    }

    /**
     * @param FileContainer $container
     * @return FileContainer
     */
    public function createFile(FileContainer $container)
    {
        $container->created = time();
        $createdContainer = $this->_db->createFile($container);
        if ($createdContainer) {
            $this->_cache->setFile($createdContainer);
        }

        return $createdContainer;
    }

    /**
     * @param FileContainer $container
     * @return FileContainer
     */
    public function updateFile(FileContainer $container)
    {
        $updated = $this->_db->updateFile($container);
        if ($updated) {
            $this->_cache->setFile($updated);
        }

        return $updated;
    }

    public function deleteFile($uid)
    {
        $file = $this->getFileByUid($uid);
        if($file){
            $path = $this->getFilePathFromContainer($file);
            $thumbPath = $this->getThumbFilePathFromContainer($file);
            if($path){
                @unlink($path);
            }
            if($thumbPath){
                @unlink($thumbPath);
            }
        }
        if ( $this->_db->deleteFileByUid( $uid ) ){
            $this->_cache->deleteFile($uid);
            return true;
        }
    }

    public function getUniqueName()
    {
        $solt = 'All you need is love';
        return hash_hmac( 'md5', uniqid(rand(),1), $solt );
    }

    public function getThumbImageUrlFromContainer($container)
    {
        if ($container instanceof FileContainer) {
            $path = $this->getPathById($container->pathId);
            return  Yii::app()->fileUploader->getUrlByPath($path . DIRECTORY_SEPARATOR . 'thumb_'.$container->uid . '.' . $container->ext);
        }
    }

    public function getFileUrlFromContainer($container)
    {
        if ($container instanceof FileContainer) {
            $path = $this->getPathById($container->pathId);
            return  Yii::app()->fileUploader->getUrlByPath($path . DIRECTORY_SEPARATOR . $container->uid . '.' . $container->ext);
        }
    }

    public function getFilePathFromContainer($container)
    {
        if ($container instanceof FileContainer) {
            $path = $this->getPathById($container->pathId);
            return Yii::app()->fileUploader->uploaderOptions['uploadFolder'] . $path .
                DIRECTORY_SEPARATOR . $container->uid . '.' . $container->ext;
        }
    }

    public function getThumbFilePathFromContainer($container)
    {
        if ($container instanceof FileContainer) {
            $path = $this->getPathById($container->pathId);
            return Yii::app()->fileUploader->uploaderOptions['uploadFolder'] . $path .
            DIRECTORY_SEPARATOR . 'thumb_'.$container->uid . '.' . $container->ext;
        }
    }

    public function createStaticFile($fileTypeId, $container, $instance)
    {
        $newFilename = $this->getUniqueName();
        $ext = $instance->getExtensionName();
        if ($instance->saveAs($this->getPathById($fileTypeId) . DIRECTORY_SEPARATOR . $newFilename . '.' . $ext)) {
            $container->uid = $newFilename;
            $container->ext = $ext;
            $container->pathId = $fileTypeId;
            return $this->createFile($container);
        } else {
            $this->errors = CMap::mergeArray($this->errors, array($instance->getError()));
        }
    }

    public function createImageFile($imageTypeId, $container, $instance)
    {
        $newFilename = $this->getUniqueName();
        $ext = $instance->getExtensionName();
        if ($instance->saveAs($this->getPathById($imageTypeId) . DIRECTORY_SEPARATOR . $newFilename . '.' . $ext)) {
            $container->uid = $newFilename;
            $container->ext = $ext;
            $container->pathId = $imageTypeId;
            $imagePath = $this->getFilePathFromContainer($container);
            $settings = $this->settings[$imageTypeId];
            try {
                $originalImage = Yii::app()->imageLib->load($imagePath);
                $thumbImagePath = Yii::app()->fileUploader->uploaderOptions['uploadFolder'] . $this->getPathById($imageTypeId) .
                DIRECTORY_SEPARATOR . 'thumb_' . $newFilename . '.' . $ext;
                 $this->resizeImage($originalImage, $imagePath, $thumbImagePath, $settings);
                return $this->createFile($container);
            } catch(Exception $e) {
                $this->addErrors(array($e->getMessage()));
            }

        } else {
            $this->errors = CMap::mergeArray($this->errors, array($instance->getError()));
        }

    }

    public function resizeImage($image, $imagePath, $thumbImagePath, $settings)
    {
        $width = isset($settings['width']) ? $settings['width'] : null;
        $height = isset($settings['height']) ? $settings['height'] : null;
        $thumbWidth = isset($settings['thumb']['width']) ? $settings['thumb']['width'] : null;
        $thumbHeight = isset($settings['thumb']['height']) ? $settings['thumb']['height'] : null;

        list($currentWidth, $currentHeight, $currentType, $currentAttr) = getimagesize($imagePath);

        if ($thumbWidth && $thumbHeight) {
            $imageThumb = $image;
            if (($currentWidth > $thumbWidth) || ($currentHeight > $thumbHeight)) {
                $imageThumb->resize($thumbWidth, NULL);
            }
            $imageThumb->save($thumbImagePath);
        }

        if ($width && $height) {
            if (($currentWidth > $width) || ($currentHeight > $height)){
                $image->resize($width, $height, Image::AUTO);
                $image->save();
            }
        }
    }


    /**
     * @return ModifierStorageComponent
     */
    public function modifier()
    {
        return new ModifierStorageComponent($this);
    }

    /**
     * @return ProductsStorageComponent
     */
    public function products()
    {
        return new ProductsStorageComponent($this);
    }

    /**
     * @return AttachmentComponent
     */
    public function attachment()
    {
        return new AttachmentComponent($this);
    }
    
    /**
     * @return GalleryStorageComponent
     */
    public function gallery()
    {
        return new GalleryStorageComponent($this);
    }

    /**
     * @return StaticImageStorageComponent
     */
    public function staticImage()
    {
        return new StaticImageStorageComponent($this);
    }
    
    public function getVersionParameter()
    {
        return '?v='.Yii::app()->params->storageStaticFilesVersion;
    }
}