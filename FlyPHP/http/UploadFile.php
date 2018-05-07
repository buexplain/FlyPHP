<?php namespace fly\http;

use fly\contracts\http\UploadFile as InterfaceUploadFile;
use Exception;


class UploadFile implements InterfaceUploadFile
{
    protected $file;

    protected $errors = array(
        UPLOAD_ERR_INI_SIZE => 'The file "%s" exceeds your upload_max_filesize ini directive (limit is %d KiB).',
        UPLOAD_ERR_FORM_SIZE => 'The file "%s" exceeds the upload limit defined in your form.',
        UPLOAD_ERR_PARTIAL => 'The file "%s" was only partially uploaded.',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
        UPLOAD_ERR_CANT_WRITE => 'The file "%s" could not be written on disk.',
        UPLOAD_ERR_NO_TMP_DIR => 'File could not be uploaded: missing temporary directory.',
        UPLOAD_ERR_EXTENSION => 'File upload was stopped by a PHP extension.',
    );

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function move($directory, $fileName=null)
    {
        if(!$this->isValid()) {
            throw new Exception($this->getErrorMessage());
        }

        if(is_null($fileName)) {
            $fileName = uniqid().'.'.$this->getOriginalExtension();
        }

        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new Exception(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new Exception(sprintf('Unable to write in the "%s" directory', $directory));
        }

        $target = rtrim($directory, '/\\').DIRECTORY_SEPARATOR.$fileName;

        if (!@move_uploaded_file($this->getRealPath(), $target)) {
            $error = error_get_last();
            throw new Exception(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getRealPath(), $target, strip_tags($error['message'])));
        }

        @chmod($target, 0666 & ~umask());

        return $target;
    }

    /**
     * Returns the maximum size of an uploaded file as configured in php.ini.
     *
     * @return int The maximum size of an uploaded file in bytes
     */
    public static function getMaxFileSize()
    {
        $iniMax = strtolower(ini_get('upload_max_filesize'));

        if ('' === $iniMax) {
            return PHP_INT_MAX;
        }

        $max = ltrim($iniMax, '+');
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($iniMax, -1)) {
            case 't':
                $max *= 1024;
                break;
            case 'g':
                $max *= 1024;
                break;
            case 'm':
                $max *= 1024;
                break;
            case 'k':
                $max *= 1024;
                break;
        }

        return $max;
    }

    public function getErrorMessage()
    {
        $errorCode = $this->getError();
        $maxFileSize = $errorCode === UPLOAD_ERR_INI_SIZE ? self::getMaxFileSize() / 1024 : 0;
        $message = isset($this->errors[$errorCode]) ? $this->errors[$errorCode] : 'The file "%s" was not uploaded due to an unknown error.';
        return sprintf($message, $this->getOriginalName(), $maxFileSize);
    }

    public function isValid()
    {
        return $this->getError() === UPLOAD_ERR_OK && is_uploaded_file($this->getRealPath());
    }

    public function getOriginalName()
    {
        return $this->file['name'];
    }

    public function getOriginalExtension()
    {
        return strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
    }

    public function getRealPath()
    {
        return $this->file['tmp_name'];
    }

    public function getMimeType()
    {
        return $this->file['type'];
    }

    public function getSize()
    {
        return $this->file['size'];
    }

    public function getError()
    {
        return $this->file['error'];
    }
}