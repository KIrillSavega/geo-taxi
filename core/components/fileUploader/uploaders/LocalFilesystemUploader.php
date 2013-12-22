<?php

class LocalFilesystemUploader extends CComponent implements IFileUploader
{
    public $uploadFolder = '/tmp';

    public function __construct($config)
    {
        foreach ($config as $attribute => $value) {
            $this->{$attribute} = $value;
        }
    }

    public function moveUploadedFile(BaseUploadedFile $file, $destination)
    {
        $copyTo = $this->resolveDestination($destination);
        $this->checkAndCreateDirIfNotExist($copyTo);
        if (rename($file->getTempName(), $copyTo)) {
            return true;
        }
    }

    public function isUploadedFile(BaseUploadedFile $file)
    {
        return is_uploaded_file($file->getTempName());
    }

    public function copy(BaseUploadedFile $file, $destination)
    {
        $destination = $this->resolveDestination($destination);
        $this->checkAndCreateDirIfNotExist($destination);
        return copy($file->getTempName(), $destination);
    }

    protected function resolveDestination($destination)
    {
        return $this->uploadFolder . DIRECTORY_SEPARATOR . $destination;
    }

    protected function checkAndCreateDirIfNotExist($destination)
    {
        $pathArray = explode('/', $destination);
        array_pop($pathArray);
        $newDestination = '';

        //check and create if not exist dir recursively
        if (!is_dir($destination)) {
            foreach ($pathArray as $path) {
                $newDestination .= $path . '/';
                if (!is_dir($newDestination)) {
                    mkdir($newDestination, 0775, true);
                }
            }
        }

        //check is dir writable
        if (!is_writable(dirname($destination))) {
            throw new CException("Directory " . dirname($destination) . " is not writable!");
        }
    }
}
