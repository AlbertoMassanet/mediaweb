<?php

include_once "mediaFile.php";

class mediaAudio extends mediaFile
{
  public $detectableFiles = [
          "mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"];



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