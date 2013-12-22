<?php
/*
This class implements file cache
*/

class FileCacheComponent extends CFileCache
{
    public function mget($keys)
    {
        $values = array();
        foreach ($keys as $key) {
            $values[] = $this->getValue($key);
        }
        return $values;
    }

    public function mset($keys)
    {
        foreach ($keys as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    protected function setValue($key, $value, $expire = 0)
    {
        if ($expire <= 0)
            $expire = 31536000; // 1 year
        $expire += time();

        $cacheFile = $this->getCacheFile($key);
        @mkdir(dirname($cacheFile), 0777, true);
        if (@file_put_contents($cacheFile, $value, LOCK_EX) !== false) {
            @chmod($cacheFile, 0777);
            return @touch($cacheFile, $expire);
        } else
            return false;
    }

    public function getCacheFile($key)
    {
        $keySubDirs = explode('::', $key);
        if (count($keySubDirs) > 0) {
            array_pop($keySubDirs);
        }

        return $this->cachePath . DIRECTORY_SEPARATOR .
            implode(DIRECTORY_SEPARATOR, $keySubDirs) .
            DIRECTORY_SEPARATOR . $key . $this->cacheFileSuffix;
    }

    protected function generateUniqueKey($key)
    {
        return $key;
    }

    public function __set($name, $value){}

    public function __get($name){}

}