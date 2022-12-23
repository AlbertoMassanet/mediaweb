<?php

include_once "mediaFile.php";

include_once "metadata/NFOReader.php";
include_once "vendor/getid3/getid3.php";
include_once "vendor/getid3/getid3.lib.php";

class mediaVideo extends mediaFile
{
  public $detectableFiles = [
          "avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"];



  public function __construct($file)
  {
    if (is_file($file) && in_array(Utils::getExtension($file), $this->detectableFiles))
    {
      parent::__construct($file);
    } else throw new Exception("The file is not a image.");
  }

  public function Info()
  {
    return $this->getInfoID3();
  }

}