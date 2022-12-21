<?php

include_once "mediaFiles.php";

include_once 'mediaBook.php';

class Media extends mediaFiles
{
    


  /**
   * Full path and filename where data has been saved
   * 
   * @var String
   */
  public $fullsavefile = null; // Donde se guardan los datos si se han guardado.

  /**
   * Media types to search. Empty for all
   * 
   * @var String
   */
  public $media_type = "";

  public function __construct($media = "")
  {
    $this->media_type = $media;
  }
}