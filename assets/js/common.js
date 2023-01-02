let menuToggle = document.querySelector('.menuToggle');
let navigation = document.querySelector('.navigation');
let list = document.querySelectorAll('.list');
let activeTab = 'setting';
let neverActive = 'refresh';

menuToggle.onclick = function() {
    navigation.classList.toggle('active');
   
}

function activeLink()
{
    if (this.id != neverActive)
    {
        list.forEach((item) => {
            item.classList.remove('active');
        });
        this.classList.add('active');
        activeTab = this.id;

        //TODO: Visualiza el formato requerido
        
    } else {
        // Realiza el refresco
    }
    
}

list.forEach((item) => item.addEventListener('click', activeLink))



/**
 * Get brightness from an image.
 * 
 * @param {object} image 
 * @param {function} callback 
 */
// Usage:
// var thisImg;
// document.querySelector("img").addEventListener("click", function(){
//     thisImg = $(this)

//     getImageBrightness( $(this),function( thisImgID, brightness ) {     
//         document.getElementsByTagName('pre')[0].innerHTML = "Brightness: "+brightness+"<br><br>- Red border means class added -> dark,<br>- yellow border mean class added -> light";

//         if(brightness<127.5){
//             document.querySelector("#"+thisImgID).classList.add("dark");
//         }else{
//             document.querySelector("#"+thisImgID).classList.add("light");
//         }
//     });
// });

function getImageBrightness(image,callback) {
    var thisImgID = image.attr("id");

    var img = document.createElement("img");
    img.src = image.attr("src");

    img.style.display = "none";
    document.body.appendChild(img);

    var colorSum = 0;

    img.onload = function() {
        // create canvas
        var canvas = document.createElement("canvas");
        canvas.width = this.width;
        canvas.height = this.height;

        var ctx = canvas.getContext("2d");
        ctx.drawImage(this,0,0);

        var imageData = ctx.getImageData(0,0,canvas.width,canvas.height);
        var data = imageData.data;
        var r,g,b,avg;

          for(var x = 0, len = data.length; x < len; x+=4) {
            r = data[x];
            g = data[x+1];
            b = data[x+2];

            avg = Math.floor((r+g+b)/3);
            colorSum += avg;
        }

        var brightness = Math.floor(colorSum / (this.width*this.height));
        callback(thisImgID, brightness);
    }
}