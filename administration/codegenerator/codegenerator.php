<?php
  /*******************************
  *** Gestion des utilisateurs ***
  ********************************
  Fonctionnalités :
  - Génération nouvelle page
  *******************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_codegenerator.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      if (!isset($generatorParameters))
        $generatorParameters = initializeGenerator();
      break;

    case 'generateCode':
      var_dump($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: codegenerator.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      $generatorParameters->setNom_section(htmlspecialchars($generatorParameters->getNom_section()));
      $generatorParameters->setNom_technique(htmlspecialchars($generatorParameters->getNom_technique()));
      $generatorParameters->setStyle_specifique(htmlspecialchars($generatorParameters->getStyle_specifique()));
      $generatorParameters->setScript_specifique(htmlspecialchars($generatorParameters->getScript_specifique()));

      foreach ($generatorParameters->getOptions() as &$generatorOption)
      {
        $generatorOption->setOption(htmlspecialchars($generatorOption->getOption()));
        $generatorOption->setChecked(htmlspecialchars($generatorOption->getChecked()));
        $generatorOption->setTitre(htmlspecialchars($generatorOption->getTitre()));
        $generatorOption->setCategorie(htmlspecialchars($generatorOption->getCategorie()));
      }

      unset($generatorOption);
      break;

    case 'generateCode':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'generateCode':
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_codegenerator.php');
      break;
  }
?>
