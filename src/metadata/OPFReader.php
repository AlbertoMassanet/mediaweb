<?php

class OPFReader {
  private $xml;
  private $filepath;

  public function __construct($filePath) {
    // Cargamos el archivo OPF en un objeto SimpleXML
    $this->xml = simplexml_load_file($filePath);
    $this->filepath = $filePath;
  }

  public function getTitle() {
    // Buscamos el título del libro en los metadatos
    $title = $this->xml->metadata->children('dc', true)->title;
    if ($title) {
      return (string)$title;
    }
    return null;
  }

  public function getLanguage() {
    // Buscamos el idioma del libro en los metadatos
    $language = $this->xml->metadata->children('dc', true)->language;
    if ($language) {
      return (string)$language;
    }
    return null;
  }

  public function getDescription() {
    // Buscamos la sinopsis del libro en los metadatos
    $description = $this->xml->metadata->children('dc', true)->description;
    if ($description) {
      return (string)$description;
    }
    return null;
  }

  public function getAuthor() {
    // Buscamos el autor del libro en los metadatos
    $author = $this->xml->metadata->children('dc', true)->creator;
    if ($author) {
      return (string)$author;
    }
    return null;
  }

  public function getChapters() {
    // Creamos un array para almacenar los capítulos del libro
    $chapters = array();

    // Iteramos a través de cada item del spine (que contiene la lista de capítulos del libro)
    foreach ($this->xml->spine->itemref as $item) {
      // Buscamos el título del capítulo en los metadatos
      $id = (string)$item['idref'];
      $title = $this->xml->manifest->item[$id]->metadata->children('dc', true)->title;
      if ($title) {
        $chapters[] = (string)$title;
      }
    }
    return $chapters;
  }

  public function getCoverImage() {
    // Buscamos el elemento manifest que corresponda a la portada del libro
    $manifest = $this->xml->manifest->item[0];
    if (!$manifest) {
      return null;
    }
  
    // Obtenemos la ruta del archivo de la portada
    $coverPath = (string)$manifest['href'];
  
    // Si la ruta es relativa, la convertimos a absoluta
    if (!preg_match('/^https?:\/\//', $coverPath)) {
      $coverPath = dirname($this->filepath) . PATH_SEPARATOR . $coverPath;
    }
  
    // Devolvemos la ruta del archivo de la portada
    return $coverPath;
  }

  public function getGenres() {
    // Creamos un array para almacenar los géneros del libro
    $genres = array();
  
    // Buscamos los géneros del libro en los metadatos
    $metadata = $this->xml->metadata->children('dc', true)->subject;
    if ($metadata) {
      // Si hay más de un género, los separamos en un array
      if (strpos($metadata, ',') !== false) {
        $genres = explode(',', $metadata);
      } else {
        $genres[] = $metadata;
      }
    }
    return $genres;
  }
}