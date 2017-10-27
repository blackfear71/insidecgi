<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');
  include_once('../../includes/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_moviehouse.php');

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['wrong_date']) OR $_SESSION['wrong_date'] != true)
  {
    $_SESSION['nom_film_saisi']         = "";
    $_SESSION['date_theater_saisie']    = "";
    $_SESSION['date_release_saisie']    = "";
    $_SESSION['trailer_saisi']          = "";
    $_SESSION['link_saisi']             = "";
    $_SESSION['poster_saisi']           = "";
    $_SESSION['doodle_saisi']           = "";
    $_SESSION['date_doodle_saisie']     = "";
    $_SESSION['hours_doodle_saisies']   = "";
    $_SESSION['minutes_doodle_saisies'] = "";
    $_SESSION['time_doodle_saisi']      = "";
    $_SESSION['restaurant_saisi']       = "";
    $_SESSION['place_saisie']           = "";
    $wrong_date                         = false;
  }

  // Récupération top si erreur date (écrasé par les alertes sinon)
  if (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
    $wrong_date = true;

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goAjouter':
      // Lecture liste des données par le modèle
      $initSaisie   = true;
      $filmExistant = false;

      if ($_SESSION['wrong_date'] == true)
        $film = initCreErrFilm();
      else
        $film = initCreFilm();
      break;

    case "goModifier":
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['modify_id']) OR !is_numeric($_GET['modify_id']))
        header('location: saisie.php?action=goAjouter');

      $initSaisie   = false;
      $filmExistant = controlFilm($_GET['modify_id']);

      if ($filmExistant == true)
      {
        if ($_SESSION['wrong_date'] == true)
          $film = initModErrFilm($_GET['modify_id']);
        else
          $film = initModFilm($_GET['modify_id']);
      }
      break;

    case "doInserer":
      $new_id = insertFilmAvance($_POST, $_SESSION['identifiant']);
      break;

    case "doModifier":
      modFilmAvance($_GET['modify_id'], $_POST, $_SESSION['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      if (isset($_GET['modify_id']) AND is_numeric($_GET['modify_id']))
        header('location: saisie.php?modify_id=' . $_GET['modify_id'] . '&action=goModifier');
      else
        header('location: saisie.php?action=goAjouter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goAjouter':
    case "goModifier":
      if ($filmExistant == true OR ($filmExistant == false AND $initSaisie == true))
      {
        $film->setFilm(htmlspecialchars($film->getFilm()));
        $film->setTo_delete(htmlspecialchars($film->getTo_delete()));
        $film->setDate_add(htmlspecialchars($film->getDate_add()));
        $film->setIdentifiant_add(htmlspecialchars($film->getIdentifiant_add()));
        $film->setDate_theater(htmlspecialchars($film->getDate_theater()));
        $film->setDate_release(htmlspecialchars($film->getDate_release()));
        $film->setLink(htmlspecialchars($film->getLink()));
        $film->setPoster(htmlspecialchars($film->getPoster()));
        $film->setTrailer(htmlspecialchars($film->getTrailer()));
        $film->setId_url(htmlspecialchars($film->getId_url()));
        $film->setDoodle(htmlspecialchars($film->getDoodle()));
        $film->setDate_doodle(htmlspecialchars($film->getDate_doodle()));
        $film->setTime_doodle(htmlspecialchars($film->getTime_doodle()));
        $film->setRestaurant(htmlspecialchars($film->getRestaurant()));
        $film->setPlace(htmlspecialchars($film->getPlace()));
        $film->setNb_comments(htmlspecialchars($film->getNb_comments()));
        $film->setStars_user(htmlspecialchars($film->getStars_user()));
        $film->setParticipation(htmlspecialchars($film->getParticipation()));
        $film->setNb_users(htmlspecialchars($film->getNb_users()));
        $film->setAverage(htmlspecialchars($film->getAverage()));
      }
      break;

    case "doInserer":
    case "doModifier":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doInserer":
      if ($_SESSION['wrong_date'] == true)
        header('location: saisie.php?action=goAjouter');
      else
        header('location: details.php?id_film=' . $new_id . '&action=goConsulter');
      break;

    case "doModifier":
      if ($_SESSION['wrong_date'] == true)
        header('location: saisie.php?modify_id=' . $_GET['modify_id'] . '&action=goModifier');
      else
        header('location: details.php?id_film=' . $_GET['modify_id'] . '&action=goConsulter');
      break;

    case 'goAjouter':
    case "goModifier":
    default:
      include_once('vue/vue_saisie.php');
      break;
  }
?>
