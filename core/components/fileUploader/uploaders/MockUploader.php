<?php
class MockUploader extends LocalFilesystemUploader implements IFileUploader
{
    public function moveUploadedFile(BaseUploadedFile $file, $destination)
    {
        $wasCopied = $this->copy($file, $destination);
        if ($wasCopied) {
            unlink($file->getTempName());
        }
        return $wasCopied;
    }

    public function isUploadedFile(BaseUploadedFile $file)
    {
        return true;
    }

}
