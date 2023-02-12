<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaReader - Visualizador de Archivos Multimedia</title>

    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <div class="navigation">
        <div class="menuToggle"></div>
        <div class="title-nav"><span>Media</span>&nbsp;<span>Explorer</span></div>
        <ul>
            <li class="list">
                <a href="#" style="--clr: #f44336">
                    <span class="icon" title="Refrescar"><ion-icon name="refresh-outline"></ion-icon></span>
                    <span class="text">Refrescar</span>
                </a>
            </li>
            <li class="list">
                <a href="/" style="--clr: #0000a0">
                    <span class="icon" title="Audios"><ion-icon name="home-outline"></ion-icon></span>
                    <span class="text">Inicio</span>
                </a>
            </li>
            <li class="list">
                <a href="/audio" style="--clr: #f44336">
                    <span class="icon" title="Audios"><ion-icon name="musical-notes-outline"></ion-icon></span>
                    <span class="text">Audios</span>
                </a>
            </li>
            <li class="list">
                <a href="/book" style="--clr: #ffa117">
                    <span class="icon" title="Libros"><ion-icon name="book-outline"></ion-icon></span>
                    <span class="text">Libros</span>
                </a>
            </li>
            <li class="list">
                <a href="/video" style="--clr: #0fc70f"> 
                    <span class="icon" title="Vídeo"><ion-icon name="film-outline"></ion-icon></span>
                    <span class="text">Video</span>
                </a>
            </li>
            <li class="list">
                <a href="/images" style="--clr: #2196f3">
                    <span class="icon" title="Imágenes"><ion-icon name="image-outline"></ion-icon></span>
                    <span class="text">Imágenes</span>
                </a>
            </li>
            <li class="list">
                <a href="/setting" style="--clr: #b145e9">
                    <span class="icon" title="Ajustes"><ion-icon name="settings-outline"></ion-icon></span>
                    <span class="text">Ajustes</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="container">

        <div class="op-menu setting">
            <h2>Ajustes</h2>
            <div class="cont-form">
                <form method="POST">
                    <div class="row row-bottom">
                        <label for="samepath">
                            <input type="checkbox" 
                                name="samepath" 
                                id="samepath" />
                            Misma carpeta
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathbook">
                            <span>Carpeta Libros:</span>&nbsp;
                            <input type="text" 
                                name="pathbook" 
                                id="pathbook" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathvideo">
                            <span>Carpeta Vídeos:</span>&nbsp;
                            <input type="text" 
                                name="pathvideo"
                                id="pathvideo" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathaudio">
                            <span>Carpeta Audios:</span>&nbsp;
                            <input type="text" 
                                name="pathaudio" 
                                id="pathaudio" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathimage">
                            <span>Carpeta Imágenes:</span>&nbsp;
                            <input type="text" 
                                name="pathimage" 
                                id="pathimage" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="extradata">
                            <input type="checkbox" 
                            name="extradata" 
                            id="extradata">
                            Mostrar datos extra
                        </label>
                        <div class="row row-tab">
                            <div class="row">
                                <label for="useopf">
                                    <input type="checkbox" 
                                        name="formData.useopf" 
                                        id="useopf">
                                    Usar metadata Calibre en libros si los hubiera
                                </label>
                            </div>
                            <div class="row">
                                <label for="usenfo">
                                <input type="checkbox" 
                                    name="usenfo" 
                                    id="usenfo">
                                    Usar metadata Kodi/XBMC  en Vídeos/Audios si los hubiera
                                </label>
                                </div>
                        </div>
                    </div>
                    <div class="row row-last">
                        <label for="savefile">
                            <input type="checkbox" 
                                name="savefile" 
                                id="savefile">
                                Guardar datos en disco (agilizará la aplicación la próxima vez que la use)
                        </label>
                    </div>
                    <div class="row">
                        <div class="row-bottom row-button">
                            <button type="submit" id="save">
                                Guardar
                            </button>
                            <button id="cancel">
                                Cancelar
                            </button>
                            <button id="deletefile">
                                Borrar datos del disco
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>    

        <div id="book" class="media book">
                            

        </div>
        <div id="images" class="media images">

            <div class="card">
                <div class="face face1">
                    <div class="content">
                        <img src="" alt="">
                        <h3>Design</h3>
                    </div>
                </div>
                <div class="face face2">
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa laborum, corrupti voluptas architecto praesentium a reiciendis, optio magni sequi tempore, ratione vitae voluptatem eum magnam distinctio in rem illum vero.</p>
                        <a href="#">Read More</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="face face1">
                    <div class="content">
                        <img src="" alt="">
                        <h3>Design</h3>
                    </div>
                </div>
                <div class="face face2">
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa laborum, corrupti voluptas architecto praesentium a reiciendis, optio magni sequi tempore, ratione vitae voluptatem eum magnam distinctio in rem illum vero.</p>
                        <a href="#">Read More</a>
                    </div>
                </div>
            </div>  
            <div class="card">
                <div class="face face1">
                    <div class="content">
                        <img src="" alt="">
                        <h3>Design</h3>
                    </div>
                </div>
                <div class="face face2">
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa laborum, corrupti voluptas architecto praesentium a reiciendis, optio magni sequi tempore, ratione vitae voluptatem eum magnam distinctio in rem illum vero.</p>
                        <a href="#">Read More</a>
                    </div>
                </div>
            </div>        
        </div>

        <div id="video" class="media video">
        </div>

        <div id="audio" class="media audio">
        </div>

    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/common.js"></script>
    
</body>
</html>
