<?php

include_once "mediaImages.php";
include_once "mediaBook.php";
include_once "mediaAudio.php";
include_once "mediaVideo.php";

/**
 * ITINERATOR OF FILES
 */

class mediaFiles
{

  protected $path = null;
  protected $files = [];

  public function __construct($path)
  {
    if (!empty($path)) $this->path = $path;
    else throw new Exception("Path cannot be empty.");
  }
  /**
   * Search files 
   * 
   * @param String $media Media Type to search into path (if empty, search all media types)
   * @return Array if invalid media type or not found return empty array
   */
  protected function searchFilesByMediaType($media = "")
  {
    //TODO
  }
}