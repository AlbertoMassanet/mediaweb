<?php


class OPFReader
{
    public $namespace = ["namespace" => "dc", "url" => "http://purl.org/dc/elements/1.1/"];
    public $useNamespace = true;
    
    public $nameTags = ['title', 'creator', 'date', 'description', 'language', 'subject'];
    public $imageTag = ['tag' => 'reference', 'type'=> 'cover', 'return' => 'href']; // NOT IN USE

    private $file;
    private $path;
    private $filename;

    private $useDOM = false;

    protected $objXML;
    protected $rawDoc;

  public function __construct($file = "", $useDOM = false)
  {
    $this->useDOM = $useDOM;
    if (!empty($file))
    {
      $this->setFile($file);
      $this->setDoc();
    }
    
    return $this;
  }


  public function setFile($file)
  {
    if (is_file($file)) 
    {
      $this->file = $file;
      $this->path = Utils::getPathFromFilePath($file);
      $this->filename = Utils::getFilenameFromFilePath($file);
      $this->rawDoc = file_get_contents($file);
      if (!mb_detect_encoding($this->rawDoc, 'UTF-8', true)) $this->rawDoc = mb_convert_encoding($this->rawDoc, 'UTF-8', 'auto');
    }
    else
      throw new Exception(" '{$file}' is not a file.");

    return $this;
  }

  public function setNamespace($arr)
  {
    if (is_array($arr))
      $this->namespace = $arr;

    return $this;
  }

  public function setUseNamespace($bool)
  {
    if (is_bool($bool)) $this->useNamespace = $bool;

    return $this;
  }

  public function setDoc()
  {
    if ($this->useDOM)
    {
      $this->objXML = new DOMDocument;
      $this->objXML->loadXML($this->rawDoc);
    } else {
      $this->objXML = new SimpleXMLElement($this->rawDoc);
    }
  }


  public function getTagMetadata($tag)
  {
    $this->objXML->registerXPathNamespace($this->namespace['namespace'], $this->namespace['url']);
    $ns = '//' . $this->namespace['namespace'] . ':';
    $result = $this->objXML->xpath($ns . $tag);

    return (string)$result[0];
  }

  public function getAllMetadata()
  {
    $result = [];

    foreach ($this->nameTags as $tag)
      $result[$tag] = $this->getTagMetadata($tag);

    return $result;
  }

  public function getCover()
  {
    //$pattern = '//' . $this->imageTag['tag'];
    //$result = $this->objXML->xpath('//reference[@type="cover"]');

    // OPF Cover
    $result = $this->objXML->guide->reference['href'];
    if ($result) 
      $result = $this->path . DIRECTORY_SEPARATOR . (string) $result;

    return $result;

  }

  public function getObj()
  {
    return $this->objXML;
  }
}