<?php

include_once "mediaFile.php";
include_once "vendor/getid3/getid3.php";

class mediaImages extends mediaFile
{
  public $detectableFiles = [
          "jpg", "jpeg", "gif", "png"];

  public $path;


  public function __construct($path)
  {
    if ($path)
    {
      $this->path = $path;
      if (!in_array($this->file_info['extension'], $this->detectableFiles)) throw new Exception("The file is not a image.");
    }
  }

}