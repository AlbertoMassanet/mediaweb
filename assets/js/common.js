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