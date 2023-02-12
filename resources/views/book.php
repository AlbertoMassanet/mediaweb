<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaReader - Visualizador de Archivos Multimedia | <?=$title?></title>

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
            <li class="list active">
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
                <a href="/image" style="--clr: #2196f3">
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

    <div id="book" class="media book">
        <?=$description?>
    </div>

    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/common.js"></script>
    
</body>
</html>

