<?php
  /*****************************
  **** Gestion des rapports ****
  ******************************
  Fonctionnalités :
  - Résolution de bug/évolution
  - Suppression de bug/évolution
  - Rejet de bug/évolution
  *****************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_reports.php');
  include_once('modele/physique_reports.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération de la liste des bugs/évolutions en fonction de la vue
      switch ($_GET['view'])
      {
        case 'all':
        case 'resolved':
        case 'unresolved':
          // Récupération de la liste des utilisateurs
          $listeUsers = getListeUsers();

          // Récupération de la liste des équipes
          $listeEquipes = getListeEquipes();

          // Récupération de la liste des bugs
          $listeBugs = getBugs($_GET['view'], 'B', $listeUsers, $listeEquipes);

          // Récupération de la liste des évolutions
          $listeEvolutions = getBugs($_GET['view'], 'E', $listeUsers, $listeEquipes);
          break;

        default:
          // Contrôle vue renseignée URL
          header('location: reports.php?view=all&action=goConsulter');
          break;
      }
      break;

		case 'doChangerStatut':
			// Mise à jour d'un bug ou d'une évolution
			$resolved  = updateBug($_POST);
      $idRapport = $_POST['id_report'];
			break;

    case 'doSupprimer':
      // Suppression d'un bug ou d'une évolution
      deleteBug($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: reports.php?view=all&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeUsers as &$user)
      {
        $user['pseudo'] = htmlspecialchars($user['pseudo']);
        $user['avatar'] = htmlspecialchars($user['avatar']);
      }

      unset($user);

      foreach ($listeEquipes as $equipe)
      {
        Team::secureData($equipe);
      }

      foreach ($listeBugs as $bug)
      {
        BugEvolution::secureData($bug);
      }

      foreach ($listeEvolutions as $evolution)
      {
        BugEvolution::secureData($evolution);
      }
      break;

		case 'doChangerStatut':
    case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
		case 'doChangerStatut':
      if ($resolved == 'Y' OR $resolved == 'R')
        header('location: reports.php?view=resolved&action=goConsulter&anchor=' . $idRapport);
      else
        header('location: reports.php?view=unresolved&action=goConsulter&anchor=' . $idRapport);
      break;

    case 'doSupprimer':
			header('location: reports.php?view=' . $_GET['view'] . '&action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_reports.php');
      break;
  }
?>
