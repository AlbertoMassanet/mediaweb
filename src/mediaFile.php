<?php

include_once "Utils.php";

class mediaFile
{
  protected $detectableFiles = [];
  protected $aceptableMetadataFiles = [];
  protected $aceptableCoverFiles = [];
  protected $aceptablePartnerFiles = [];
  protected $aceptablePrefixFiles = [];
  protected $aceptableSufixFiles = [];

  protected $file;
  protected $info = [];

  protected $units = array(
    'bit' => 0.125,
    'KB' => 1024,
    'MB' => 1048576,
    'GB' => 1073741824,
    'TB' => 1099511627776
  );

  public function __construct($file)
  {
    if ($file)
    {
      $this->file = $file;
      $this->getInfo($file);
    } else throw new Exception("File cannot be empty.");
  }

  protected function getInfo($file)
  {
    $this->info = Utils::getInfoFromFile($file);
    if (empty($this->info)) throw new Exception("String '{$this->file}' is not a file.");
  }

    /**
     * return size of file
     * @param string $type unit default - kilobytes
     * allowed types:
     * KB: Kilobytes
     * MB: Megabytes
     * GB: Gigabytes
     * TB: Terabytes
     * bit: bites
     * @return int size of file
     * */
  protected function getSize($type = "KB")
  {
      $n_type = isset($this->units[$type]) ? $this->units[$type] : 1;
      return (isset($this->info['size'])) ? $this->info['size'] * $n_type : 0;
  }


}