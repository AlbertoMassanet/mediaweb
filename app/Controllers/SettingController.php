<?php

namespace app\Controllers;

use app\Utils;

class SettingController extends Controller
{
    protected $title = "Ajustes";

    public function index()
    {
       // if (empty($this->config)) $this->config = Utils::loadFromFile(SETTING_JSON_FILE);
        
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        $token = $_SESSION['token'];

		return $this->view('setting', [
                'title' => $this->title,
                'csrf' => $token,
                'actions' => $this->actions,
                'data' => $this->config,
        ]);
	}

    public function post()
    {
        
        if (!empty($_POST['token'])) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                 // Proceed to process the form data

                 if (file_exists(SETTING_JSON_FILE))
                 {
                     unlink(SETTING_JSON_FILE);
                 }
             
                 $this->extractData();
                 $res = Utils::saveToFile(SETTING_JSON_FILE, $this->data, true);
                 
                 if (is_string($res))
                 {
                    // $objSent = $res;
                    // $returnType = RETURN_ERROR_BAD_REQUEST;
                    $this->actions['errors'][] = "No se ha podido guardar los datos de configuración";
                 } else {
                    // $returnType = RETURN_CREATE_OK;
                    // $objSent = $res;
                    $this->actions['success'][] = "Se han guardado los datos de configuración con éxito";
                 }

            } else {
                 // Log this as a warning and keep an eye on these attempts
                 $this->actions['errors'][] = "Invalid form data";
            }
        } else {
            $this->actions['errors'][] = "Invalid form data";
        }
        unset($_SESSION['token']);
        return $this->index();
    }

    public function delete()
    {
        $res = true;
        if (file_exists(PATH_DATA_FILES))
        {
            foreach(glob(PATH_DATA_FILES.'*') as $file)
            {
                $res = @unlink($file);
            }
        }
        if (!$res)
        {
            $this->actions['errors'][] = "No se ha(n) podido eliminar uno o varios archivos de datos";
        } else {
            $this->actions['success'][] = "Se han eliminado los datos con éxito";
        }
        return $this->index();
    }

    protected function extractData()
    {
        if (array_key_exists('pathaudio', $_POST)) $this->data['pathaudio'] = $_POST['pathaudio'];
        if (array_key_exists('pathbook', $_POST)) $this->data['pathbook'] = $_POST['pathbook'];
        if (array_key_exists('pathvideo', $_POST)) $this->data['pathvideo'] = $_POST['pathvideo'];
        if (array_key_exists('pathimage', $_POST)) $this->data['pathimage'] = $_POST['pathimage'];
        if (array_key_exists('extradata', $_POST)) $this->data['extradata'] = $_POST['extradata'];
        if (array_key_exists('useopf', $_POST)) $this->data['useopf'] = $_POST['useopf'];
        if (array_key_exists('usenfo', $_POST)) $this->data['usenfo'] = $_POST['usenfo'];
        if (array_key_exists('savefile', $_POST)) $this->data['savefile'] = $_POST['savefile'];
    }
}