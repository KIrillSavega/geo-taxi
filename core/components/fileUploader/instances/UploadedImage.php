<?php

class UploadedImage extends UploadedFile
{
    public function beforeSave()
    {
        $fileInfo = new finfo(FILEINFO_MIME);
        $type = $fileInfo->buffer(file_get_contents($this->_tempName));
        $exploadedType = explode(';', $type);
        $mimeType = !empty($exploadedType) ? $exploadedType[0] : '';
        if (in_array($mimeType, Yii::app()->fileUploader->getAllowedImageMimeTypes())) {
            return true;
        } else {
            $this->_error = UPLOAD_ERR_MIME_TYPE_MISMATCH;
            return false;
        }
    }
}

