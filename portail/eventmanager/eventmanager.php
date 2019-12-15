<?php
  /****************************
  ******* Event Manager *******
  *****************************
  Fonctionnalités :
  - Consultation des évènements
  ****************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_eventmanager.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle




      /*$preferences      = getPreferences($_SESSION['user']['identifiant']);*/




      break;

    default:
      // Contrôle action renseignée URL
      header('location: eventmanager.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':




      /*$preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
      $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
      $preferences->setView_old_movies(htmlspecialchars($preferences->getView_old_movies()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
      $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));*/





      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_eventmanager.php');
      break;
  }
?>
