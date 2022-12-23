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

  public function __construct($file)
  {
    if (is_file($file) && in_array(Utils::getExtension($file), $this->detectableFiles))
    {
      parent::__construct($file);
    } else throw new Exception("The file is not a image.");
  }

  public function Info()
  {
    return $this->getSimpleInfo();
  }

  public function getCover()
  {
    $rt = $this->findNearestCoverFiles();

  }

  public function getArrayFromOPF()
  {
    if ($file_opf = $this->findNearestMetadataFiles())
    {
      $this->opf = new OPFReader($file_opf);
    }
  }

  protected function findCoverFromOpf()
  {
    
  }

  protected function findNearestCoverFiles()
  {
    $ret = $this->findNearestFiles(self::FIND_FILE_COVER);
    return (!empty($ret)) ?? $ret;
  }

  protected function findNearestMetadataFiles()
  {
    $ret = $this->findNearestFiles(self::FIND_FILE_METADATA);
    return (!empty($ret)) ?? $ret;   
  }

  protected function findNearestFiles($typeFile)
  {
    $list = [];
    $imagesTypes = [];
    $patternFiles = [];
    $acceptables = [];

    switch ($typeFile) {
      case self::FIND_FILE_COVER:
        $acceptables = $this->aceptableCoverFiles;
        break;

      case self::FIND_FILE_METADATA:
        $acceptables = $this->aceptableMetadataFiles;
        break;
      default:
        return null;

    }


    foreach($acceptables as $typefiles)
    {
      if (strrpos($typefiles,".") !== false)
      {
        // complete filename or name.*
        $patternFiles[] = $typefiles;
      } else {
        // extensions
        $imagesTypes[] = $typefiles;
      }
    }

    foreach ($patternFiles as $findFiles)
    {
      if (strrpos($findFiles,"*") !== false) 
      {
        foreach($imagesTypes as $ext)
          foreach(glob($this->path_file.PATH_SEPARATOR.'*'.$this->name_file.'*.'.$ext) as $file)
            $list[] = $file;
      } else {
        if (file_exists($this->path_file.PATH_SEPARATOR.$this->name_file.'.'.$typefiles)) $list[] = $this->path_file.PATH_SEPARATOR.$typefiles;
      }
    }

    return $list;
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