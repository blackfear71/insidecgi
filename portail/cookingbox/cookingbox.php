<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_cookingbox.php');

  // Initialisation sauvegarde saisie
  if ((!isset($_SESSION['alerts']['quantity_not_numeric']) OR $_SESSION['alerts']['quantity_not_numeric'] != true))
  {
    $_SESSION['save']['year_recipe']           = "";
    $_SESSION['save']['week_recipe']           = "";
    $_SESSION['save']['name_recipe']           = "";
    $_SESSION['save']['ingredients']           = array();
    $_SESSION['save']['quantites_ingredients'] = array();
    $_SESSION['save']['unites_ingredients']    = array();
    $_SESSION['save']['preparation']           = "";
    $_SESSION['save']['remarks']               = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: cookingbox.php?year=' . date("Y") . '&action=goConsulter');
      else
      {
        // Gâteaux semaines n et n + 1
        $currentWeek    = getWeek(date('W'));
        $nextWeek       = getWeek(date('W') + 1);
        $listeUsers     = getUsers();

        // Saisie
        $listeSemaines  = getWeeks($_SESSION['user']['identifiant']);

        // Recettes
        $anneeExistante = controlYear($_GET['year']);
        $ongletsYears   = getOnglets();
        $recettes       = getRecipes($_GET['year']);
      }
      break;

    case "doModifier":
      updateCake($_POST);
      break;

    case "doValider":
      validateCake("Y", $_POST['week_cake'], $_GET['year']);
      break;

    case "doAnnuler":
      validateCake("N", $_POST['week_cake'], $_GET['year']);
      break;

    case "doAjouterRecette":
      $year       = $_POST['year_recipe'];
      $id_recette = insertRecipe($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case "doModifierRecette":
      break;

    case "doSupprimerRecette":
      deleteRecipe($_POST, $_GET['year']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: cookingbox.php?year=' . date("Y") . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case "goConsulter":
      $currentWeek->setIdentifiant(htmlspecialchars($currentWeek->getIdentifiant()));
      $currentWeek->setPseudo(htmlspecialchars($currentWeek->getPseudo()));
      $currentWeek->setAvatar(htmlspecialchars($currentWeek->getAvatar()));
      $currentWeek->setWeek(htmlspecialchars($currentWeek->getWeek()));
      $currentWeek->setYear(htmlspecialchars($currentWeek->getYear()));
      $currentWeek->setCooked(htmlspecialchars($currentWeek->getCooked()));
      $currentWeek->setName(htmlspecialchars($currentWeek->getName()));
      $currentWeek->setPicture(htmlspecialchars($currentWeek->getPicture()));
      $currentWeek->setIngredients(htmlspecialchars($currentWeek->getIngredients()));
      $currentWeek->setRecipe(htmlspecialchars($currentWeek->getRecipe()));
      $currentWeek->setTips(htmlspecialchars($currentWeek->getTips()));

      $nextWeek->setIdentifiant(htmlspecialchars($nextWeek->getIdentifiant()));
      $nextWeek->setPseudo(htmlspecialchars($nextWeek->getPseudo()));
      $nextWeek->setAvatar(htmlspecialchars($nextWeek->getAvatar()));
      $nextWeek->setWeek(htmlspecialchars($nextWeek->getWeek()));
      $nextWeek->setYear(htmlspecialchars($nextWeek->getYear()));
      $nextWeek->setCooked(htmlspecialchars($nextWeek->getCooked()));
      $nextWeek->setName(htmlspecialchars($nextWeek->getName()));
      $nextWeek->setPicture(htmlspecialchars($nextWeek->getPicture()));
      $nextWeek->setIngredients(htmlspecialchars($nextWeek->getIngredients()));
      $nextWeek->setRecipe(htmlspecialchars($nextWeek->getRecipe()));
      $nextWeek->setTips(htmlspecialchars($nextWeek->getTips()));

      foreach ($listeUsers as &$user)
      {
        $user = htmlspecialchars($user);
      }

      unset($user);

      foreach ($listeSemaines as &$year)
      {
        foreach ($year as &$week)
        {
          $week = htmlspecialchars($week);
        }
      }

      unset($year);
      unset($week);

      foreach ($ongletsYears as &$onglet)
      {
        $onglet = htmlspecialchars($onglet);
      }

      unset($onglet);

      foreach ($recettes as &$recette)
      {
        $recette->setIdentifiant(htmlspecialchars($recette->getIdentifiant()));
        $recette->setPseudo(htmlspecialchars($recette->getPseudo()));
        $recette->setAvatar(htmlspecialchars($recette->getAvatar()));
        $recette->setWeek(htmlspecialchars($recette->getWeek()));
        $recette->setYear(htmlspecialchars($recette->getYear()));
        $recette->setCooked(htmlspecialchars($recette->getCooked()));
        $recette->setName(htmlspecialchars($recette->getName()));
        $recette->setPicture(htmlspecialchars($recette->getPicture()));
        $recette->setIngredients(htmlspecialchars($recette->getIngredients()));
        $recette->setRecipe(htmlspecialchars($recette->getRecipe()));
        $recette->setTips(htmlspecialchars($recette->getTips()));
      }

      unset($recette);

      // Conversion JSON
      $listeSemainesJson = json_encode($listeSemaines);
      $listeUsersJson    = json_encode($listeUsers);
      $recettesJson      = json_encode(convertForJson($recettes));
      break;

    case "doModifier":
    case "doValider":
    case "doAnnuler":
    case "doAjouterRecette":
    case "doModifierRecette":
    case "doSupprimerRecette":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doModifier":
    case "doValider":
    case "doAnnuler":
    case "doSupprimerRecette":
      header('location: cookingbox.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case "doAjouterRecette":
    case "doModifierRecette":
      header('location: cookingbox.php?year=' . $year . '&action=goConsulter&anchor=' . $id_recette);
      break;

    case "goConsulter":
    default:
      include_once('vue/vue_cookingbox.php');
      break;
  }
?>
