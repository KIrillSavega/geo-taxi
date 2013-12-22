<?php

class StorageCache extends BaseCacheImplementation
{
    /**
     * @var Redis
     */
    public $cache;

    public function getFileByUid($uid)
    {
        return $this->cache->get(CacheKey::storageFile($uid));
    }

    public function setFile($file)
    {
        if ($file instanceof FileContainer) {
            return $this->cache->set(CacheKey::storageFile($file->uid), $file);
        }
    }

    public function getAllFilesByUIDs($UIDs)
    {
        $keys = array();
        foreach ($UIDs as $uid) {
            $keys[] = CacheKey::storageFile($uid);
        }

        return $this->cache->mget($keys);
    }

    public function setAllFiles($files)
    {
        $keys = array();
        foreach ($files as $file) {
            if ($file instanceof FileContainer) {
                $keys[CacheKey::storageFile($file->uid)] = $file;
            }
        }

        return $this->cache->mset($keys);
    }

    public function deleteFile($uid)
    {
        $this->cache->delete(CacheKey::storageFile($uid));
    }

    public function getImagesByProductId($id)
    {
        return $this->cache->get(CacheKey::productImages($id));
    }

    public function setImagesByProductId($UIDs, $id)
    {
        $this->cache->set(CacheKey::productImages($id), $UIDs);
    }

    public function deleteImagesForProductId($id)
    {
        $this->cache->delete(CacheKey::productImages($id));
    }

    public function getAttachmentsByProductId($id)
    {
        return $this->cache->get(CacheKey::productAttachments($id));
    }

    public function setAttachmentsByProductId($UIDs, $id)
    {
        $this->cache->set(CacheKey::productAttachments($id), $UIDs);
    }

    public function deleteAttachmentsForProductId($id)
    {
        $this->cache->delete(CacheKey::productAttachments($id));
    }
    
    public function getGalleryImagesBySalesOutletId($id)
    {
        return $this->cache->get(CacheKey::salesOutletGalleryImages($id));
    }

    public function setGalleryImagesForSalesOutlet($id, $UIDs)
    {
        $this->cache->set(CacheKey::salesOutletGalleryImages($id), $UIDs);
    }

    public function deleteGalleryImagesForSalesOutlet($id)
    {
        $this->cache->delete(CacheKey::salesOutletGalleryImages($id));
    }
}