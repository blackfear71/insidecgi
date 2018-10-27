<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Contrôle si la page renseignée et numérique
	if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
		header('location: collector.php?action=goConsulter&page=1');

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['alerts']['wrong_date']) OR $_SESSION['alerts']['wrong_date'] != true)
  {
    $_SESSION['save']['speaker']        = "";
    $_SESSION['save']['other_speaker']  = "";
    $_SESSION['save']['date_collector'] = "";
    $_SESSION['save']['type_collector'] = "";
    $_SESSION['save']['collector']      = "";
    $_SESSION['save']['context']        = "";
  }

  // Modèle de données : "module métier"
  include_once('modele/metier_collector.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Lecture des données par le modèle
      $listeUsers = getUsers();
      $nbPages    = getPages();

      if ($nbPages > 0)
      {
        if ($_GET['page'] > $nbPages)
          header('location: collector.php?action=goConsulter&page=' . $nbPages);
        elseif ($_GET['page'] < 1)
          header('location: collector.php?action=goConsulter&page=1');
        else
        {
          $listeCollectors = getCollectors($listeUsers, $nbPages, $_GET['page']);
          $listeVotesUsers = getVotesUser($listeCollectors, $_SESSION['user']['identifiant']);
          $listeVotes      = getVotes($listeCollectors);
        }
      }
      break;

    case "doAjouter":
      $new_id   = insertCollector($_POST, $_FILES, $_SESSION['user']['identifiant']);
      $num_page = numPageCollector($new_id);
      break;

    case "doSupprimer":
      deleteVotes($_GET['delete_id']);
      deleteCollector($_GET['delete_id']);
      break;

    case "doModifier":
      updateCollector($_POST, $_GET['modify_id']);
      break;

    case "doVoter":
      voteCollector($_POST, $_SESSION['user']['identifiant'], $_GET['id']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: collector.php?action=goConsulter&page=1');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case "goConsulter":
      if ($nbPages > 0)
      {
        foreach ($listeUsers as &$user)
        {
          $user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
          $user->setPseudo(htmlspecialchars($user->getPseudo()));
          $user->setAvatar(htmlspecialchars($user->getAvatar()));
        }

        unset($user);

        foreach ($listeCollectors as &$collector)
        {
          $collector->setAuthor(htmlspecialchars($collector->getAuthor()));
          $collector->setName_a(htmlspecialchars($collector->getName_a()));
          $collector->setSpeaker(htmlspecialchars($collector->getSpeaker()));
          $collector->setName_s(htmlspecialchars($collector->getName_s()));
          $collector->setType_s(htmlspecialchars($collector->getType_s()));
          $collector->setDate_collector(htmlspecialchars($collector->getDate_collector()));
          $collector->setType_collector(htmlspecialchars($collector->getType_collector()));
          $collector->setCollector(htmlspecialchars($collector->getCollector()));
          $collector->setContext(htmlspecialchars($collector->getContext()));
          $collector->setDate_add(htmlspecialchars($collector->getDate_add()));
        }

        unset($collector);

        foreach ($listeVotesUsers as &$vote)
        {
          $vote->setId_collector(htmlspecialchars($vote->getId_collector()));
          $vote->setIdentifiant(htmlspecialchars($vote->getIdentifiant()));
          $vote->setVote(htmlspecialchars($vote->getVote()));
        }

        unset($vote);
      }
      break;

    case "doAjouter":
    case "doSupprimer":
    case "doModifier":
    case "doVoter":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
      header('location: collector.php?action=goConsulter&page=' . $num_page . '&anchor=' . $new_id);
      break;

    case "doSupprimer":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page']);
      break;

    case "doModifier":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&anchor=' . $_GET['modify_id']);
      break;

    case "doVoter":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&anchor=' . $_GET['id']);
      break;

    case "goConsulter":
    default:
      include_once('vue/vue_collector.php');
      break;
  }
?>
