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



  public function __construct($file, $useID3 = false)
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