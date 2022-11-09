window.onload = () =>{
    const FiltersForm = document.querySelector("#filters");

    //On boucle sur les inputs 
    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("change", () =>{
            //Ici on intercepte les clics
            //Ensuite on récupère les données du formulaire. 
            const Form = new FormData(FiltersForm);
            // console.log(Form)
            
            // On frabrique la "queryString"
            const Params = new URLSearchParams();
            
            
            Form.forEach((value, key) => {
                Params.append(key, value);
                
                
            });

            // On Récupère l'url Active 

            const Url = new URL(window.location.href);
            

            //On lance la requête Ajax 
            fetch(Url.pathname + "?"+ Params.toString() + "&ajax=1",{
                headers : {
                    "x-Requested-With": "XMLHttpRequest"
                }
            }).then(response => {
                console.log(response)
            }).catch(e => alert(e));


        });
    });
}