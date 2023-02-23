const name= document.getElementById("nom")
const lieu= document.getElementById("lieu")
const nbr_P= document.getElementById("nbr_P")
const date_D= document.getElementById("date_D")
const date_F= document.getElementById("date_F")
const prix_billet= document.getElementById("prix_billet")
const type= document.getElementById("type")
const error= document.getElementById("error")
        const form= document.getElementById("form")
        const error_name= document.getElementById("error_name")
        const error_lieu= document.getElementById("error_lieu")
        const error_datedeb= document.getElementById("error_datedeb")
        const error_datefin= document.getElementById("error_datefin")
        const error_nbr= document.getElementById("error_nbr")
        const error_type= document.getElementById("error_type")
        const error_prix= document.getElementById("error_prix")


        
        
        function check(){
        form.addEventListener('submit',(e) =>{
            
          let messages = []
          //nom
          if (name.value ===""){
            messages.push('Ce champ est vide')
            error_name.innerHTML = 'Ce champ est vide'
            
          }
            else if(name.value.length <=2 && name.value.length >=1 ){
              messages.push('Le nom doit contenir au moins 3 caracteres.')
              error_name.innerHTML = 'Le nom doit contenir au moins 3 caracteres.'
            }
            
            else if (isNaN(name.value)==false){
              messages.push('Ce nom est invalid')
              error_name.innerHTML = 'Ce nom est invalid'
            }
            //lieu
            if (lieu.value ===""){
                messages.push('Ce champ est vide')
                error_lieu.innerHTML = 'Ce champ est vide'
              }
                else if(lieu.value.length <=3 && lieu.value.length>=1 ){
                  messages.push('Le lieu doit contenir au moins 3 caracteres.')
                  error_lieu.innerHTML = 'Le lieu doit contenir au moins 3 caracteres.'
                }
                
                else if (isNaN(lieu.value)==false){
                  messages.push('Ce lieu est invalid')
                  error_lieu.innerHTML = 'Ce lieu est invalid'
                }


            //nbr 
            if (isNaN(nbr_P.value)==true){
                messages.push('Ce nom est invalide')
                error_nbr.innerHTML = 'Ce nombre est invalide'
              }


            

            //prix

            if (isNaN(prix_billet.value)==true){
                messages.push('Ce prix est invalid')
                error_prix.innerHTML = 'Ce prix est invalid'
              }

              if(date_F.value < date_D.value){
                messages.push('Ce prix est invalid')
                error_datedeb.innerHTML = 'Date debut ne peut pas étre supérieure à la date fin'
                error_datefin.innerHTML = 'Date fin ne peut pas étre inférieure à la date debut'
              }
              else{
                error_datedeb.innerHTML = ''
                error_datefin.innerHTML = ''
              }

            
            if(messages.length >0){
              e.preventDefault()
              error.Element.innerText =messages.join(', ')
            }

          
        })
      }