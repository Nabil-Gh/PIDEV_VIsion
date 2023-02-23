const libelle= document.getElementById("libelle")
const description= document.getElementById("description")
const prix= document.getElementById("prix")

const error= document.getElementById("error")
        const form= document.getElementById("form")
        const error_libelle= document.getElementById("error_libelle")
        const error_description= document.getElementById("error_descriptioon")
        const error_prix= document.getElementById("error_prix")
        


        
        
        function check(){
        form.addEventListener('submit',(e) =>{
            
          let messages = []
          //libelle
          if (libelle.value ===""){
            messages.push('Ce champ est vide')
            error_name.innerHTML = 'Ce champ est vide'
            
          }
            else if(libelle.value.length <=20 && libelle.value.length >=3 ){
              messages.push('Le nom doit contenir au moins 3 caracteres.')
              error_libelle.innerHTML = 'Le nom doit contenir au moins 3 caracteres.'
            }
            
            else if (isNaN(libelle.value)==false){
              messages.push('Ce nom est invalid')
              error_libelle.innerHTML = 'Ce nom est invalid'
            }
            //description
            if (description.value ===""){
                messages.push('Ce champ est vide')
                error_description.innerHTML = 'Ce champ est vide'
              }
                else if(description.value.length <=255 && description.value.length>=3 ){
                  messages.push('La description doit contenir au moins 3 caracteres.')
                  error_description.innerHTML = 'La description doit contenir au moins 3 caracteres.'
                }
                
                else if (isNaN(description.value)==false){
                  messages.push('description  invalid')
                  error_description.innerHTML = 'description est invalid'
                }


            //nbr 
            if (isNaN(prix.value)==true){
                messages.push('Ce prix est invalide')
                error_prix.innerHTML = 'Ce prix est invalide'
              }


            

            //prix

            
          
        })
      }