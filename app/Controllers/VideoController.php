<?php

namespace app\Controllers;

class VideoController extends Controller
{
    protected $media = "video";
    protected $media_types = ["avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"];

    public function index()
    {
		return $this->view($this->media, [
                'media' => $this->media,
                'title' => 'Video',
                'description' => 'Esta es la página de video'
        ]);
	}
}