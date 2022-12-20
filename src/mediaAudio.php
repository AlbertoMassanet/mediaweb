<?php

include_once "mediaFile.php";

class mediaAudio extends mediaFile
{
  public $detectableFiles = [
          "mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"];



  public $path;


  public function __construct($path)
  {
    if ($path)
    {
      $this->path = $path;
      if (!in_array($this->file_info['extension'], $this->detectableFiles)) throw new Exception("The file is not an audio format.");
    }
  }

}