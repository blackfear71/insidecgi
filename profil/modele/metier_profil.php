<?php
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/classes/profile.php');
  include_once('../includes/classes/success.php');
  include_once('../includes/libraries/php/imagethumb.php');

  // METIER : Lecture des données profil
  // RETOUR : Objet Profile
  function getProfile($user)
  {
    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM users WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $profile = Profile::withData($donnees);

    $reponse->closeCursor();

    return $profile;
  }

  // METIER : Lecture des données statistiques profil
  // RETOUR : Objet Statistiques
  function getStatistiques($user)
  {
    $nb_films_ajoutes = 0;
    $nb_comments      = 0;
    $expenses         = 0;
    $nb_collectors    = 0;
    $nb_ideas         = 0;

    global $bdd;

    // Nombre de films ajoutés Movie House
    $reponse = $bdd->query('SELECT COUNT(id) AS nb_films_ajoutes FROM movie_house WHERE identifiant_add = "' . $user . '" AND to_delete != "Y"');
    $donnees = $reponse->fetch();

    $nb_films_ajoutes = $donnees['nb_films_ajoutes'];

    $reponse->closeCursor();

    // Nombre de commentaires Movie House
    $reponse0 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY id ASC');
    while($donnees0 = $reponse0->fetch())
    {
      $reponse1 = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $donnees0['id'] . ' AND author = "' . $user . '"');
      $donnees1 = $reponse1->fetch();

      if ($reponse1->rowCount() > 0)
        $nb_comments++;

      $reponse1->closeCursor();
    }
    $reponse0->closeCursor();

    // Solde des dépenses
    $reponse2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user . '"');
    $donnees2 = $reponse2->fetch();

    $expenses = $donnees2['expenses'];

    $reponse2->closeCursor();

    // Nombre de phrases cultes soumises
    $reponse3 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector WHERE author = "' . $user . '"');
    $donnees3 = $reponse3->fetch();

    $nb_collectors = $donnees3['nb_collectors'];

    $reponse3->closeCursor();

    // Nombre d'idées soumises
    $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE author = "' . $user . '"');
    $donnees4 = $reponse4->fetch();

    $nb_ideas = $donnees4['nb_idees'];

    $reponse4->closeCursor();

    // On construit un tableau avec les données statistiques
    $myStats = array('nb_films_ajoutes' => $nb_films_ajoutes,
                     'nb_comments'      => $nb_comments,
                     'expenses'         => $expenses,
                     'nb_collectors'    => $nb_collectors,
                     'nb_ideas'         => $nb_ideas,
                    );

    // Instanciation d'un objet Statistiques à partir des données remontées de la bdd
    $statistiques = Statistiques::withData($myStats);

    return $statistiques;
  }

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }

  // METIER : Mise à jour du pseudo
  // RETOUR : Aucun
  function changePseudo($user, $post)
  {
    $new_pseudo = trim($post['new_pseudo']);

    if (!empty($new_pseudo))
    {
      global $bdd;

      // Mise à jour du pseudo
      $reponse = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE identifiant = "' . $user . '"');
      $reponse->execute(array(
        'pseudo' => $new_pseudo
      ));
      $reponse->closeCursor();

      // Mise à jour du pseudo stocké en SESSION
      $_SESSION['user']['pseudo'] = $new_pseudo;
    }

    $_SESSION['alerts']['pseudo_updated'] = true;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function changeAvatar($user, $files)
  {
    global $bdd;

    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "../includes/images/profil";

    if (!is_dir($dossier))
      mkdir($dossier);

    // On contrôle la présence du dossier d'avatars, sinon on le créé
    $dossier_avatars = $dossier . "/avatars";

    if (!is_dir($dossier_avatars))
      mkdir($dossier_avatars);

 		$avatar = rand();

 		// Si on a bien une image
 		if ($files['avatar']['name'] != NULL)
 		{
 			// Dossier de destination
 			$avatar_dir = $dossier_avatars . '/';

 			// Données du fichier
 			$file      = $files['avatar']['name'];
 			$tmp_file  = $files['avatar']['tmp_name'];
 			$size_file = $files['avatar']['size'];
      $maxsize   = 8388608; // 8Mo

      // Si le fichier n'est pas trop grand
 			if ($size_file < $maxsize)
 			{
 				// Contrôle fichier temporaire existant
 				if (!is_uploaded_file($tmp_file))
 				{
 					exit("Le fichier est introuvable");
 				}

 				// Contrôle type de fichier
 				$type_file = $files['avatar']['type'];

 				if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
 				{
 					exit("Le fichier n'est pas une image valide");
 				}
 				else
 				{
 					$type_image = pathinfo($file, PATHINFO_EXTENSION);
 					$new_name   = $avatar . '.' . $type_image;
 				}

 				// Contrôle upload (si tout est bon, l'image est envoyée)
 				if (!move_uploaded_file($tmp_file, $avatar_dir . $new_name))
 				{
 					exit("Impossible de copier le fichier dans $avatar_dir");
 				}

 				// Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
 				imagethumb($avatar_dir . $new_name, $avatar_dir . $new_name, 400, FALSE, TRUE);

 				// echo "Le fichier a bien été uploadé";

 				// On efface l'ancien avatar si présent
 				$reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
 				$donnees1 = $reponse1->fetch();

 				if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
 					unlink ($dossier . "/" . $donnees1['avatar'] . "");

 				$reponse1->closeCursor();

 				// On stocke la référence du nouvel avatar dans la base
 				$reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
 				$reponse2->execute(array(
 					'avatar' => $new_name
 				));
 				$reponse2->closeCursor();

        $_SESSION['user']['avatar']           = $new_name;
 				$_SESSION['alerts']['avatar_updated'] = true;
 			}
 		}
  }

  // METIER : Suppression de l'avatar (base + fichier)
  // RETOUR : Aucun
  function deleteAvatar($user)
  {
    global $bdd;

    // On efface l'ancien avatar si présent
    $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
    $donnees1 = $reponse1->fetch();

    if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
      unlink ("../includes/images/profil/avatars/" . $donnees1['avatar'] . "");

    $reponse1->closeCursor();

    // On efface la référence de l'ancien avatar dans la base
    $new_name = "";

    $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
    $reponse2->execute(array(
      'avatar' => $new_name
    ));
    $reponse2->closeCursor();

    $_SESSION['user']['avatar']           = '';
    $_SESSION['alerts']['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des préférences
  // RETOUR : Aucun
  function updatePreferences($user, $post)
  {
    $error                           = false;

    global $bdd;

		// Préférences MOVIE HOUSE
		$view_movie_house = $post['movie_house_view'];

		$categories_home = "";

		if (isset($post['films_waited']))
			$categories_home .= "Y";
		else
			$categories_home .= "N";

		if (isset($post['films_way_out']))
			$categories_home .= "Y";
		else
			$categories_home .= "N";

		if (isset($post['affiche_date']))
			$today_movie_house = "Y";
		else
			$today_movie_house = "N";

    if ($post['old_movies_view'] == "T")
      $view_old_movies = "T;;;";
    else
    {
      if (!is_numeric($post['duration']) OR !ctype_digit($post['duration']) OR $post['duration'] <= 0)
      {
        $_SESSION['alerts']['duration_not_correct'] = true;
        $error                                      = true;
      }
      else
      {
        switch ($post['type_duration'])
        {
          case "J":
            if ($post['duration'] > 365)
            {
              $_SESSION['alerts']['duration_too_long'] = true;
              $error                                   = true;
            }
            break;

          case "S":
            if ($post['duration'] > 52)
            {
              $_SESSION['alerts']['duration_too_long'] = true;
              $error                                   = true;
            }
            break;

          case "M":
            if ($post['duration'] > 12)
            {
              $_SESSION['alerts']['duration_too_long'] = true;
              $error                                   = true;
            }
            break;

          default:
            break;
        }
        $view_old_movies = $post['old_movies_view'] . ";" . $post['type_duration'] . ";" . $post['duration'] . ";";
      }
    }

		// Préférences #THEBOX
		$view_the_box = $post['the_box_view'];

    // Préférences Notifications
    $view_notifications = $post['notifications_view'];

    if ($error == false)
    {
      // Mise à jour de la table des préférences utilisateur
      $reponse = $bdd->prepare('UPDATE preferences SET view_movie_house   = :view_movie_house,
                                                       categories_home    = :categories_home,
                                                       today_movie_house  = :today_movie_house,
                                                       view_old_movies    = :view_old_movies,
                                                       view_the_box       = :view_the_box,
                                                       view_notifications = :view_notifications
                                                 WHERE identifiant = "' . $user . '"');
      $reponse->execute(array(
        'view_movie_house'   => $view_movie_house,
        'categories_home'    => $categories_home,
        'today_movie_house'  => $today_movie_house,
        'view_old_movies'    => $view_old_movies,
        'view_the_box'       => $view_the_box,
        'view_notifications' => $view_notifications
      ));
      $reponse->closeCursor();

      // Mise à jour des préférences stockées en SESSION
      $_SESSION['user']['view_movie_house']   = $view_movie_house;
      $_SESSION['user']['view_the_box']       = $view_the_box;
      $_SESSION['user']['view_notifications'] = $view_notifications;

      $_SESSION['alerts']['preferences_updated'] = true;
    }
  }

  // METIER : Modification adresse mail
  // RETOUR : Aucun
  function updateMail($user, $post)
  {
    if (isset($post['suppression_mail']))
      $mail = "";
    else
      $mail = $post['mail'];

    global $bdd;

    // Mise à jour de l'adresse mail utilisateur
		$reponse = $bdd->prepare('UPDATE users SET email  = :email WHERE identifiant = "' . $user . '"');
		$reponse->execute(array(
			'email'  => $mail
		));
		$reponse->closeCursor();

    $_SESSION['alerts']['mail_updated'] = true;
  }

  // METIER : Mise à jour du mot de passe
  // RETOUR : Aucun
  function changeMdp($user, $post)
  {
    if (!empty($post['old_password'])
    AND !empty($post['new_password'])
    AND !empty($post['confirm_new_password']))
  	{
      global $bdd;

  		// Lecture des données actuelles de l'utilisateur
  		$reponse = $bdd->query('SELECT id, identifiant, salt, password FROM users WHERE identifiant = "' . $user . '"');
  		$donnees = $reponse->fetch();

  		$old_password = htmlspecialchars(hash('sha1', $post['old_password'] . $donnees['salt']));

  		if ($old_password == $donnees['password'])
  		{
  			$salt                 = rand();
  			$new_password         = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
  			$confirm_new_password = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

  			if ($new_password == $confirm_new_password)
  			{
  				$req = $bdd->prepare('UPDATE users SET salt = :salt, password = :password WHERE identifiant = "' . $user . '"');
  				$req->execute(array(
  					'salt'     => $salt,
  					'password' => $new_password
  				));
  				$req->closeCursor();

  				$_SESSION['alerts']['password_updated'] = true;
  			}
  			else
  			   $_SESSION['alerts']['wrong_password'] = true;
  		}
  		else
  		  $_SESSION['alerts']['wrong_password'] = true;

  		$reponse->closeCursor();
    }
  }

  // METIER : Mise à jour du statut par l'utilisateur (désinscription, mot de passe)
  // RETOUR : Aucun
  function changeStatus($user, $status)
  {
    global $bdd;

    $reponse = $bdd->prepare('UPDATE users SET status = :status WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'status' => $status
    ));
    $reponse->closeCursor();

    switch ($status)
    {
      case "D":
        $_SESSION['alerts']['ask_desinscription'] = true;
        break;

      case "N":
        $_SESSION['alerts']['cancel_status'] = true;
        break;

      default:
        break;
    }
  }

  // METIER : Lecture liste des succès
  // RETOUR : Liste des succès et déblocages
  function getSuccess($user)
  {
    $listSuccess = array();

    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM success');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Success à partir des données remontées de la bdd
      $mySuccess = Success::withData($donnees);

      // Recherche des données utilisateur
      $reponse2 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $donnees['reference'] . '" AND identifiant = "' . $user . '"');
      $donnees2 = $reponse2->fetch();

      if ($reponse2->rowCount() > 0)
        $mySuccess->setValue_user($donnees2['value']);

      $reponse2->closeCursor();

      array_push($listSuccess, $mySuccess);
    }
    $reponse->closeCursor();

    // Tri sur niveau puis ordonnancement
    foreach ($listSuccess as $success)
    {
      $tri_level[] = $success->getLevel();
      $tri_order[] = $success->getOrder_success();
    }
    array_multisort($tri_level, SORT_ASC, $tri_order, SORT_ASC, $listSuccess);

    return $listSuccess;
  }

  // METIER : Classement des succès des utilisateurs
  // RETOUR : Tableau des classement
  function getRankUsers($listSuccess, $listUsers)
  {
    // Création tableau de correspondance identifiant / pseudo
    $tablePseudos = array();

    foreach ($listUsers AS $user)
    {
      $tablePseudos[$user->getIdentifiant()] = $user->getPseudo();
    }

    // Création tableau des classements en fonction du succès
    $globalRanks = array();

    global $bdd;

    // Boucle pour parcourir tous les succès
    foreach ($listSuccess as $success)
    {
      if ($success->getDefined() == "Y" AND $success->getLimit_success() > 1)
      {
        $rankSuccess = array();

        // Boucle pour parcourir tous les succès débloqués par les utilisateurs
        $reponse = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" ORDER BY value DESC');
        while ($donnees = $reponse->fetch())
        {
          // On vérifie que l'utilisateur a débloqué le succès pour l'ajouter
          if ($donnees['value'] >= $success->getLimit_success())
          {
            $myRankSuccess = array('identifiant' => $donnees['identifiant'],
                                   'pseudo'      => $tablePseudos[$donnees['identifiant']],
                                   'value'       => $donnees['value'],
                                   'rank'        => 0
                                  );
            array_push($rankSuccess, $myRankSuccess);
          }
        }
        $reponse->closeCursor();

        // On filtre le tableau
        if (!empty($rankSuccess))
        {
          // Affectation du rang et suppression si rang > 3 (médaille de bronze)
          $prevRank    = $rankSuccess[0]['value'];
          $currentRank = 1;

          foreach ($rankSuccess as $key => &$rankSuccessUser)
          {
            $currentTotal = $rankSuccessUser['value'];

            if ($currentTotal != $prevRank)
            {
              $currentRank += 1;
              $prevRank = $rankSuccessUser['value'];
            }

            // Suppression des rangs > 3 sinon on enregistre le rang
            if ($currentRank > 3)
              unset($rankSuccess[$key]);
            else
             $rankSuccessUser['rank'] = $currentRank;
          }

          unset($rankSuccessUser);

          // On créé un tableau correspondant au classement sur un succès et on l'ajoute au tableau global
          $myGlobalRanks = array('id'             => $success->getId(),
                                 'level'          => $success->getLevel(),
                                 'order_success'  => $success->getOrder_success(),
                                 'podium'         => $rankSuccess
                                );

          array_push($globalRanks, $myGlobalRanks);
        }
      }
    }

    return $globalRanks;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    return $listeUsers;
  }
?>
