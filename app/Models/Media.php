<?php

namespace app\Models;

class Media
{
    protected $media = "";


    public function __construct($media)
    {
      $this->media = $media;
    }
}