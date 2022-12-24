<?php

include_once "mediaFiles.php";

class Media extends mediaFiles
{
    


  /**
   * Full path and filename where data has been saved
   * 
   * @var String
   */
  public $fullsavefile = null; // Donde se guardan los datos si se han guardado.

  public $media_types = [];

  public $path;

  public function __construct($path, $media = "", $media_types = [])
  {
    $this->path = $path;
    if (!empty($media)) $this->media = $media;
    if (!empty($media_types)) $this->media_types = $media_types;
    if (isset($this->path)) parent::__construct($this->path);
    return $this;
  }

  public function setMediaTypes($media_types)
  {
    $this->media_types = $media_types;
    return $this;
  }

  public function showTree()
  {
    return (isset($this->path)) ? $this->tree($this->path, ) : null;
  }
}