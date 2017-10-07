<?php
  // Contrôles communs Administrateur
  include_once('../includes/controls_admin.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$listeUsers = getUsers();
      break;

    case 'changeBeginnerStatus':
      changeBeginner($_GET['user'], $_GET['top']);
      break;

    case 'changeDevelopperStatus':
      changeDevelopper($_GET['user'], $_GET['top']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: infos_users.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
			foreach ($listeUsers as $user)
			{
				$user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
				$user->setReset(htmlspecialchars($user->getReset()));
				$user->setPseudo(htmlspecialchars($user->getPseudo()));
				$user->setAvatar(htmlspecialchars($user->getAvatar()));
        $user->setEmail(htmlspecialchars($user->getEmail()));
        $user->setBeginner(htmlspecialchars($user->getBeginner()));
        $user->setDevelopper(htmlspecialchars($user->getDevelopper()));
			}
      break;

    case 'changeBeginnerStatus':
    case 'changeDevelopperStatus':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'changeBeginnerStatus':
    case 'changeDevelopperStatus':
      header('location: infos_users.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_infos_users.php');
      break;
  }
?>
