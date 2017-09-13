<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  include('../../includes/fonctions_dates.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_bugs.php');

  // Contrôle vue renseignée URL
  switch ($_GET['view'])
  {
    case 'submit':
    case 'resolved':
    case 'unresolved':
      break;

    default:
      header('location: bugs.php?view=submit&action=goSignaler');
      break;
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $listeBugs = getBugs($_GET['view']);
      break;

    case 'doSignaler':
      // Insertion des données par le modèle
      insertBug($_POST);
      break;

    case 'goSignaler':
      // Pas de traitement
      break;

    default:
      // Contrôle action renseignée URL
      header('location: bugs.php?view=' . $_GET['view'] . '&action=goSignaler');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeBugs as $bug)
      {
        $bug->setSubject(htmlspecialchars($bug->getSubject()));
        $bug->setDate(htmlspecialchars($bug->getDate()));
        $bug->setAuthor(htmlspecialchars($bug->getAuthor()));
        $bug->setName_a(htmlspecialchars($bug->getName_a()));
        $bug->setContent(htmlspecialchars($bug->getContent()));
        $bug->getType(htmlspecialchars($bug->getType()));
        $bug->getResolved(htmlspecialchars($bug->getResolved()));
      }
      break;

    case 'doSignaler':
    case 'goSignaler':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doSignaler':
      header('location: bugs.php?view=unresolved&action=goConsulter');
      break;

    case 'goConsulter':
    case 'goSignaler':
    default:
      include_once('vue/vue_bugs.php');
      break;
  }
?>