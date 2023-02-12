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

  public $settings = [];

  public function __construct($path, $media = "", $media_types = [], $settings = [])
  {
    $this->path = $path;
    if (!empty($media)) $this->media = $media;
    if (!empty($media_types)) $this->media_types = $media_types;
    if (!empty($settings)) $this->settings = $settings;
    if (isset($this->path)) parent::__construct($this->path);
  }

  public function setMediaTypes($media_types)
  {
    $this->media_types = $media_types;
    return $this;
  }

  public function setMedia($media)
  {
    $this->media = $media;
    return $this;    
  }

  public function setSetting($settings)
  {
    if (is_array($settings)) $this->settings = $settings;
    return $this;    
  }

  public function showTree()
  {
    return (isset($this->path)) ? $this->tree($this->path) : null;
  }


  public function run()
  {
    return (isset($this->path)) ? $this->advTree($this->path) : null;
  }
}