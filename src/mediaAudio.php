<?php

include_once "mediaFile.php";

include_once "vendor/getid3/getid3.php";
include_once "vendor/getid3/getid3.lib.php";

class mediaAudio extends mediaFile
{
  public $detectableFiles = [
          "mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"];



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