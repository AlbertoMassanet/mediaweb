<?php

include_once "mediaFile.php";

class mediaVideo extends mediaFile
{
  public $detectableFiles = [
          "avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"];



  public function __construct($file, $useID3 = true)
  {
    if (is_file($file) && in_array(Utils::getExtension($file), $this->detectableFiles))
    {
      parent::__construct($file, $useID3);
    } else throw new Exception("The file is not a image.");
  }

  public function Info()
  {
    return $this->info;
  }

}