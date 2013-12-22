<?php

class UploadedFile extends BaseUploadedFile
{
    static protected $_files;

    /**
     * Constructor.
     * Use {@link getInstance} to get an instance of an uploaded file.
     * @param string $name the original name of the file being uploaded
     * @param string $tempName the path of the uploaded file on the server.
     * @param string $type the MIME-type of the uploaded file (such as "image/gif").
     * @param integer $size the actual size of the uploaded file in bytes
     * @param integer $error the error code
     */
    public function __construct($name, $tempName, $type, $size, $error)
    {
        $this->_name = $name;
        $this->_tempName = $tempName;
        $this->_type = $type;
        $this->_size = $size;
        $this->_error = $error;
        $this->_checkIsUploadedFileOnSave = true;
    }

    /**
     * Returns an instance of the specified uploaded file.
     * The file should be uploaded using {@link CHtml::activeFileField}.
     * @param CModel $model the model instance
     * @param string $attribute the attribute name. For tabular file uploading, this can be in the format of "[$i]attributeName", where $i stands for an integer index.
     * @return UploadedFile the instance of the uploaded file.
     * Null is returned if no file is uploaded for the specified model attribute.
     * @see getInstanceByName
     */
    public static function getInstance($model, $attribute)
    {
        return static::getInstanceByName(CHtml::resolveName($model, $attribute));
    }

    /**
     * Returns an instance of the specified uploaded file.
     * The name can be a plain string or a string like an array element (e.g. 'Blog[imageFile]', or 'Blog[0][imageFile]').
     * @param string $name the name of the file input field.
     * @return UploadedFile the instance of the uploaded file.
     * Null is returned if no file is uploaded for the specified name.
     */
    public static function getInstanceByName($name)
    {
        if (null === static::$_files)
            static::prefetchFiles();

        return isset(static::$_files[$name]) && static::$_files[$name]->getError() != UPLOAD_ERR_NO_FILE ? static::$_files[$name] : null;
    }

    /**
     * Initially processes $_FILES superglobal for easier use.
     * Only for internal usage.
     */
    protected static function prefetchFiles()
    {
        static::$_files = array();
        if (!isset($_FILES) || !is_array($_FILES))
            return;

        foreach ($_FILES as $class => $info)
            static::collectFilesRecursive($class, $info['name'], $info['tmp_name'], $info['type'], $info['size'], $info['error']);
    }

    /**
     * Processes incoming files for {@link getInstanceByName}.
     * @param string $key key for identifiing uploaded file: class name and subarray indexes
     * @param mixed $names file names provided by PHP
     * @param mixed $tmp_names temporary file names provided by PHP
     * @param mixed $types filetypes provided by PHP
     * @param mixed $sizes file sizes provided by PHP
     * @param mixed $errors uploading issues provided by PHP
     */
    protected static function collectFilesRecursive($key, $names, $tmp_names, $types, $sizes, $errors)
    {
        if (is_array($names)) {
            foreach ($names as $item => $name)
                static::collectFilesRecursive($key . '[' . $item . ']', $names[$item], $tmp_names[$item], $types[$item], $sizes[$item], $errors[$item]);
        } else {
            $class = get_called_class();
            static::$_files[$key] = new $class($names, $tmp_names, $types, $sizes, $errors);
        }

    }

    /**
     * Returns all uploaded files for the given model attribute.
     * @param CModel $model the model instance
     * @param string $attribute the attribute name. For tabular file uploading, this can be in the format of "[$i]attributeName", where $i stands for an integer index.
     * @return array array of UploadedFile objects.
     * Empty array is returned if no available file was found for the given attribute.
     */
    public static function getInstances($model, $attribute)
    {
        return static::getInstancesByName(CHtml::resolveName($model, $attribute));
    }

    /**
     * Returns an array of instances for the specified array name.
     *
     * If multiple files were uploaded and saved as 'Files[0]', 'Files[1]',
     * 'Files[n]'..., you can have them all by passing 'Files' as array name.
     * @param string $name the name of the array of files
     * @return array the array of UploadedFile objects. Empty array is returned
     * if no adequate upload was found. Please note that this array will contain
     * all files from all subarrays regardless how deeply nested they are.
     */
    public static function getInstancesByName($name)
    {
        if (null === static::$_files)
            static::prefetchFiles();

        $len = strlen($name);
        $results = array();
        foreach (array_keys(static::$_files) as $key)
            if (0 === strncmp($key, $name, $len) && static::$_files[$key]->getError() != UPLOAD_ERR_NO_FILE)
                $results[] = static::$_files[$key];
        return $results;
    }

    /**
     * Cleans up the loaded UploadedFile instances.
     * This method is mainly used by test scripts to set up a fixture.
     * @since 1.1.4
     */
    public static function reset()
    {
        static::$_files = null;
    }
}
