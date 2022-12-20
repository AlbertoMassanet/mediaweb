<?php

include_once "mediaFile.php";

class mediaVideo extends mediaFile
{
  public $detectableFiles = [
          "avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"];


  public $path;


  public function __construct($path)
  {
    if ($path)
    {
      $this->path = $path;
      if (!in_array($this->file_info['extension'], $this->detectableFiles)) throw new Exception("The file is not a video format.");
    }
  }

}