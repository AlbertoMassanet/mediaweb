<?php

namespace app\Controllers;

class AudioController extends Controller
{
    protected $media = "audio";
    protected $media_types = ["mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"];

    public function index()
    {

		return $this->view($this->media, [
                'media' => $this->media,
                'title' => 'Audio',
                'description' => 'Esta es la página de audio'
        ]);
	}
}