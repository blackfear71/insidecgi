/***************/
/*** Actions ***/
/***************/
// Au chargement du document
$(function()
{
  /*** Actions au clic ***/
  // Affiche la saisie de propositions
  $('#saisiePropositions, #fermerPropositions').click(function()
  {
    afficherMasquer('zone_saisie_choix');
  });

  // Ajoute une nouvelle proposition
  $('#saisie_autre_choix').click(function()
  {
    addChoice('zone_choix', 'zone_marge_choix');
  });

  // Affiche la saisie lieu restaurant
  $(document).on('click', '.afficherLieu', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('choix_restaurant_', '');
    var id_listbox = 'zone_listbox_restaurant_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherListboxLieux(id_listbox);
  });

  // Affiche la saisie restaurant liée au lieu
  $(document).on('change', '.afficherRestaurant', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('select_lieu_', '');

    afficherListboxRestaurants('select_lieu_' + num, 'zone_listbox_' + num, 'annuler_restaurant_' + num);
  });

  // Affiche la saisie horaire restaurant
  $(document).on('click', '.afficherHoraire', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('choix_horaire_', '');
    var id_listbox = 'zone_listbox_horaire_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherListboxHoraires(id_listbox, id_bouton, 'create', '');
  });

  // Affiche la saisie transports restaurant
  $(document).on('click', '.afficherTransports', function()
  {
    var id_bouton   = $(this).attr('id');
    var num         = $(this).attr('id').replace('choix_transports_', '');
    var id_checkbox = 'zone_checkbox_transports_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherCheckboxTransports(id_checkbox);
  });

  // Affiche la saisie menu restaurant
  $(document).on('click', '.afficherMenu', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('choix_menu_', '');
    var id_saisie = 'zone_saisie_menu_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherSaisieMenu(id_saisie);
  });

  // Masque la saisie lieu restaurant
  $(document).on('click', '.annulerLieu', function()
  {
    var id_annuler = $(this).attr('id');
    var num        = $(this).attr('id').replace('annuler_restaurant_', '');
    var id_zone    = 'zone_listbox_' + num;

    cacherListboxRestaurants(id_zone, id_annuler);
  });

  // Masque la saisie horaire restaurant
  $(document).on('click', '.annulerHoraire', function()
  {
    var id_annuler  = $(this).attr('id');
    var num         = $(this).attr('id').replace('annuler_horaires_', '');
    var id_zone     = 'choix_horaire_' + num;
    var id_select_h = 'select_heures_' + num;
    var id_select_m = 'select_minutes_' + num;

    cacherListboxHoraires(id_zone, id_annuler, id_select_h, id_select_m);
  });

  // Masque la saisie transports restaurant
  $(document).on('click', '.annulerTransports', function()
  {
    var id_annuler = $(this).attr('id');
    var num        = $(this).attr('id').replace('annuler_transports_', '');
    var id_zone    = 'zone_checkbox_' + num;

    cacherCheckboxTransports(id_zone, id_annuler);
  });

  // Masque la saisie menu restaurant
  $(document).on('click', '.annulerMenu', function()
  {
    var id_annuler = $(this).attr('id');
    var num        = $(this).attr('id').replace('annuler_menu_', '');
    var id_zone    = 'zone_menu_' + num;

    cacherSaisieMenu(id_zone, id_annuler);
  });

  // Coche / décoche le mode de transport
  $(document).on('click', '.cocherTransport', function()
  {
    var id_check = $(this).closest('div').attr('id');

    changeCheckedColor(id_check);
  });

  // Affiche la modification d'un choix
  $('.modifierChoix').click(function()
  {
    var id_choix = $(this).attr('id').replace('modifier_', '');

    afficherMasquerNoDelay('modifier_choix_' + id_choix);
    afficherMasquerNoDelay('visualiser_choix_' + id_choix);
    initMasonry();
  });

  // Ferme la modification d'un choix
  $('.annulerChoix').click(function()
  {
    var id_choix = $(this).attr('id').replace('annuler_', '');

    afficherMasquerNoDelay('modifier_choix_' + id_choix);
    afficherMasquerNoDelay('visualiser_choix_' + id_choix);
    initMasonry();
  });

  // Affiche la modification de l'horaire d'un choix
  $(document).on('click', '.afficherHoraireUpdate', function()
  {
    var id_bouton  = $(this).attr('id');
    var num        = $(this).attr('id').replace('update_horaire_', '');
    var id_listbox = 'zone_update_listbox_horaire_' + num;

    afficherMasquerNoDelay(id_bouton);
    afficherListboxHoraires(id_listbox, id_bouton, 'update', num);
  });

  // Masque la modification de l'horaire d'un choix
  $(document).on('click', '.annulerHoraireUpdate', function()
  {
    var id_annuler  = $(this).attr('id');
    var num         = $(this).attr('id').replace('annuler_horaires_', '');
    var id_zone     = 'update_horaire_' + num;
    var id_select_h = 'select_heures_' + num;
    var id_select_m = 'select_minutes_' + num;

    cacherListboxHoraires(id_zone, id_annuler, id_select_h, id_select_m);
  });

  // Affiche les détails d'une proposition
  $('.afficherDetails').click(function()
  {
    var id_details = $(this).attr('id').replace('afficher_details_', '');

    showDetails('zone_details', id_details);
  });

  // Ferme les détails d'une proposition
  $('#fermerDetails').click(function()
  {
    afficherMasquer('zone_details');
  });

  // Affiche la saisie de restaurant
  $('#saisieRestaurant, #fermerRestaurant').click(function()
  {
    afficherMasquer('zone_add_restaurant');
  });

  // Change le statut d'un jour d'ouverture (saisie)
  $('.checkDay').click(function()
  {
    var id_jour = $(this).attr('id').split('_');
    var day     = id_jour[id_jour.length - 1];

    changeCheckedDay('saisie_checkbox_ouverture_' + day, 'saisie_label_ouverture_' + day, 'label_jour_checked', 'label_jour');
  });

  // Ajoute un champ de saisie libre type de restaurant (saisie)
  $('#addType').click(function()
  {
    addOtherType('types_restaurants');
  });

  // Change la couleur des checkbox types de restaurant (saisie & modification)
  $('.checkType, .checkTypeUpdate').click(function()
  {
    var id_type = $(this).closest('div').attr('id');

    changeCheckedColor(id_type);
  });

  // Scroll vers un lieu
  $('.lienLieu').click(function()
  {
    var id_lieu = $(this).attr('id').replace('link_', '');
    var offset  = 20;
    var shadow  = false;

    scrollToId(id_lieu, offset, shadow);
  });

  // Affiche la description longue d'un restaurant
  $('.descriptionRestaurant').click(function()
  {
    var id_restaurant = $(this).attr('id').replace('description_', '');

    afficherMasquerNoDelay('short_description_' + id_restaurant);
    afficherMasquerNoDelay('long_description_' + id_restaurant);
    initMasonry();
  });

  // Affiche la zone de modification d'un restaurant
  $('.modifierRestaurant').click(function()
  {
    var id_restaurant = $(this).attr('id').replace('modifier_', '');

    afficherMasquerNoDelay('modifier_restaurant_' + id_restaurant);
    afficherMasquerNoDelay('visualiser_restaurant_' + id_restaurant);
    initMasonry();
  });

  // Ferme la zone de modification d'un restaurant
  $('.annulerRestaurant').click(function()
  {
    var id_restaurant = $(this).attr('id').replace('annuler_', '');

    afficherMasquerNoDelay('modifier_restaurant_' + id_restaurant);
    afficherMasquerNoDelay('visualiser_restaurant_' + id_restaurant);
    initMasonry();
  });

  // Change le statut d'un jour d'ouverture (modification)
  $('.checkDayUpdate').click(function()
  {
    var id_jour = $(this).attr('id').split('_');
    var num     = id_jour[id_jour.length - 1];
    var day     = id_jour[id_jour.length - 2];

    changeCheckedDay('checkbox_update_ouverture_' + day + '_' + num, 'label_update_ouverture_' + day + '_' + num, 'update_label_jour_checked', 'update_label_jour');
  });

  // Ajoute un champ de saisie libre type de restaurant (modification)
  $('.addTypeUpdate').click(function()
  {
    var id_restaurant = $(this).attr('id').replace('type_update_', '');

    addOtherType('update_types_restaurants_' + id_restaurant);
  });

  /*** Actions au changement ***/
  // Change la couleur du type à la saisie
  $(document).on('input', '.saisieType', function()
  {
    id_type = $(this).attr('id');

    changeTypeColor(id_type);
  });

  // Affiche la saisie "Autre" (saisie)
  $('#saisie_location').on('change', function()
  {
    afficherOther('saisie_location', 'saisie_other_location', 'saisie_nom');
  });

  // Charge l'image (saisie)
  $('.loadSaisieRestaurant').on('change', function()
  {
    loadFile(event, 'img_restaurant_saisie');
  });

  // Affiche la saisie "Autre" (modification)
  $('.changeLieu').on('change', function()
  {
    id_restaurant = $(this).attr('id').replace('update_location_', '');

    afficherModifierOther('update_location_' + id_restaurant, 'other_location_' + id_restaurant, 'saisie_nom');
  });

  // Charge l'image (modification)
  $('.loadModifierRestaurant').on('change', function()
  {
    id_restaurant = $(this).attr('id').replace('modifier_image_', '');

    loadFile(event, 'img_restaurant_' + id_restaurant);
  });
});

/************************/
/*** Masonry & scroll ***/
/************************/
// Au chargement du document complet
$(window).on('load', function()
{
  // On n'affiche la zone qu'à ce moment là, sinon le premier titre apparait puis la suite de la page
  $('.zone_propositions_determination').css('display', 'block');
  $('.zone_restaurants').css('display', 'block');

  // Calcul automatique des tailles des zones
  tailleAutoZones();

  // Masonry (Propositions)
  if ($('.zone_propositions').length)
  {
    $('.zone_propositions').masonry().masonry('destroy');

    $('.zone_propositions').masonry({
      // Options
      itemSelector: '.zone_proposition, #zone_solo_no_votes, .zone_proposition_determined, .zone_proposition_top, .zone_proposition_resume',
      columnWidth: 200,
      fitWidth: true,
      gutter: 30,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_propositions').addClass('masonry');
  }

  // Masonry (Restaurants)
  if ($('.zone_fiches_restaurants').length)
  {
    $('.zone_fiches_restaurants').masonry().masonry('destroy');

    $('.zone_fiches_restaurants').masonry({
      // Options
      itemSelector: '.fiche_restaurant',
      columnWidth: 500,
      fitWidth: true,
      gutter: 30,
      horizontalOrder: true
    });

    // On associe une classe pour y ajouter une transition dans le css
    $('.zone_fiches_restaurants').addClass('masonry');
  }

  // Déclenchement du scroll : on récupère l'id de l'ancre dans l'url (fonction JS)
  var id     = $_GET('anchor');
  var offset = 60;
  var shadow = true;

  // Scroll vers l'id
  scrollToId(id, offset, shadow);
});

/*****************/
/*** Fonctions ***/
/*****************/
// Initialisation manuelle de "Masonry"
function initMasonry()
{
  // Masonry (Propositions)
  $('.zone_propositions').masonry({
    // Options
    itemSelector: '.zone_proposition, .zone_proposition_determined, .zone_proposition_top, .zone_proposition_resume',
    columnWidth: 200,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  // Masonry (Restaurants)
  $('.zone_fiches_restaurants').masonry({
    // Options
    itemSelector: '.fiche_restaurant',
    columnWidth: 500,
    fitWidth: true,
    gutter: 30,
    horizontalOrder: true
  });

  // Découpe le texte si besoin
  $('.description_restaurant').wrapInner();
}

// Calcul les tailles des zones automatiquement
function tailleAutoZones()
{
  // Taille de la zone bande à part / sans votes en fonction de la zone suivante si elle existe
  if ($('#zone_first').length && ($('#zone_solo').length || $('#zone_no_votes').length))
  {
    var hauteur_min = $('#zone_first').height();

    $('#zone_solo_no_votes').css('min-height', hauteur_min);

    // Les 2 zones sont présentes
    if ($('#zone_solo').length && $('#zone_no_votes').length)
      $('#zone_no_votes').css('min-height', hauteur_min - $('#zone_solo').height() - 20);

    // Il n'y a que la zone "bande à part"
    if (!$('#zone_no_votes').length)
      $('#zone_solo').css('min-height', hauteur_min);

    // Il n'y a que la zone des non votants
    if (!$('#zone_solo').length)
      $('#zone_no_votes').css('min-height', hauteur_min);
  }

  // Taille des zones de résumé en fonction de la plus grande
  var day_height     = $('.jour_semaine').height() + 10;
  var text_height    = $('.no_proposal').height();
  var max_height     = Math.max($('#zone_resume_lundi').height(), $('#zone_resume_mardi').height(), $('#zone_resume_mercredi').height(), $('#zone_resume_jeudi').height(), $('#zone_resume_vendredi').height());
  var calcul_padding = (max_height - (day_height + text_height + 10)) / 2;

  $('.no_proposal').css('padding-top', calcul_padding);
  $('.no_proposal').css('padding-bottom', calcul_padding);
  $('.zone_proposition_resume').css('min-height', max_height);
}

// Affiche ou masque un élément (délai 200ms)
function afficherMasquer(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(200);
  else
    $('#' + id).fadeOut(200);
}

// Affiche ou masque un élément (délai 0s)
function afficherMasquerNoDelay(id)
{
  if ($('#' + id).css('display') == "none")
    $('#' + id).fadeIn(0);
  else
    $('#' + id).fadeOut(0);
}

// Affiche la listbox des lieux
function afficherListboxLieux(id)
{
  var num        = id.substr(-1);
  var id_zone    = 'zone_listbox_' + num;
  var id_select  = 'select_lieu_' + num;
  var id_annuler = 'annuler_restaurant_' + num;
  var html;

  html = '<div id="' + id_zone + '" class="zone_listbox_restaurant">';
    html += '<select id="' + id_select + '" name="select_lieu[' + num + ']" class="listbox_choix afficherRestaurant" required>';
      html += '<option value="" hidden>Choisissez...</option>';
      $.each(listeLieux, function(key, value)
      {
        html += '<option value="' + value + '">' + value + '</option>';
      });
    html += '</select>';
  html += '</div>';

  html += '<a id="' + id_annuler + '" class="bouton_annuler annulerLieu" style="margin-top: 10px;">Annuler</a>';

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
function afficherListboxHoraires(id, zone, type, idChoix)
{
  var html;
  var num;
  var name_select_h;
  var name_select_m;
  var class_listbox;
  var class_bouton;

  if (type == "create")
  {
    num           = id.substr(-1);
    name_select_h = 'select_heures[' + num + ']';
    name_select_m = 'select_minutes[' + num + ']';
    class_listbox = "listbox_horaires";
    class_bouton  = "bouton_annuler annulerHoraire";
  }
  else if (type == "update")
  {
    num           = idChoix;
    name_select_h = 'select_heures_' + num;
    name_select_m = 'select_minutes_' + num;
    class_listbox = "listbox_horaires_update";
    class_bouton  = "bouton_annuler_update annulerHoraireUpdate";
  }

  var id_select_h = 'select_heures_' + num;
  var id_select_m = 'select_minutes_' + num;
  var id_annuler  = 'annuler_horaires_' + num;
  var id_zone     = zone;

  html = '<select id="' + id_select_h + '" name="' + name_select_h + '" class="' + class_listbox + '">';
    for (var i = 11; i < 14; i++)
    {
      if (i == 12)
        html += '<option value="' + i + '" selected>' + i + '</option>';
      else
        html += '<option value="' + i + '">' + i + '</option>';
    }
  html += '</select>';

  html += '<select id="' + id_select_m + '" name="' + name_select_m + '" class="' + class_listbox + '">';
    for (var j = 0; j < 4; j++)
    {
      if (j == 0)
        html += '<option value="0' + j + '" selected>0' + j + '</option>';
      else
        html += '<option value="' + j * 15 + '">' + j * 15 + '</option>';
    }
  html += '</select>';

  html += '<a id="' + id_annuler + '" class="' + class_bouton + '">Annuler</a>';

  $("#" + id).append(html);
}

// Cache les lisbox des horaires
function cacherListboxHoraires(zone, bouton, heures, minutes)
{
  var num = heures.substr(-1);

  $('#' + heures).remove();
  $('#' + minutes).remove();
  $('#' + bouton).remove();
  $('#' + zone).css('display', 'block');
}

// Affiche les checkbox des transports
function afficherCheckboxTransports(id)
{
  var html;
  var num        = id.substr(-1);
  var id_zone    = "zone_checkbox_" + num;
  var id_check_f = 'checkbox_feet_' + num;
  var id_check_b = 'checkbox_bike_' + num;
  var id_check_t = 'checkbox_tram_' + num;
  var id_check_c = 'checkbox_car_' + num;
  var id_annuler = 'annuler_transports_' + num;

  html = '<div id="' + id_zone + '" class="zone_checkbox">';
    html += '<div id="bouton_' + id_check_f + '" class="switch_transport">';
      html += '<input id="' + id_check_f + '" type="checkbox" value="F" name="checkbox_feet[' + num + ']" />';
      html += '<label for="' + id_check_f + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';
    html += '</div>';

    html += '<div id="bouton_' + id_check_b + '" class="switch_transport">';
      html += '<input id="' + id_check_b + '" type="checkbox" value="B" name="checkbox_bike[' + num + ']" />';
      html += '<label for="' + id_check_b + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';
    html += '</div>';

    html += '<div id="bouton_' + id_check_t + '" class="switch_transport">';
      html += '<input id="' + id_check_t + '" type="checkbox" value="T" name="checkbox_tram[' + num + ']" />';
      html += '<label for="' + id_check_t + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';
    html += '</div>';

    html += '<div id="bouton_' + id_check_c + '" class="switch_transport">';
      html += '<input id="' + id_check_c + '" type="checkbox" value="C" name="checkbox_car[' + num + ']" />';
      html += '<label for="' + id_check_c + '" class="label_switch_transport cocherTransport"><img src="/inside/includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
    html += '</div>';
  html += '</div>';

  html += '<a id="' + id_annuler + '" class="bouton_annuler annulerTransports" style="margin-top: 25px;">Annuler</a>';

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

  html += '<a id="' + id_annuler + '" class="bouton_annuler annulerMenu" style="margin-top: 27px;">Annuler</a>';

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
function addChoice(id, zone)
{
  var html;
  var num     = $("#" + id + " span").length / 4;
  var new_num = num + 1;
  var icon;

  // Sélection du logo
  switch (new_num)
  {
    case 2:
      icon = "location_grey";
      break;

    case 3:
      icon = "menu_grey";
      break;

    case 4:
      icon = "feet";
      break;

    case 5:
      icon = "restaurants_grey";
      break;

    default:
      icon = "propositions_grey";
      break;
  }

  // On ajoute de nouveaux champs de saisie
  html  = '<div class="titre_choix"><img src="/inside/includes/icons/foodadvisor/' + icon + '.png" alt="' + icon + '" class="logo_proposition" />Proposition ' + new_num + '</div>';

  html += '<div id="zone_listbox_restaurant_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_restaurant_' + new_num + '" class="bouton_choix afficherLieu"><span class="fond_plus">+</span>Restaurant</a>';
  html += '</div>';

  html += '<div id="zone_listbox_horaire_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_horaire_' + new_num + '" class="bouton_choix afficherHoraire"><span class="fond_plus">+</span>Horaire</a>';
  html += '</div>';

  html += '<div id="zone_checkbox_transports_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_transports_' + new_num + '" class="bouton_choix afficherTransports"><span class="fond_plus">+</span>Transport</a>';
  html += '</div>';

  html += '<div id="zone_saisie_menu_' + new_num + '" class="zone_listbox">';
    html += '<a id="choix_menu_' + new_num + '" class="bouton_choix afficherMenu"><span class="fond_plus">+</span>Menu</a>';
  html += '</div>';

  html += '<div class="separation_choix"></div>';

  $("#" + id).append(html);

  // On remonte la zone de saisie sur l'écran
  var marge = 200 - num * 25 + 'px';
  $('#' + zone).animate({marginTop: marge}, 400);

  // On supprime le bouton d'ajout si on a 5 propositions
  if (new_num == 5)
    $('#saisie_autre_choix').css('display', 'none');
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

// Change la couleur des checkbox (saisie restaurant)
function changeCheckedColor(input)
{
  if ($('#' + input).children('input').prop('checked'))
    $('#' + input).removeClass('bouton_checked');
  else
    $('#' + input).addClass('bouton_checked');
}

// Change la couleur de fond lors de la saisie de texte
function changeTypeColor(id)
{
  if ($('#' + id).val() != "")
  {
    $('#' + id).css('background-color', '#70d55d');
    $('#' + id).css('color', 'white');
  }
  else
  {
    $('#' + id).css('background-color', '#e3e3e3');
    $('#' + id).css('color', '#262626');
  }
}

// Génère une nouvelle zone pour saisir un type
function addOtherType(id)
{
  var html;
  var length     = $("#" + id + " input").length;
  var new_length = length + 1;
  var id_type    = id + '_' + new_length;

  html = '<input type="text" placeholder="Type" value="" id="' + id_type + '" name="' + id + '[' + new_length + ']" class="type_other saisieType" />';

  $("#" + id).append(html);
}

// Affiche ou masque la zone de saisie lieu "Autre" (insertion)
function afficherOther(select, id, name)
{
  if ($('#' + select).val() == 'other_location')
  {
    if ($('#' + id).css('display') == 'none')
    {
      $('#' + select).css('width', '200px');
      $('#' + name).css('width', 'calc(100% - 670px)');
      $('#' + id).css('width', '200px');
      $('#' + id).css('display', 'inline-block');
      $('#' + id).prop('required', true);
    }
  }
  else
  {
    $('#' + select).css('width', '180px');
    $('#' + name).css('width', 'calc(100% - 420px)');
    $('#' + id).css('display', 'none');
    $('#' + id).prop('required', false);
  }
}

// Affiche ou masque la zone de saisie "Autre" (modification)
function afficherModifierOther(select, id)
{
  if ($('#' + select).val() == 'other_location')
  {
    if ($('#' + id).css('display') == 'none')
    {
      $('#' + id).css('display', 'block');
      $('#' + id).prop('required', true);
    }
  }
  else
  {
    $('#' + id).css('display', 'none');
    $('#' + id).prop('required', false);
  }
}

// Fixe la couleur de fond lors du changement de statut
function changeCheckedDay(id_checkbox, id_label, class_checked, class_no_check)
{
  if ($('#' + id_checkbox).prop('checked') == true)
  {
    $('#' + id_label).removeClass(class_checked);
    $('#' + id_label).addClass(class_no_check);
  }
  else
  {
    $('#' + id_label).addClass(class_checked);
    $('#' + id_label).removeClass(class_no_check);
  }
}

function showDetails(zone, id)
{
  // Modification des données
  var details = detailsPropositions[id];

  /*******************/
  /*** Restaurant ***/
  /*******************/
  // Lien image
  $('#lien_details_proposition').attr('href', 'restaurants.php?action=goConsulter&anchor=' + details['id_restaurant']);

  // Image restaurant
  if (details['picture'] != "")
    $('#image_details_proposition').attr('src', '../../includes/images/foodadvisor/' + details['picture']);
  else
    $('#image_details_proposition').attr('src', '../../includes/icons/foodadvisor/restaurants.png');

  // Nom du restaurant
  $('#nom_details_proposition').html(details['name']);

  // Jours d'ouverture
  var opened = details['opened'].split(';');

  $.each(opened, function(key, value)
  {
    if (value != "")
    {
      if (value == "Y")
        $('#jour_details_proposition_' + key).addClass('jour_oui_fa');
      else
        $('#jour_details_proposition_' + key).addClass('jour_non_fa');
    }
  });

  // Prix
  if (details['min_price'] != "" && details['max_price'] != "")
  {
    var price;

    if (details['min_price'] == details['max_price'])
      price = "Prix ~ " + details['min_price'] + "€";
    else
      price = "Prix " + details['min_price'] + " - " + details['max_price'] + "€";

    $('.zone_price_details').css('display', 'block');
    $('#prix_details_proposition').html(price);
  }
  else
  {
    $('.zone_price_details').css('display', 'none');
    $('#prix_details_proposition').html();
  }

  // Lieu
  $('#lieu_details_proposition').html(details['location']);

  // Nombre de participants
  var nbParticipants;

  if (details['nb_participants'] == 1)
    nbParticipants = details['nb_participants'] + " participant";
  else
    nbParticipants = details['nb_participants'] + " participants";

  $('#participants_details_proposition').html(nbParticipants);

  // Type de restaurant
  $('#types_details_proposition').empty();

  if (details['types'] != "")
  {
    $('#types_details_proposition').css('display', 'block');

    var types = details['types'].split(';');

    $.each(types, function()
    {
      if (this != "")
        $('#types_details_proposition').append('<span class="horaire_proposition">' + this + '</span>');
    });
  }
  else
  {
    $('#types_details_proposition').css('display', 'none');
    $('#types_details_proposition').html();
  }

  // Appelant
  if (details['phone'] != "" || details['determined'] == "Y")
  {
    $('.zone_caller_details').css('display', 'block');

    if (details['phone'] != "")
      $('#telephone_details_proposition').html(details['phone']);

    if (details['determined'] == "Y")
    {
      $('#caller_details_propositions').parent().css('display', 'inline-block');

      if (details['avatar'] != "")
      {
        $('#caller_details_propositions').attr('src', '../../includes/images/profil/avatars/' + details['avatar']);
        $('#caller_details_propositions').attr('title', details['caller']);
      }
      else
      {
        $('#caller_details_propositions').attr('src', '../../includes/icons/common/default.png');
        $('#caller_details_propositions').attr('title', details['caller']);
      }
    }
    else
    {
      $('#caller_details_propositions').parent().css('display', 'none');
      $('#caller_details_propositions').attr('src', '../../includes/icons/common/default.png');
      $('#caller_details_propositions').attr('title', 'avatar');
    }
  }
  else
  {
    $('.zone_caller_details').css('display', 'none');
    $('#caller_details_propositions').parent().css('display', 'none');
    $('#caller_details_propositions').attr('src', '../../includes/icons/common/default.png');
    $('#caller_details_propositions').attr('title', 'avatar');
  }

  // Liens
  if (details['website'] != "" || details['plan'] != "")
  {
    $('.zone_liens_details').css('display', 'block');

    if (details['website'] == "")
    {
      $('#website_details_proposition').css('display', 'none');
      $('#website_details_proposition').attr('href', '');
    }
    else
    {
      $('#website_details_proposition').css('display', 'inline-block');
      $('#website_details_proposition').attr('href', details['website']);
    }

    if (details['plan'] == "")
    {
      $('#plan_details_proposition').css('display', 'none');
      $('#plan_details_proposition').attr('href', '');
    }
    else
    {
      $('#plan_details_proposition').css('display', 'inline-block');
      $('#plan_details_proposition').attr('href', details['plan']);
    }
  }
  else
  {
    $('.zone_liens_details').css('display', 'none');
    $('#website_details_proposition').attr('href', '');
    $('#plan_details_proposition').attr('href', '');
  }

  // Bouton réservation (si on a participé)
  var participe = false;

  if (details['reserved'] != "Y")
  {
    $.each(details['details'], function()
    {
      if (userSession == this['identifiant'])
      {
        participe = true;
        return false;
      }
    });
  }

  if (participe == true)
  {
    $('#reserver_details_proposition').css('display', 'block');
    $('#reserver_details_proposition').attr('action', 'foodadvisor.php?action=doReserver');
  }
  else
  {
    $('#reserver_details_proposition').css('display', 'none');
    $('#reserver_details_proposition').attr('action', '');
  }

  // Indicateur réservation
  if (details['reserved'] == "Y")
    $('#reserved_details_proposition').css('display', 'block');
  else
    $('#reserved_details_proposition').css('display', 'none');

  // Bouton annulation réservation (si on a participé)
  var reserved = false;

  if (details['reserved'] == "Y")
  {
    if (userSession == details['caller'])
      reserved = true;
  }

  if (reserved == true)
  {
    $('#annuler_details_proposition').css('display', 'block');
    $('#annuler_details_proposition').attr('action', 'foodadvisor.php?action=doAnnulerReserver');
  }
  else
  {
    $('#annuler_details_proposition').css('display', 'none');
    $('#annuler_details_proposition').attr('action', '');
  }

  // Id restaurant
  if (participe == true)
    $('#reserver_details_proposition > input[name=id_restaurant]').val(id);
  else
    $('#reserver_details_proposition > input[name=id_restaurant]').val('');

  if (reserved == true)
    $('#annuler_details_proposition > input[name=id_restaurant]').val(id);
  else
    $('#annuler_details_proposition > input[name=id_restaurant]').val('');

  // On cache la zone si tout est vide
  if (participe == false && reserved == false && details['reserved'] != "Y")
    $('#indicateurs_details_proposition').css('display', 'none');
  else
    $('#indicateurs_details_proposition').css('display', 'block');

  /********************/
  /*** Participants ***/
  /********************/
  $('#top_details_proposition').empty();
  var ligne;
  var transports;

  $.each(details['details'], function()
  {
    ligne      = '';
    transports = '';

    ligne += '<div class="zone_details_user_top">';

    // Avatar
    if (this['avatar'] != "")
      ligne += '<img src="../../includes/images/profil/avatars/' + this['avatar'] + '" alt="avatar" title="' + this['pseudo'] + '" class="avatar_details" />';
    else
      ligne += '<img src="../../includes/icons/common/default.png" alt="avatar" title="' + this['pseudo'] + '" class="avatar_details" />';

    // Pseudo
    ligne += '<div class="pseudo_details">' + this['pseudo'] + '</div>';

    // Transports
    ligne += '<div class="zone_details_transports">';
      if (this['transports'] != "")
      {
        transports = this['transports'].split(';');

        $.each(transports, function(key, value)
        {
          switch (value)
          {
            case 'F':
              ligne += '<img src="../../includes/icons/foodadvisor/feet.png" alt="feet" class="icone_details" />';
              break;

            case 'B':
              ligne += '<img src="../../includes/icons/foodadvisor/bike.png" alt="bike" class="icone_details" />';
              break;

            case 'T':
              ligne += '<img src="../../includes/icons/foodadvisor/tram.png" alt="tram" class="icone_details" />';
              break;

            case 'C':
              ligne += '<img src="../../includes/icons/foodadvisor/car.png" alt="car" class="icone_details" />';
              break;

            default:
              break;
          }
        });
      }
    ligne += '</div>';

    // Horaires
    if (this['horaire'] != "")
      ligne += '<div class="horaire_details">' + formatTimeForDisplayLight(this['horaire']) + '</div>';

    ligne += '</div>';

    $('#top_details_proposition').append(ligne);
  });

  /*************/
  /*** Menus ***/
  /*************/
  $('.zone_details_user_bottom').empty();
  var menuPresent = false;
  var colonne;
  var menu;

  $.each(details['details'], function()
  {
    colonne = '';

    if (this['menu'] != ';;;')
    {
      menuPresent = true;
      menu        = this['menu'].split(';');

      colonne += '<div class="zone_details_user_menu">';

      // Avatar
      if (this['avatar'] != "")
        colonne += '<img src="../../includes/images/profil/avatars/' + this['avatar'] + '" alt="avatar" title="' + this['pseudo'] + '" class="avatar_menus" />';
      else
        colonne += '<img src="../../includes/icons/common/default.png" alt="avatar" title="' + this['pseudo'] + '" class="avatar_menus" />';

      // Entrée
      if (menu[0] != "")
      {
        colonne += '<div class="zone_menu_mes_choix">';
          colonne += '<span class="titre_texte_mon_choix">Entrée</span>';
          colonne += '<div class="texte_mon_choix">' + menu[0] + '</div>';
        colonne += '</div>';
      }

      // Plat
      if (menu[1] != "")
      {
        colonne += '<div class="zone_menu_mes_choix">';
          colonne += '<span class="titre_texte_mon_choix">Plat</span>';
          colonne += '<div class="texte_mon_choix">' + menu[1] + '</div>';
        colonne += '</div>';
      }

      // Dessert
      if (menu[2] != "")
      {
        colonne += '<div class="zone_menu_mes_choix">';
          colonne += '<span class="titre_texte_mon_choix">Dessert</span>';
          colonne += '<div class="texte_mon_choix">' + menu[2] + '</div>';
        colonne += '</div>';
      }

      colonne += '</div>';

      $('.zone_details_user_bottom').append(colonne);
    }
  });

  // Pas de menus
  if (menuPresent == false)
    $('.zone_details_user_bottom').html('<div class="no_menu_details">Pas de menus proposés pour ce choix.</div>');

  // Affichage de la zone
  afficherMasquer(zone);
}

// Formate l'horaire
function formatTimeForDisplayLight(time)
{
  var horaire;

  if (time.length == 6 || time.length == 4)
    horaire = time.substr(0, 2) + ':' + time.substr(2, 2);
  else
    horaire = time;

  return horaire;
}
