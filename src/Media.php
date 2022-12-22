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
  public $media = "";
  public $path;

  public function __construct($path, $media = "")
  {
    $this->path = $path;
    $this->media = $media;
    if (isset($this->path)) parent::__construct($this->path);
  }

  public function showTree()
  {
    return (isset($this->path)) ? $this->tree($this->path) : null;
  }
}