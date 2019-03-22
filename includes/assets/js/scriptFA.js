// Au chargement du document complet
$(window).load(function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_propositions_determination').css('display', 'block');
  $('.zone_restaurants').css('display', 'block');

  // On lance Masonry et le scroll après avoir chargé les images
  $('.zone_propositions').masonry({
    // Options
    itemSelector: '.zone_proposition, .zone_proposition_determined, .zone_proposition_top',
    columnWidth: 200,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  $('.zone_fiches_restaurants').masonry({
    // Options
    itemSelector: '.fiche_restaurant',
    columnWidth: 400,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  // On associe une classe pour y ajouter une transition dans le css
  $('.zone_propositions').addClass('masonry');
  $('.zone_fiches_restaurants').addClass('masonry');

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 60;

  // Scroll vers l'id
  scrollToId(id, offset);

  // On applique un style pour mettre en valeur l'élément puis on le fait disparaitre au bout de 5 secondes
  if (id != null)
  {
    $('#zone_shadow_' + id).css('box-shadow', '0 3px 10px #262626');

    setTimeout(function()
    {
      $('#zone_shadow_' + id).css('box-shadow', 'none');
      $('#zone_shadow_' + id).css({transition : "box-shadow ease 0.2s"});
    }, 5000);
  }
});

// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // On lance Masonry
  $('.zone_fiches_restaurants').masonry({
    // Options
    itemSelector: '.fiche_restaurant',
    columnWidth: 400,
    fitWidth: true,
    gutter: 20,
    horizontalOrder: true
    /*transitionDuration: 0*/
  });

  // Découpe le texte si besoin
  $('.description_restaurant').wrapInner();
}

// Affiche ou masque un élément
function afficherMasquer(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "block";
  else
    document.getElementById(id).style.display = "none";
}

// Affiche la listbox des lieux
function afficherListboxLieux(id)
{
  var num        = id.substr(-1);
  var id_zone    = "zone_listbox_" + num;
  var id_select  = 'select_lieu_' + num;
  var id_annuler = 'annuler_restaurant_' + num;
  var html;

  html = '<div id="' + id_zone + '" class="zone_listbox_restaurant">';
    html += '<select id="' + id_select + '" name="select_lieu[' + num + ']" class="listbox_choix" onchange="afficherListboxRestaurants(\'' + id_select + '\', \'' + id_zone + '\', \'' + id_annuler + '\')" required>';
      html += '<option value="" hidden>Choisissez...</option>';
      $.each(listLieux, function(key, value)
      {
        html += '<option value="' + value + '">' + value + '</option>';
      });
    html += '</select>';
  html += '</div>';

  html += '<a id="' + id_annuler + '" onclick="cacherListboxRestaurants(\'' + id_zone + '\', \'' + id_annuler + '\')" class="bouton_annuler" style="margin-top: 10px;">Annuler</a>';

  $("#" + id).append(html);
}

// Affiche la listbox des restaurants associés
function afficherListboxRestaurants(id, zone, bouton)
{
  var lieu        = $('#' + id).val();
  var num         = id.substr(-1);
  var id_select_2 = 'select_restaurant_' + num;
  var html;

  if ($('#' + id_select_2).length)
    $('#' + id_select_2).remove();

  html = '<select id="' + id_select_2 + '" name="select_restaurant[' + num + ']" class="listbox_choix" required>';
    html += '<option value="" hidden>Choisissez...</option>';
    $.each(listeRestaurants[lieu], function(key, value)
    {
      html += '<option value="' + value.id + '">' + value.name + '</option>';
    });
  html += '</select>';

  $("#" + zone).append(html);
  $("#" + bouton).css('margin-top', '31px');
}

// Cache les lisbox des restaurants
function cacherListboxRestaurants(zone, bouton)
{
  var num = zone.substr(-1);

  $('#' + zone).remove();
  $('#' + bouton).remove();
  $('#choix_restaurant_' + num).css('display', 'block');
}

// Affiche les listbox des horaires
function afficherListboxHoraires(id)
{
  var html;
  var num         = id.substr(-1);
  var id_select_h = 'select_heures_' + num;
  var id_select_m = 'select_minutes_' + num;
  var id_annuler  = 'annuler_horaires_' + num;

  html = '<select id="' + id_select_h + '" name="select_heures[' + num + ']" class="listbox_horaires">';
    for (var i = 11; i < 14; i++)
    {
      if (i == 12)
        html += '<option value="' + i + '" selected>' + i + '</option>';
      else
        html += '<option value="' + i + '">' + i + '</option>';
    }
  html += '</select>';

  html += '<select id="' + id_select_m + '" name="select_minutes[' + num + ']" class="listbox_horaires">';
    for (var j = 0; j < 4; j++)
    {
      if (j == 0)
        html += '<option value="0' + j + '" selected>0' + j + '</option>';
      else
        html += '<option value="' + j*15 + '">' + j*15 + '</option>';
    }
  html += '</select>';

  html += '<a id="' + id_annuler + '" onclick="cacherListboxHoraires(\'' + id_select_h + '\',\'' + id_select_m + '\', \'' + id_annuler + '\')" class="bouton_annuler">Annuler</a>';

  $("#" + id).append(html);
}

// Cache les lisbox des horaires
function cacherListboxHoraires(heures, minutes, bouton)
{
  var num = heures.substr(-1);

  $('#' + heures).remove();
  $('#' + minutes).remove();
  $('#' + bouton).remove();
  $('#choix_horaire_' + num).css('display', 'block');
}

// Affiche les checkbox des transports
function afficherCheckboxTransports(id)
{
  var html;
  var num        = id.substr(-1);
  var id_zone    = "zone_checkbox_" + num;
  var id_check_f = 'checkbox_feet_' + num;
  var id_label_f = 'label_feet_' + num;
  var id_check_b = 'checkbox_bike_' + num;
  var id_label_b = 'label_bike_' + num;
  var id_check_t = 'checkbox_tram_' + num;
  var id_label_t = 'label_tram_' + num;
  var id_check_c = 'checkbox_car_' + num;
  var id_label_c = 'label_car_' + num;
  var id_annuler = 'annuler_transports_' + num;

  html = '<div id="' + id_zone + '" class="zone_checkbox">';
    html += '<input type="checkbox" id="' + id_check_f + '" name="checkbox_feet[' + num + ']" value="F" onchange="changeCheckedColor(\'' + id_check_f + '\', \'' + id_label_f + '\', \'label_transport_checked\', \'label_transport\');" class="checkbox_transport" />';
    html += '<label for="' + id_check_f + '" id="' + id_label_f + '" class="label_transport"><img src="../../includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';

    html += '<input type="checkbox" id="' + id_check_b + '" name="checkbox_bike[' + num + ']" value="B" onchange="changeCheckedColor(\'' + id_check_b + '\', \'' + id_label_b + '\', \'label_transport_checked\', \'label_transport\');" class="checkbox_transport" />';
    html += '<label for="' + id_check_b + '" id="' + id_label_b + '" class="label_transport"><img src="../../includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';

    html += '<input type="checkbox" id="' + id_check_t + '" name="checkbox_tram[' + num + ']" value="T" onchange="changeCheckedColor(\'' + id_check_t + '\', \'' + id_label_t + '\', \'label_transport_checked\', \'label_transport\');" class="checkbox_transport" />';
    html += '<label for="' + id_check_t + '" id="' + id_label_t + '" class="label_transport"><img src="../../includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';

    html += '<input type="checkbox" id="' + id_check_c + '" name="checkbox_car[' + num + ']" value="C" onchange="changeCheckedColor(\'' + id_check_c + '\', \'' + id_label_c + '\', \'label_transport_checked\', \'label_transport\');" class="checkbox_transport" />';
    html += '<label for="' + id_check_c + '" id="' + id_label_c + '" class="label_transport"><img src="../../includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
  html += '</div>';

  html += '<a id="' + id_annuler + '" onclick="cacherCheckboxTransports(\'' + id_zone + '\', \'' + id_annuler + '\')" class="bouton_annuler" style="margin-top: 31px;">Annuler</a>';

  $("#" + id).append(html);
}

// Cache les checkbox des transports
function cacherCheckboxTransports(zone, bouton)
{
  var num = zone.substr(-1);

  $('#' + zone).remove();
  $('#' + bouton).remove();
  $('#choix_transports_' + num).css('display', 'block');
}

// Affiche la saisie du menu
function afficherSaisieMenu(id)
{
  var html;
  var num        = id.substr(-1);
  var id_zone    = "zone_menu_" + num;
  var id_entree  = 'saisie_entree_' + num;
  var id_plat    = 'saisie_plat_' + num;
  var id_dessert = 'saisie_dessert_' + num;
  var id_annuler = 'annuler_menu_' + num;

  html = '<div id="' + id_zone + '" class="zone_saisie_menu">';
    html += '<input type="text" placeholder="Entrée" name="saisie_entree[' + num + ']" class="saisie_menu" />';
    html += '<input type="text" placeholder="Plat" name="saisie_plat[' + num + ']" class="saisie_menu" />';
    html += '<input type="text" placeholder="Dessert" name="saisie_dessert[' + num + ']" class="saisie_menu" />';
  html += '</div>';

  html += '<a id="' + id_annuler + '" onclick="cacherSaisieMenu(\'' + id_zone + '\', \'' + id_annuler + '\')" class="bouton_annuler" style="margin-top: 30px;">Annuler</a>';

  $("#" + id).append(html);
}

// Cache la saisie du menu
function cacherSaisieMenu(zone, bouton)
{
  var num = zone.substr(-1);

  $('#' + zone).remove();
  $('#' + bouton).remove();
  $('#choix_menu_' + num).css('display', 'block');
}

// Génère une nouvelle zone pour saisir un choix
function addChoice(id)
{
  var html;
  var num     = $("#" + id + " span").length / 4;
  var new_num = num + 1;

  html  = '<div class="titre_choix">Proposition ' + new_num + '</div>';

  html += '<div id="zone_listbox_restaurant_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_restaurant_' + new_num + '" onclick="afficherMasquer(\'choix_restaurant_' + new_num + '\'); afficherListboxLieux(\'zone_listbox_restaurant_' + new_num + '\');" class="bouton_choix"><span class="fond_plus">+</span>Restaurant</a>';
  html += '</div>';

  html += '<div id="zone_listbox_horaire_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_horaire_' + new_num + '" onclick="afficherMasquer(\'choix_horaire_' + new_num + '\'); afficherListboxHoraires(\'zone_listbox_horaire_' + new_num + '\')" class="bouton_choix"><span class="fond_plus">+</span>Horaire</a>';
  html += '</div>';

  html += '<div id="zone_checkbox_transports_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_transports_' + new_num + '" onclick="afficherMasquer(\'choix_transports_' + new_num + '\'); afficherCheckboxTransports(\'zone_checkbox_transports_' + new_num + '\');" class="bouton_choix"><span class="fond_plus">+</span>Transport</a>';
  html += '</div>';

  html += '<div id="zone_saisie_menu_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_menu_' + new_num + '" onclick="afficherMasquer(\'choix_menu_' + new_num + '\'); afficherSaisieMenu(\'zone_saisie_menu_' + new_num + '\');" class="bouton_choix"><span class="fond_plus">+</span>Menu</a>';
  html += '</div>';

  html += '<div class="separation_choix"></div>';

  $("#" + id).append(html);

  if (new_num == 5)
    $("#saisie_autre_choix").css("display", "none");
}

// Insère une prévisualisation de l'image sur la page
var loadFile = function(event, id)
{
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);

  // Rotation automatique
  EXIF.getData(event.target.files[0], function()
  {
    var orientation = EXIF.getTag(this, "Orientation");
    var degrees     = 0;

    // Les valeurs sont inversées par rapport à la fonction rotateImage() dans fonctions_communes.php
    switch(orientation)
    {
      case 3:
        degrees = 180;
        break;

      case 6:
        degrees = 90;
        break;

      case 8:
        degrees = -90;
        break;

      case 1:
      default:
        degrees = 0;
        break;
    }

    output.setAttribute('style','transform: rotate(' + degrees + 'deg)');
  });
};

// Fixe la couleur de fond lors de la sélection
function changeCheckedColor(id_checkbox, id_label, class_checked, class_no_check)
{
  if (document.getElementById(id_checkbox).checked == true)
    document.getElementById(id_label).className = class_checked;
  else
    document.getElementById(id_label).className = class_no_check;
}

// Fixe la couleur de fond lors de la saisie de texte
function changeTypeColor(id)
{
  if (document.getElementById(id).value != "")
  {
    document.getElementById(id).style.background = "#70d55d";
    document.getElementById(id).style.color = "white";
  }
  else
  {
    document.getElementById(id).style.background = "#e3e3e3";
    document.getElementById(id).style.color = "#262626";
  }
}

// Génère une nouvelle zone pour saisir un type
function addOtherType(id)
{
  var html;
  var length     = $("#" + id + " input").length;
  var new_length = length + 1;
  var id_type    = id + '_' + new_length;

  html = '<input type="text" placeholder="Type" value="" id="' + id_type + '" name="' + id + '[' + new_length + ']" oninput="changeTypeColor(\'' + id_type + '\')" class="type_other" />';

  $("#" + id).append(html);
}

// Affiche ou masque la zone de saisie "Autre" (insertion)
function afficherOther(select, id, name)
{
  if (document.getElementById(select).value == "other_location")
  {
    if (document.getElementById(id).style.display == "none")
    {
      document.getElementById(select).style.width = "calc(33% - 100px)";
      document.getElementById(id).style.width     = "calc(33% - 100px)";
      document.getElementById(name).style.width   = "calc(34% - 77px)";
      document.getElementById(id).style.display   = "inline-block";
      document.getElementById(id).required = true;
    }
  }
  else
  {
    document.getElementById(select).style.width = "calc(50% - 225px)";
    document.getElementById(name).style.width = "calc(50% - 20px)";
    document.getElementById(id).style.display = "none";
    document.getElementById(id).required = false;
  }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
  if (document.getElementById(select).value == "other_location")
  {
    if (document.getElementById(id).style.display == "none")
    {
      document.getElementById(id).style.display = "block";
      document.getElementById(id).required = true;
    }
  }
  else
  {
    document.getElementById(id).style.display = "none";
    document.getElementById(id).required = false;
  }
}

// Fixe la couleur de fond lors du changement de statut
function changeCheckedDay(id_checkbox, id_label, class_checked, class_no_check)
{
  if (document.getElementById(id_checkbox).checked == true)
    document.getElementById(id_label).className  = class_no_check;
  else
    document.getElementById(id_label).className  = class_checked;
}
