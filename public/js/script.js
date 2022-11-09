
// FONCTION PLAY AND PAUS VIDEO HOME ACCEUIL  

function modifyAction(el) { // CREATION DE LA FONCTION JS 
    let x = document.getElementById("videoHome"); // RECUPERATION DE LID DE LA VIDEO POUR CIBLER 
    console.log(el);   
    if (el.textContent == "▷") {
      el.textContent = 'II';
      x.play();
    } else {
      el.textContent = '▷';
      x.pause();
    }
  }

  const el = document.querySelector("#lecteur");
  el.addEventListener("click", function(){modifyAction(el)}, false);


  // MENU RESPONSIVE 
  
  // je stocke l'element du DOM #burger dans une variable
const burger = document.querySelector("#burger")
// je stocke UNE COPIE de l'element du DOM #menu dans une variable
const menuMobile = document.querySelector("#menu").cloneNode(true)
// je CHANGE l'attribut id de la copie (#menu -> #menu-mobile)
menuMobile.setAttribute("id", "menu-mobile")

// je crée un nouvel élément du DOM (span)
let closeBtn = document.createElement("span")
// j'inscris du HTML à l'intérieur du span (pour faire apparaitre la petite croix)
closeBtn.innerHTML = '<i class="fas fa-times"></i>'
// j'ajoute la classe CSS "close-btn" au span
closeBtn.classList.add("close-btn")
// je place la span au début du contenu du menu
menuMobile.prepend(closeBtn)

//au clic sur le bouton closeBtn (la petite croix), je retire le menu mobile de l'affichage
//aka : je retire nav#menu-mobile du DOM
closeBtn.addEventListener("click", function(){
    menuMobile.remove()
})

//même chose en cliquant sur la nav, MAIS je vérifie que je ne clique pas sur un des enfants de la nav
menuMobile.addEventListener("click", function(event){
    if(event.target == this)//si l'évenement a pour cible la nav ELLE-MEME, c'est OK
        menuMobile.remove()
})

//document.querySelector("#burger").addEventListener("click", ...)
burger.addEventListener("click", function(){
    document.querySelector("body").prepend(menuMobile)
})

//lorsque la fenêtre atteint une largeur de 820px ou plus, on retire le menu mobile du DOM
window.addEventListener('resize', function(){
    if(window.innerWidth >= 820){
        menuMobile.remove()
    }
});

menuMobile.querySelector(".dropmenu").addEventListener("click", function(){
    this.querySelector(".dropdown").classList.toggle("dropped")
})
