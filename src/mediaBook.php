<?php

include_once "mediaFile.php";

include_once "metadata/OPFReader.php";
include_once "metadata/TextReader.php";

class mediaBook extends mediaFile
{


  public $detectableFiles = [
          "epub", "html", "htm", "pdf", "rtf", "txt", "cbc", "fb2", "lit", "mobi", "odt", "doc", "docx", "prc", "pdb", "pml", "cbz", "cbr"
          ];

  public $aceptableMetadataFiles = ["metadata.opf", "opf", "txt"];

  public $aceptableCoverFiles = ["cover.jpg", "cover.*", "portada.*", "contraportada.*", "jpg", "png", "gif"];


  protected $opf;

  public function __construct($file = "")
  {
    if (is_file($file) && in_array(Utils::getExtension($file), $this->detectableFiles))
    {
      parent::__construct($file);
    } else return $this;
  }

  public function getFullData()
  {
    $ret = [];

    $n = $this->getFriendlyName();
    $n = (!empty($n)) ? $n : $this->name_file;
    // Datos básicos del sistema
    $ret[$n]['system'] = $this->getSimpleInfo();


    // Cover si lo hubiera
    $c = $this->getCover();
    if (is_array($c) && !empty($c))
      if (count($c) > 1) {
        $r = [];
        foreach ($c as $img)
          $r[] = Utils::getBase64FromFileImage($img);
          $ret[$n]['cover'] = $r;
      } else {
        $ret[$n]['cover'] = Utils::getBase64FromFileImage($c[0]);
      }

    // Metadata si lo hubiera
    $r = $this->getMetadataFiles();
    if (!empty($r) && is_array($r)) 
    {
      // Solo toma el primero
      $opfFile = $r[0];

      $ret[$n]['metadata'] = $r;
    }

    return $ret;
  }

  public function setFile($file)
  {
    if (is_file($file) && in_array(Utils::getExtension($file), $this->detectableFiles))
    {
      parent::__construct($file);
    } else throw new Exception("The file is not a book.");
  }

  public function Info()
  {
    return $this->getSimpleInfo();
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