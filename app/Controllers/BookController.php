<?php

namespace app\Controllers;

use Utils;

class BookController extends Controller
{
    protected $title = "Libros";

    protected $media = "book";
    protected $media_type = ["epub", "html", "htm", "pdf", "rtf", "txt", "cbc", "fb2", "lit", "mobi", "odt", "doc", "docx", "prc", "pdb", "pml", "cbz", "cbr"];


    public function __construct()
    {
      parent::__construct();
      if (!array_key_exists('pathbook', $this->config) || empty($this->config['pathbook'])) 
        $this->actions['errors'][] = "No existe una ruta a los datos";
      else
        $this->mainPath = $this->config['pathbook'];
    }

    public function index()
    {
      $this->getMedia();
      $ret = [
          'media' => $this->media,
          'title' => $this->title,
          'description' => 'Esta es la página de libros',
          'data' => $this->data,
          'config' => $this->config,
        ];

	    return $this->view($this->media, $ret);
    }
}