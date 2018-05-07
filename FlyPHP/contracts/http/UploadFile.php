<?php namespace fly\contracts\http;

interface UploadFile
{
    public function move($path, $fileName=null);
    public function getErrorMessage();
    public function isValid();
    public function getOriginalName();
    public function getOriginalExtension();
    public function getRealPath();
    public function getMimeType();
    public function getSize();
    public function getError();
}