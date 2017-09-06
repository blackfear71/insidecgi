<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  include('../../includes/fonctions_dates.php');
  include('../../includes/fonctions_regex.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_moviehouse.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $filmExistant = controlFilm($_GET['id_film']);
      if ($filmExistant == true)
      {
        $listeNavigation   = getNavigation($_GET['id_film']);
        $detailsFilm       = getDetails($_GET['id_film'], $_SESSION['identifiant']);
        $listeEtoiles      = getDetailsStars($_GET['id_film']);
        $listeCommentaires = getComments($_GET['id_film']);
      }
      break;

    case "doVoterFilm":
      insertStars($_POST, $_GET, $_SESSION['identifiant']);
      break;

    case "doParticiperFilm":
      insertParticipation($_POST, $_GET, $_SESSION['identifiant']);
      break;

    case "doCommenter":
      insertComment($_POST, $_GET, $_SESSION['identifiant']);
      break;

    case "doSupprimer":
      $preferences = getPreferences($_SESSION['identifiant']);
      deleteFilm($_GET['delete_id']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      if ($filmExistant == true)
      {
        foreach ($listeNavigation as $navigation)
        {
          if (!empty($navigation))
          {
            $navigation['id']   = htmlspecialchars($navigation['id']);
            $navigation['film'] = htmlspecialchars($navigation['film']);
          }
        }

        $detailsFilm->setId(htmlspecialchars($detailsFilm->getId()));
        $detailsFilm->setFilm(htmlspecialchars($detailsFilm->getFilm()));
        $detailsFilm->setTo_delete(htmlspecialchars($detailsFilm->getTo_delete()));
        $detailsFilm->setDate_add(htmlspecialchars($detailsFilm->getDate_add()));
        $detailsFilm->setDate_theater(htmlspecialchars($detailsFilm->getDate_theater()));
        $detailsFilm->setDate_release(htmlspecialchars($detailsFilm->getDate_release()));
        $detailsFilm->setLink(htmlspecialchars($detailsFilm->getLink()));
        $detailsFilm->setPoster(htmlspecialchars($detailsFilm->getPoster()));
        $detailsFilm->setTrailer(htmlspecialchars($detailsFilm->getTrailer()));
        $detailsFilm->setId_url(htmlspecialchars($detailsFilm->getId_url()));
        $detailsFilm->setDoodle(htmlspecialchars($detailsFilm->getDoodle()));
        $detailsFilm->setDate_doodle(htmlspecialchars($detailsFilm->getDate_doodle()));
        $detailsFilm->setTime_doodle(htmlspecialchars($detailsFilm->getTime_doodle()));
        $detailsFilm->setRestaurant(htmlspecialchars($detailsFilm->getRestaurant()));
        $detailsFilm->setNb_comments(htmlspecialchars($detailsFilm->getNb_comments()));
        $detailsFilm->setStars_user(htmlspecialchars($detailsFilm->getStars_user()));
        $detailsFilm->setParticipation(htmlspecialchars($detailsFilm->getParticipation()));
        $detailsFilm->setNb_users(htmlspecialchars($detailsFilm->getNb_users()));
        $detailsFilm->setAverage(htmlspecialchars($detailsFilm->getAverage()));

        foreach ($listeEtoiles as $etoiles)
        {
          $etoiles->setId(htmlspecialchars($etoiles->getId()));
          $etoiles->setId_film(htmlspecialchars($etoiles->getId_film()));
          $etoiles->setIdentifiant(htmlspecialchars($etoiles->getIdentifiant()));
          $etoiles->setPseudo(htmlspecialchars($etoiles->getPseudo()));
          $etoiles->setAvatar(htmlspecialchars($etoiles->getAvatar()));
          $etoiles->setStars(htmlspecialchars($etoiles->getStars()));
          $etoiles->setParticipation(htmlspecialchars($etoiles->getParticipation()));
        }

        foreach ($listeCommentaires as $comment)
        {
          $comment->setId(htmlspecialchars($comment->getId()));
          $comment->setId_film(htmlspecialchars($comment->getId_film()));
          $comment->setAuthor(htmlspecialchars($comment->getAuthor()));
          $comment->setPseudo(htmlspecialchars($comment->getPseudo()));
          $comment->setAvatar(htmlspecialchars($comment->getAvatar()));
          $comment->setDate(htmlspecialchars($comment->getDate()));
          $comment->setTime(htmlspecialchars($comment->getTime()));
          $comment->setComment(htmlspecialchars($comment->getComment()));
        }
      }
      break;

    case "doVoterFilm":
    case "doParticiperFilm":
    case "doCommenter":
    case "doSupprimer":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doVoterFilm":
    case "doParticiperFilm":
      header('location: details.php?id_film=' . $_GET['id_film'] . '&action=goConsulter');
      break;

    case "doCommenter":
      header('location: details.php?id_film=' . $_GET['id_film'] . '&action=goConsulter#comments');
      break;

    case "doSupprimer":
      switch ($preferences->getView_movie_house())
      {
        case "S":
          $view_movie_house = "main";
          break;

        case "D":
          $view_movie_house = "user";
          break;

        case "H":
        default:
          $view_movie_house = "home";
          break;
      }

      header('location: moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_details.php');
      break;
  }
?>
