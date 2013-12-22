<?php

class UploadedByUrlFile extends BaseUploadedFile
{
    public function __construct($url)
    {
        $url = $this->resolveUrl($url);
        $this->_checkIsUploadedFileOnSave = false;
        $this->_name = $this->getFileNameByUrl($url);
        $extension = empty($extension) ? 'png' : $extension;
        $this->_tempName = Yii::app()->fileUploader->tmpDir . DIRECTORY_SEPARATOR . uniqid() . '.' . $this->getExtensionName();
        $ch = curl_init($url);
        $fp = fopen($this->_tempName, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $this->_error = UPLOAD_ERR_OK;
        if (!file_exists($this->_tempName)) {
            $this->_error = UPLOAD_ERR_NO_FILE;
        } else {
            $this->_size = filesize($this->_tempName);
            if (!$this->_size) {
                $this->_error = UPLOAD_ERR_PARTIAL;
            }
        }
    }

    protected function resolveUrl($url)
    {
        return strtok($url, '?');
    }

    public function getFileNameByUrl($url)
    {
        $exploaded = explode('/', $url);
        return $exploaded[count($exploaded) - 1];
    }

}
