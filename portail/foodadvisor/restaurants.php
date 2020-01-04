<?php
  /******************************
  *** Les enfants ! A table ! ***
  *******************************
  Fonctionnalités :
  - Consultation restaurants
  - Ajout restaurants
  - Modification restaurants
  - Suppression restaurants
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_commun.php');
  include_once('modele/metier_restaurants.php');

  // Initialisation sauvegarde saisie
  if ((!isset($_SESSION['alerts']['wrong_phone_number']) OR $_SESSION['alerts']['wrong_phone_number'] != true)
  AND (!isset($_SESSION['alerts']['wrong_price_min'])    OR $_SESSION['alerts']['wrong_price_min']    != true)
  AND (!isset($_SESSION['alerts']['wrong_price_max'])    OR $_SESSION['alerts']['wrong_price_max']    != true)
  AND (!isset($_SESSION['alerts']['miss_price'])         OR $_SESSION['alerts']['miss_price']         != true)
  AND (!isset($_SESSION['alerts']['price_max_min'])      OR $_SESSION['alerts']['price_max_min']      != true)
  AND (!isset($_SESSION['alerts']['file_too_big'])       OR $_SESSION['alerts']['file_too_big']       != true)
  AND (!isset($_SESSION['alerts']['temp_not_found'])     OR $_SESSION['alerts']['temp_not_found']     != true)
  AND (!isset($_SESSION['alerts']['wrong_file_type'])    OR $_SESSION['alerts']['wrong_file_type']    != true)
  AND (!isset($_SESSION['alerts']['wrong_file'])         OR $_SESSION['alerts']['wrong_file']         != true))
  {
    unset($_SESSION['save']);

    $_SESSION['save']['name_restaurant']        = "";
    $_SESSION['save']['prix_min']               = "";
    $_SESSION['save']['prix_max']               = "";
    $_SESSION['save']['phone_restaurant']       = "";
    $_SESSION['save']['types_restaurants']      = "";
    $_SESSION['save']['description_restaurant'] = "";
    $_SESSION['save']['location']               = "";
    $_SESSION['save']['ouverture_restaurant']   = "";
    $_SESSION['save']['website_restaurant']     = "";
    $_SESSION['save']['plan_restaurant']        = "";
    $_SESSION['save']['saisie_other_location']  = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $listeLieux       = getLieux();
      $listeTypes       = getTypesRestaurants();
      $listeRestaurants = getRestaurants($listeLieux);
      $choixRapide      = getFastActions($_SESSION['user']['identifiant']);
      break;

    case 'doAjouter':
      $id_restaurant = insertRestaurant($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doModifier':
      $id_restaurant = updateRestaurant($_POST, $_FILES);
      break;

    case 'doSupprimer':
      deleteRestaurant($_POST);
      break;

    case 'doChoixRapide':
      $isSolo        = getSolo($_SESSION['user']['identifiant']);
      $id_restaurant = insertFastChoice($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: restaurants.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeLieux as &$lieu)
      {
        $lieu = htmlspecialchars($lieu);
      }

      unset($lieu);

      foreach ($listeTypes as &$type)
      {
        $type = htmlspecialchars($type);
      }

      unset($type);

      foreach ($listeRestaurants as $restaurantsParLieux)
      {
        foreach ($restaurantsParLieux as &$restaurant)
        {
          $restaurant->setName(htmlspecialchars($restaurant->getName()));
          $restaurant->setPicture(htmlspecialchars($restaurant->getPicture()));
          $restaurant->setTypes(htmlspecialchars($restaurant->getTypes()));
          $restaurant->setLocation(htmlspecialchars($restaurant->getLocation()));
          $restaurant->setPhone(htmlspecialchars($restaurant->getPhone()));
          $restaurant->setOpened(htmlspecialchars($restaurant->getOpened()));
          $restaurant->setMin_price(htmlspecialchars($restaurant->getMin_price()));
          $restaurant->setMax_price(htmlspecialchars($restaurant->getMax_price()));
          $restaurant->setWebsite(htmlspecialchars($restaurant->getWebsite()));
          $restaurant->setPlan(htmlspecialchars($restaurant->getPlan()));
          $restaurant->setDescription(htmlspecialchars($restaurant->getDescription()));
        }

        unset($restaurant);
      }
      break;

    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
    case 'doChoixRapide':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if (!empty($id_restaurant))
        header('location: restaurants.php?action=goConsulter&anchor=' . $id_restaurant);
      else
        header('location: restaurants.php?action=goConsulter');
      break;

    case 'doModifier':
      header('location: restaurants.php?action=goConsulter&anchor=' . $id_restaurant);
      break;

    case 'doSupprimer':
      header('location: restaurants.php?action=goConsulter');
      break;

    case 'doChoixRapide':
      header('location: restaurants.php?action=goConsulter&anchor=' . $id_restaurant);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_restaurants.php');
      break;
  }
?>
