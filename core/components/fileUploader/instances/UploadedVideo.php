<?php

class UploadedVideo extends UploadedFile
{
    public function saveAs($file, $deleteTempFile = true)
    {
        if (!$this->beforeSave())
            return false;

        if ($this->_error == UPLOAD_ERR_OK) {
            $uploaded = false;
            if ($deleteTempFile)
                $uploaded = Yii::app()->fileUploader->moveUploadedFile($this, $file, true);
            else if (!$this->_checkIsUploadedFileOnSave || Yii::app()->fileUploader->isUploadedFile($this))
                $uploaded = Yii::app()->fileUploader->copy($this, $file);
            else
                $uploaded = false;

            if ($uploaded)
                $this->afterSave();

            return $uploaded;
        } else {
            return false;
        }
    }

    public function beforeSave()
    {
        $mimeType = Yii::app()->fileUploader->getMimeType($this->_tempName);
        if (in_array($mimeType, Yii::app()->fileUploader->getAllowedVideoMimeTypes())) {
            return true;
        }

        //BUG: file command reports incorrect mime type for mkv files
        if (($mimeType == 'application/octet-stream') && ($this->_type == 'video/x-matroska')) {
            return true;
        }
    }
}

