<?php

include_once "mediaFiles.php";

include_once "metadata/OPFReader.php";

class mediaBook extends mediaFiles
{
  public $detectableFiles = [
          "epub", "html", "htm", "pdf", "rtf", "txt", "cbc", "fb2", "lit", "mobi", "odt", "doc", "docx", "prc", "pdb", "pml", "cbz", "cbr"
          ];

  public $aceptableMetadataFiles = ["metadata.opf", "opf", "txt"];

  public $aceptableCoverFiles = ["cover.jpg", "jpg", "png", "gif"];


  public $path;


  public function __construct($path)
  {
    if ($path)
    {
      $this->path = $path;
      parent::__construct($path);
      if (!in_array($this->file_info['extension'], $this->detectableFiles)) throw new Exception("The file is not a book.");
    }
  }

  public function findAuxiliarFiles()
  {
    //TODO
  }

  public function getOPFData($metadataFile, $metatags = [])
  {
    $t = new OPFReader($metadataFile);

    if (!empty($metatags)) $t->nameTags = $metatags;

    return [
      'metadata' => $t->getAllMetadata(),
      'cover' => $t->getCover()
    ];
  }
}