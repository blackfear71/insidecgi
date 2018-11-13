// Au chargement du document complet
$(window).load(function()
{
  // On lance Masonry et le scroll après avoir chargé les images
  $('.zone_niveau_succes').masonry({
    // Options
    itemSelector: '.succes_liste',
    columnWidth: 160,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  // On associe une classe pour y ajouter une transition dans le css
  $('.zone_niveau_succes').addClass('masonry');
});

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
};

// Affiche la zone de saisie des anciens films si "partiel" est sélectionné
function afficherSaisieFilms(id, required)
{
  if (document.getElementById(id).style.display == "none")
  {
    document.getElementById(id).style.display = "block";
    document.getElementById(required).required = true;
  }
}

// Masque la zone de saisie des anciens films si "tous" est sélectionné
function masquerSaisieFilms(id, required)
{
  if (document.getElementById(id).style.display == "block")
  {
    document.getElementById(id).style.display = "none";
    document.getElementById(required).required = false;
  }
}
