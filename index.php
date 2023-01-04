<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once './src/Media.php';

    //$NFOnoXMLFilepath = realpath("test/formato_NFO_no_xml.nfo");

    // $nfoContent = file_get_contents("./test/formato_NFO_no_xml.nfo");
    // if (!mb_detect_encoding($nfoContent, 'UTF-8', true)) $nfoContent = mb_convert_encoding($nfoContent, 'UTF-8', 'auto');

    // // Es formato XML (Kodi/XBMC)
    // if (strpos($nfoContent, '<?xml') === 0)
    // {
    //     $nfoObject = simplexml_load_string($nfoContent) || die("Cannot create object");
    // }
    // // Es formato NO XML 
    // else {

    // }

    // $metadata = new OPFReader("./test/metadata.opf");

    // 36 mime_types (23/12/2022)
    $media_types = [
        "book" => ["epub", "html", "htm", "pdf", "rtf", "txt", "cbc", "fb2", "lit", "mobi", "odt", "doc", "docx", "prc", "pdb", "pml", "cbz", "cbr"],
        "audio" => ["mp3", "ogg", "wav", "3gp", "m4a", "wma", "wav"],
        "video" => ["avi", "mp1", "mp2", "mp4", "webm", "amv", "mtv"],
        "images" => ["jpg", "jpeg", "gif", "png"],
    ];

    $prueba = new Media('/media/jefe/TOSH_EXT_NTFS/Media/libros/', 'book', $media_types);
    





?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaReader - Visualizador de Archivos Multimedia</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/alpine.min.js"></script>
    
</head>
<body x-data="">
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
            <li class="list"class="{ 'active': $store.globalData.tab === 'audio' }">
                <a href="#" style="--clr: #f44336" x-on:click.prevent="$store.globalData.changeTo('audio')">
                    <span class="icon" title="Audios"><ion-icon name="musical-notes-outline"></ion-icon></span>
                    <span class="text">Audios</span>
                </a>
            </li>
            <li class="list"lass="{ 'active': $store.globalData.tab === 'book' }">
                <a href="#" style="--clr: #ffa117" x-on:click.prevent="$store.globalData.changeTo('book')">
                    <span class="icon" title="Libros"><ion-icon name="book-outline"></ion-icon></span>
                    <span class="text">Libros</span>
                </a>
            </li>
            <li class="list"class="{ 'active': $store.globalData.tab === 'video' }">
                <a href="#" style="--clr: #0fc70f" x-on:click.prevent="$store.globalData.changeTo('video')"> 
                    <span class="icon" title="Vídeos"><ion-icon name="film-outline"></ion-icon></span>
                    <span class="text">Videos</span>
                </a>
            </li>
            <li class="list":class="{ 'active': $store.globalData.tab === 'images' }">
                <a href="#" style="--clr: #2196f3" x-on:click.prevent="$store.globalData.changeTo('images')">
                    <span class="icon" title="Imágenes"><ion-icon name="image-outline"></ion-icon></span>
                    <span class="text">Imágenes</span>
                </a>
            </li>
            <li class="list" :class="{ 'active': $store.globalData.tab === 'setting' }">
                <a href="#" style="--clr: #b145e9" x-on:click.prevent="$store.globalData.changeTo('setting')">
                    <span class="icon" title="Ajustes"><ion-icon name="settings-outline"></ion-icon></span>
                    <span class="text">Ajustes</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="container">
        <div 
        x-data=""
        class="fixed inset-0 flex flex-col-reverse items-end justify-start h-screen w-screen"
        @notice.window="$store.noticesHandler.add($event.detail)"
        style="pointer-events:none">
            <template x-for="notice of $store.noticesHandler.notices" :key="notice.id">
                <div
                    x-show="$store.noticesHandler.visible.includes(notice)"
                    x-transition:enter="transition ease-in duration-200"
                    x-transition:enter-start="transform opacity-0 translate-y-2"
                    x-transition:enter-end="transform opacity-100"
                    x-transition:leave="transition ease-out duration-500"
                    x-transition:leave-start="transform translate-x-0 opacity-100"
                    x-transition:leave-end="transform translate-x-full opacity-0"
                    @click="$store.noticesHandler.remove(notice.id)"
                    class="rounded mb-4 mr-6 w-56  h-16 flex items-center justify-center text-white shadow-lg font-bold text-lg cursor-pointer"
                    :class="{
                        'bg-green-500': $store.noticesHandler.notice.type === 'success',
                        'bg-blue-500': $store.noticesHandler.notice.type === 'info',
                        'bg-orange-500': $store.noticesHandler.notice.type === 'warning',
                        'bg-red-500': $store.noticesHandler.notice.type === 'error',
                    }"
                    style="pointer-events:all"
                    x-text="$store.noticesHandler.notice.text">
                </div>
            </template>

        </div>
        <div class="op-menu setting" x-show="$store.globalData.tab === 'setting'" >
            <h2>Ajustes</h2>
            <div class="cont-form">
                <form method="POST" @submit.prevent="submitForm" x-data="crudAlpine()" x-init="iniciar">
                    <div class="row row-bottom">
                        <label for="samepath">
                            <input type="checkbox" 
                                x-model="formData.samepath" 
                                x-on:change="checkValue()" 
                                name="samepath" 
                                id="samepath" />
                            Misma carpeta
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathbook">
                            <span>Carpeta Libros:</span>&nbsp;
                            <input type="text" 
                                x-model="formData.pathbook" 
                                x-on:keyup="checkValue()" 
                                name="pathbook" 
                                id="pathbook" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathvideo">
                            <span>Carpeta Vídeos:</span>&nbsp;
                            <input type="text" 
                                x-model="formData.pathvideo"
                                name="pathvideo"
                                id="pathvideo" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathaudio">
                            <span>Carpeta Audios:</span>&nbsp;
                            <input type="text" 
                                x-model="formData.pathaudio"
                                name="pathaudio" 
                                id="pathaudio" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="pathimage">
                            <span>Carpeta Imágenes:</span>&nbsp;
                            <input type="text" 
                                x-model="formData.pathimage" 
                                name="pathimage" 
                                id="pathimage" required />
                        </label>
                    </div>
                    <div class="row">
                        <label for="extradata">
                            <input type="checkbox" 
                            x-model="formData.extradata" 
                            x-on:click="checkUseExtradata()" 
                            name="extradata" 
                            id="extradata">
                            Mostrar datos extra
                        </label>
                        <div class="row row-tab">
                            <div class="row">
                                <label for="useopf">
                                    <input type="checkbox" 
                                        x-model="formData.useopf" 
                                        :disabled = "!extradata" 
                                        name="formData.useopf" 
                                        id="useopf">
                                    Usar metadata Calibre en libros si los hubiera
                                </label>
                            </div>
                            <div class="row">
                                <label for="usenfo">
                                <input type="checkbox" 
                                    x-model="formData.usenfo" 
                                    :disabled = "!extradata" 
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
                                x-model="formData.savefile" 
                                name="savefile" 
                                id="savefile">
                                Guardar datos en disco (agilizará la aplicación la próxima vez que la use)
                        </label>
                    </div>
                    <div class="row">
                        <div class="row-bottom row-button">
                            <button type="submit" id="save" :disabled="formLoading">
                                Guardar
                            </button>
                            <button id="cancel" :disabled="formLoading">
                                Cancelar
                            </button>
                            <button id="deletefile" :disabled="formLoading">
                                Borrar datos del disco
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>    

        <div id="book" class="media book" x-show="$store.globalData.tab === 'book'" >
                            
        <?php

                    //echo "<pre>" . print_r($prueba->showTree(),true) . "</pre>";
                    
                ?>


            <!-- <div x-data="{ title: 'Start Here' }">
                <h1 x-text="title"></h1>
            </div> -->
                    
        </div>
        <div id="images" class="media images" x-show="$store.globalData.tab === 'images'">

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

        <div id="video" class="media video" x-show="$store.globalData.tab === 'video'">
        </div>

        <div id="audio" class="media audio" x-show="$store.globalData.tab === 'audio'">
        </div>

    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/common.js"></script>
    <script>

        const FORM_URL = './request.php';


        document.addEventListener('alpine:init', () => {
            Alpine.store('globalData', {
                init() {
                    this.tab = 'setting'
                },
                tab: 'setting',
                changeTo(tab) {
                    this.tab = tab
                    if (tab != 'setting')
                    {
                        fetch(FORM_URL + '?media='+tab)
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(); // Will take you to the `catch` below
                            }
                            return res.json();
                        })
                        .then(response => {
                            console.log("exito: " + JSON.stringify(response))
                            this.showResponse(response)
                        })
                    }
                },
                showResponse(data) {
                    let div = document.querySelector('div#' + this.tab)
                    let d = this.displayData(data, div)
                    
                    //div.innerHTML = d
                    console.log(div)
                },
                displayData(data, parent) {
                    let ret = [];
                    let count = 0;
                    if (
                        typeof data === 'object' &&
                        !Array.isArray(data) &&
                        data !== null
                    )
                        Object.entries(data).forEach(([key, value]) => {

                            if (typeof value === 'object' && !Array.isArray(value) && value !== null) 
                                ret = this.displayData(value, parent)
                            else 
                                {
                                    let div = document.createElement('div');
                                    div.classList.add('temporal')
                                    div.innerHTML = key + ": " + value;
                                    parent.appendChild(div);
                                    ret[count] = div;
                                    count++;
                                }
                        })
                    return ret;
                },
            })

            Alpine.store('noticesHandler', {
                notices: [],
                visible: [],
                add(notice) {
                    notice.id = Date.now()
                    this.notices.push(notice)
                    this.fire(notice.id)
                },
                fire(id) {
                    this.visible.push(this.notices.find(notice => notice.id == id))
                    const timeShown = 2000 * this.visible.length
                    setTimeout(() => {
                        this.remove(id)
                    }, timeShown)
                },
                remove(id) {
                    const notice = this.visible.find(notice => notice.id == id)
                    const index = this.visible.indexOf(notice)
                    this.visible.splice(index, 1)
                },
            })
        })

        // function noticesHandler() {
        //     return {
        //         notices: [],
        //         visible: [],
        //         add(notice) {
        //             notice.id = Date.now()
        //             this.notices.push(notice)
        //             this.fire(notice.id)
        //         },
        //         fire(id) {
        //             this.visible.push(this.notices.find(notice => notice.id == id))
        //             const timeShown = 2000 * this.visible.length
        //             setTimeout(() => {
        //                 this.remove(id)
        //             }, timeShown)
        //         },
        //         remove(id) {
        //             const notice = this.visible.find(notice => notice.id == id)
        //             const index = this.visible.indexOf(notice)
        //             this.visible.splice(index, 1)
        //         },
        //     }
        // }


        function crudAlpine()
        {
            return {
                formLoading: false,
                formData: {
                    samepath: false,
                    pathbook: "",
                    pathvideo: "",
                    pathaudio: "",
                    pathimage: "",
                    extradata: true,
                    useopf: true,
                    usenfo: true,
                    savefile: true,
                },

                iniciar: function() {
                    this.formLoading = true;
                    console.log(FORM_URL+'?read=1')
                    fetch(FORM_URL+'?read=1')
                    .then(res => {
                        return res.json();})
                    .then(response => { 
                        this.formData = response;
                        //this.runNotif('success');
                    })
                    .catch(error => console.log("Ha ocurrido un error: " + error))
                    .finally(() => {
                        this.formLoading = false;
                    })
                },

                runNotif: function(notType)
                {
                    console.log(window.Alpine)
                    $store.noticesHandler.add({type: 'success', text: '🔥 Success!'});
                },

                sp: function() {
                    if (this.formData.samepath)
                    {
                        this.formData.pathvideo = this.formData.pathbook
                        this.formData.pathaudio = this.formData.pathbook
                        this.formData.pathimage = this.formData.pathbook
                    }
                },

                checkValue: function() {
                    this.sp()
                },

                checkUseExtradata: function() {
                    this.formData.usenfo = !this.formData.extradata;
                    this.formData.useopf = !this.formData.extradata;
                },

                submitForm: function() {
                    //console.log(JSON.stringify(this.formData))
                    this.formLoading = true;
                    fetch(FORM_URL, {
                        method: "POST",
                        headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        },
                        body: JSON.stringify(this.formData),
                    })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error(); // Will take you to the `catch` below
                        }
                        return res.json();})
                    .then(response => {
                        console.log("exito: " + response)
                    })
                    .catch(error => console.log("Ha ocurrido un error: " + error))
                    .finally(() => {
                        this.formLoading = false;
                    })
                }
            }
        }
    </script>
</body>
</html>






