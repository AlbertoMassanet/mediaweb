<?php

namespace app\Controllers;

class ImageController extends Controller
{
    protected $media = "image";
    protected $media_types = ["jpg", "jpeg", "gif", "png"];

    public function index()
    {
		return $this->view($this->media, [
                'media' => $this->media,
                'title' => 'Imágenes',
                'description' => 'Esta es la página de imágenes'
        ]);
	}
}