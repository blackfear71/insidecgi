<?php
  // Fonctions communes
	include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Initialisation sauvegarde saisie succès
  if ((!isset($_SESSION['alerts']['already_referenced']) OR $_SESSION['alerts']['already_referenced'] != true)
  AND (!isset($_SESSION['alerts']['level_not_numeric'])  OR $_SESSION['alerts']['level_not_numeric']  != true)
  AND (!isset($_SESSION['alerts']['order_not_numeric'])  OR $_SESSION['alerts']['order_not_numeric']  != true)
  AND (!isset($_SESSION['alerts']['already_ordered'])    OR $_SESSION['alerts']['already_ordered']    != true)
  AND (!isset($_SESSION['alerts']['limit_not_numeric'])  OR $_SESSION['alerts']['limit_not_numeric']  != true))
  {
    $_SESSION['save']['reference_success']   = "";
    $_SESSION['save']['level']               = "";
		$_SESSION['save']['order_success']       = "";
    $_SESSION['save']['title_success']       = "";
    $_SESSION['save']['description_success'] = "";
    $_SESSION['save']['limit_success']       = "";
		$_SESSION['save']['explanation_success'] = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Lecture liste des données par le modèle
			$listeSuccess = getSuccess();
      break;

    case "goModifier":
      // Lecture liste des données par le modèle
      $listeSuccess = getSuccess();

			if (!isset($_GET['error']) OR $_GET['error'] != true)
		    $_SESSION['save']['save_success'] = NULL;
			else
        $listeSuccess = initModErrSucces($listeSuccess, $_SESSION['save']['save_success']);
      break;

    case "doAjouter":
      insertSuccess($_POST, $_FILES);
      break;

    case "doSupprimer":
      deleteSuccess($_GET['id']);
      break;

    case "doModifier":
      $erreurUpdateSucces = updateSuccess($_POST);
      break;

		case "doInitialiser":
			// Lecture liste des données par le modèle
			$listeUsers   = getUsers();
			$listeSuccess = getSuccess();
			initializeSuccess($listeSuccess, $listeUsers);
			break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_success.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
		case 'goModifier':
      foreach ($listeSuccess as &$success)
      {
        $success->setReference(htmlspecialchars($success->getReference()));
        $success->setLevel(htmlspecialchars($success->getLevel()));
        $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
				$success->setDefined(htmlspecialchars($success->getDefined()));
        $success->setTitle(htmlspecialchars($success->getTitle()));
        $success->setDescription(htmlspecialchars($success->getDescription()));
				$success->setLimit_success(htmlspecialchars($success->getLimit_success()));
        $success->setExplanation(htmlspecialchars($success->getExplanation()));
      }

			unset($success);
      break;

    case "doAjouter":
    case "doSupprimer":
    case "doModifier":
		case "doInitialiser":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doModifier":
      if ($erreurUpdateSucces == true)
        header('location: manage_success.php?error=true&action=goModifier');
      else
        header('location: manage_success.php?action=goConsulter');
      break;

    case "doAjouter":
    case "doSupprimer":
      header('location: manage_success.php?action=goConsulter');
      break;

		case "doInitialiser":
			header('location: manage_success.php?action=goConsulter');
			break;

    case 'goModifier':
      include_once('vue/vue_modify_success.php');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_success.php');
      break;
  }
?>
