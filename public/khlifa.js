const openModalBtn = document.getElementById('open-modal');
const modal = document.getElementById('modal');
const docName = document.getElementById('doc-name');
const docFile = document.getElementById('doc-file');
const closeBtn = document.getElementsByClassName('close')[0];

// Ajout d'un événement pour ouvrir le modal lorsqu'on clique sur le bouton
openModalBtn.addEventListener('click', () => {
  modal.style.display = 'block';
});

// Ajout d'un événement pour fermer le modal lorsqu'on clique sur le bouton de fermeture
closeBtn.addEventListener('click', () => {
  modal.style.display = 'none';
});

// Ajout d'un événement pour fermer le modal lorsqu'on clique en dehors de celui-ci
window.addEventListener('click', (event) => {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
});

// Fonction pour afficher les informations du document dans le modal
function displayDocInfo(doc) {
  docName.innerText = doc.nom;
  docFile.innerText = doc.fichier;
}

// Exemple d'utilisation pour afficher les informations d'un document


displayDocInfo(doc);