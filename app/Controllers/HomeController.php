<?php

namespace app\Controllers;

use lib\Template;

class HomeController extends Controller
{
    public function index()
    {
			// return $this->view('home', [
            //     'title' => 'Inicio',
            //     'description' => 'Esta es la página de inicio'
            // ]);
            Template::clearCache();
            Template::view('home.html', [
                'title' => 'Home Page',
                'lang' => 'es',
                'active' => 'home'
            ]);
	}
}