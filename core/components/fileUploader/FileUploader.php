<?php
define('UPLOAD_ERR_MIME_TYPE_MISMATCH', -1);

interface iFileUploader
{
    public function moveUploadedFile(BaseUploadedFile $file, $destination);

    public function isUploadedFile(BaseUploadedFile $file);

    public function copy(BaseUploadedFile $file, $destination);
}

class FileUploader extends CApplicationComponent
{
    public $uploaderClassName = 'LocalFilesystemUploader';
    public $uploaderOptions = array();
    public $allowedImageMimeTypes = array(
        'image/png',
        'image/jpeg',
        'image/jpg',
    );
    public $allowedVideoMimeTypes = array(
        'video/quicktime',
        'video/mp4',
        'video/mpeg',
        'video/x-m4v',
        'video/x-msvideo',
        'video/x-matroska',
        'video/x-flv',
    );

    public $allowedFilesMimeTypes = array(
        'application/pdf',
        'application/zip',
        'application/x-gzip',
    );

    public $fileStorageUrl = '';
    public $tmpDir = '/tmp';
    private $_uploader;

    public function errorCodeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

    public function init()
    {
        Yii::import('core.components.fileUploader.uploaders.*');
        $this->_uploader = new $this->uploaderClassName($this->uploaderOptions);
        if (!$this->_uploader instanceof IFileUploader) {
            throw new Exception($this->uploaderClassName . ' should implement IFileUploader interface');
        }
    }

    public function getUrlByPath($path)
    {
        return $this->fileStorageUrl . $path;
    }

    public function moveUploadedFile(BaseUploadedFile $file, $destination)
    {
        return $this->_uploader->moveUploadedFile($file, $destination);
    }

    public function isUploadedFile(BaseUploadedFile $file)
    {
        return $this->_uploader->isUploadedFile($file);
    }

    public function copy(BaseUploadedFile $file, $destination)
    {
        return $this->_uploader->copy($file, $destination);
    }

    public function getAllowedImageMimeTypes()
    {
        return $this->allowedImageMimeTypes;
    }

    public function getAllowedVideoMimeTypes()
    {
        return $this->allowedVideoMimeTypes;
    }

    public function getAllowedFilesMimeTypes()
    {
        return $this->allowedFilesMimeTypes;
    }

    public function getMimeType($filePath)
    {
        return exec("file -b --mime-type $filePath");
    }

}

