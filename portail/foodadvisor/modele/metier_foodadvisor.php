<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/restaurants.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Récupération de la liste des lieux
  // RETOUR : Liste des lieux
  function getLieux()
  {
    $listLocations = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT location FROM food_advisor_restaurants ORDER BY location ASC');
    while($donnees = $reponse->fetch())
    {
      array_push($listLocations, $donnees['location']);
    }
    $reponse->closeCursor();

    return $listLocations;
  }

  // METIER : Récupération de la liste des types
  // RETOUR : Liste des types
  function getTypesRestaurants()
  {
    $listTypes   = array();
    $stringTypes = "";

    global $bdd;

    // Lecture des types
    $reponse = $bdd->query('SELECT DISTINCT types FROM food_advisor_restaurants');
    while($donnees = $reponse->fetch())
    {
      $stringTypes .= $donnees['types'];
    }
    $reponse->closeCursor();

    // Extraction et tri
    $explodedTypes = explode(";", $stringTypes);

    foreach ($explodedTypes as $exploded)
    {
      if (!empty($exploded))
        array_push($listTypes, $exploded);
    }

    $listTypes = array_unique($listTypes);
    asort($listTypes);

    return $listTypes;
  }

  // METIER : Récupération de la liste des restaurants
  // RETOUR : Liste des restaurants
  function getRestaurants($list_locations)
  {
    $listRestaurants = array();

    global $bdd;

    foreach ($list_locations as $location)
    {
      $restaurants_by_location = array();

      $reponse = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE location = "' . $location . '" ORDER BY name ASC');
      while($donnees = $reponse->fetch())
      {
        array_push($restaurants_by_location, Restaurant::withData($donnees));
      }
      $reponse->closeCursor();

      $listRestaurants[$location] = $restaurants_by_location;
    }

    return $listRestaurants;
  }

  // METIER : Conversion du tableau d'objet des restaurants en tableau simple pour JSON
  // RETOUR : Tableau des restaurants par lieu
  function convertForJson($listeRestaurants)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listeRestaurantsAConvertir = array();

    foreach ($listeRestaurants as $keyLieu => $restaurantsParLieux)
    {
      $listeParLieu = array();

      foreach ($restaurantsParLieux as $restaurant)
      {
        $restaurantAConvertir = array('id'   => $restaurant->getId(),
                                      'name' => $restaurant->getName()
                                     );
        array_push($listeParLieu, $restaurantAConvertir);
      }

      //array_push($listeRestaurantsAConvertir, $listeParLieu);
      $listeRestaurantsAConvertir[$keyLieu] = $listeParLieu;
    }

    return $listeRestaurantsAConvertir;
  }

  // METIER : Détermine le choix du jour
  // RETOUR : Liste des choix du jour (tous)
  function getPropositions()
  {
    $listPropositions = array();

    global $bdd;

    $req1 = $bdd->query('SELECT DISTINCT id_restaurant FROM food_advisor_users WHERE date = "' . date("Ymd") . '"');
    while($data1 = $req1->fetch())
    {
      $myProposition = Proposition::withData($data1);

      // Données restaurant
      $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $myProposition->getId_restaurant());
      $data2 = $req2->fetch();

      $myProposition->setName($data2['name']);
      $myProposition->setPicture($data2['picture']);
      $myProposition->setLocation($data2['location']);
      $myProposition->setPhone($data2['phone']);

      $req2->closeCursor();

      // Nombre de participants
      $req3 = $bdd->query('SELECT COUNT(id_restaurant) AS nb_participants FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myProposition->getId_restaurant());
      $data3 = $req3->fetch();

      $myProposition->setNb_participants($data3['nb_participants']);

      $req3->closeCursor();

      // Proposition déterminée
      $req3 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myProposition->getId_restaurant());
      $data3 = $req3->fetch();

      if ($req3->rowCount() > 0)
      {
        $myProposition->setDetermined("Y");
        $myProposition->setCaller($data3['caller']);

        // Récupération pseudo et avatar
        $req4 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myProposition->getCaller() . '"');
        $data4 = $req4->fetch();

        $myProposition->setPseudo($data4['pseudo']);
        $myProposition->setAvatar($data4['avatar']);

        $req4->closeCursor();
      }

      $req3->closeCursor();

      array_push($listPropositions, $myProposition);
    }
    $req1->closeCursor();

    // Tris
    if (!empty($listPropositions))
    {
      // Tri par nombre de participants pour affecter le classement
      foreach ($listPropositions as $proposition)
      {
        $tri_nb_participants[] = $proposition->getNb_participants();
      }

      array_multisort($tri_nb_participants, SORT_DESC, $listPropositions);

      // Affectation du classement
      $prevNb_particpants = 0;
      $currentClassement  = 0;

      foreach ($listPropositions as $proposition)
      {
        $currentNb_participants = $proposition->getNb_participants();

        if ($currentNb_participants != $prevNb_particpants)
        {
          $currentClassement += 1;
          $prevNb_particpants = $currentNb_participants;
        }

        // On enregistre le rang
        $proposition->setClassement($currentClassement);
      }

      // Tri par détermination puis nombre de participants pour affichage
      $listPropositions = triPropositions($listPropositions);
    }

    return $listPropositions;
  }

  // METIER : Récupère un des participants du jour n'ayant pas appelé dans la semaine
  // RETOUR : Participant pouvant appeler
  function getCallers()
  {
    global $bdd;

    $caller = "";

    // Calcul des dates de la semaine
    $nb_jours_lundi    = 1 - date("N");
    $nb_jours_vendredi = 5 - date("N");
    $monday            = date("Ymd", strtotime('+' . $nb_jours_lundi . ' days'));
    $friday            = date("Ymd", strtotime('+' . $nb_jours_vendredi . ' days'));

    if (date("Ymd") >= $monday AND date("Ymd") <= $friday)
    {
      // Liste des participants du jour
      $listUsers = array();

      $req1 = $bdd->query('SELECT DISTINCT identifiant FROM food_advisor_users WHERE date = "' . date("Ymd") . '" ORDER BY identifiant ASC');
      while($data1 = $req1->fetch())
      {
        $req2 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant = "' . $data1['identifiant'] . '"');
        $data2 = $req2->fetch();
        $identifiant = $data2['identifiant'];
        $req2->closeCursor();

        array_push($listUsers, $identifiant);
      }
      $req1->closeCursor();

      // Liste des appelants de la semaine
      $listCallersWeek = array();

      $req3 = $bdd->query('SELECT DISTINCT caller FROM food_advisor_choices WHERE date != "' . date("Ymd") . '"
                                                                              AND date >= "' . $monday . '"
                                                                              AND date <= "' . $friday . '"
                                                                         ORDER BY caller ASC');
      while($data3 = $req3->fetch())
      {
        array_push($listCallersWeek, $data3['caller']);
      }
      $req3->closeCursor();

      // On ne concerve que ceux qui n'ont pas appelé dans la liste des utilisateurs
      $listCallers = $listUsers;

      foreach ($listCallers as $key => $user)
      {
        foreach ($listCallersWeek as $callerWeek)
        {
          if ($user == $callerWeek)
          {
            unset($listCallers[$key]);
            break;
          }
        }
      }

      // Détermination appelant aléatoire parmi ceux restant, ou par défaut une des personnes du jour
      if (!empty($listCallers))
        $caller = $listCallers[array_rand($listCallers, 1)];
      else
        $caller = $listUsers[array_rand($listUsers, 1)];
    }
    else
      $_SESSION['alerts']['week_end_determination'] = true;

    return $caller;
  }

  // METIER : Détermine celui qui réserve
  // RETOUR : Aucun
  function setDetermination($propositions, $caller)
  {
    if (!empty($propositions))
    {
      global $bdd;

      // Contrôle si choix déjà existant
      $existant = false;

      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $existant   = true;
        $old_caller = $data1['caller'];
        $id         = $data1['id'];
      }

      $req1->closeCursor();

      // Détermination restaurant et insertion en base
      $id_restaurants = array();

      foreach ($propositions as $proposition)
      {
        if ($proposition->getClassement() == 1)
        {
          // Détermination Id restaurant aléatoire
          array_push($id_restaurants, $proposition->getId_restaurant());
        }
      }

      $id_restaurant = $id_restaurants[array_rand($id_restaurants, 1)];

      // Mise à jour ou insertion
      if ($existant == true)
      {
        $choice = array('id_restaurant' => $id_restaurant,
                        'caller'        => $caller
                       );

        $req2 = $bdd->prepare('UPDATE food_advisor_choices SET id_restaurant = :id_restaurant,
                                                               caller        = :caller
                                                         WHERE id = ' . $id);
        $req2->execute($choice);
        $req2->closeCursor();

        // Génération succès (pour l'appelant si modifié)
        insertOrUpdateSuccesValue('star-chief', $old_caller, -1);
      }
      else
      {
        $choice = array('id_restaurant' => $id_restaurant,
                        'date'          => date("Ymd"),
                        'caller'        => $caller
                       );

        $req2 = $bdd->prepare('INSERT INTO food_advisor_choices(id_restaurant,
                                                                date,
                                                                caller
                                                               )
                                                        VALUES(:id_restaurant,
                                                               :date,
                                                               :caller
                                                              )');
        $req2->execute($choice);
        $req2->closeCursor();
      }

      // Génération succès (pour le nouvel appelant)
      insertOrUpdateSuccesValue('star-chief', $caller, 1);
    }
  }

  // METIER : Récupère les choix de l'utilisateur
  // RETOUR : Liste des choix du jour (utilisateur)
  function getMyChoices($user)
  {
    $listChoices = array();

    global $bdd;

    // Récupération des choix
    $reponse1 = $bdd->query('SELECT * FROM food_advisor_users WHERE identifiant = "' . $user . '" AND date = "' . date("Ymd") . '" ORDER BY id ASC');
    while($donnees1 = $reponse1->fetch())
    {
      $myChoice = Choix::withData($donnees1);

      // Données restaurant
      $reponse2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $myChoice->getId_restaurant());
      $donnees2 = $reponse2->fetch();

      $myChoice->setName($donnees2['name']);
      $myChoice->setPicture($donnees2['picture']);
      $myChoice->setLocation($donnees2['location']);

      $reponse2->closeCursor();

      array_push($listChoices, $myChoice);
    }
    $reponse1->closeCursor();

    return $listChoices;
  }

  // METIER : Récupère les choix de la semaine
  // RETOUR : Liste des choix de la semaine
  function getWeekChoices()
  {
    $listWeekChoices = array();
    $semaine         = array("lundi", "mardi", "mercredi", "jeudi", "vendredi");

    // Calcul des dates de la semaine
    $nb_jours_lundi    = 1 - date("N");
    $nb_jours_vendredi = 5 - date("N");
    $monday            = date("Ymd", strtotime('+' . $nb_jours_lundi . ' days'));
    $friday            = date("Ymd", strtotime('+' . $nb_jours_vendredi . ' days'));

    global $bdd;

    for ($i = $monday; $i <= $friday; $i++)
    {
      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . $i . '"');
      $data1 = $req1->fetch();

      $myWeekChoice = NULL;

      if ($req1->rowCount() > 0)
      {
        $myWeekChoice = Proposition::withData($data1);

        // Données restaurant
        $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $myWeekChoice->getId_restaurant());
        $data2 = $req2->fetch();

        $myWeekChoice->setName($data2['name']);
        $myWeekChoice->setPicture($data2['picture']);
        $myWeekChoice->setLocation($data2['location']);

        $req2->closeCursor();

        // Nombre de participants
        $req3 = $bdd->query('SELECT COUNT(id_restaurant) AS nb_participants FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myWeekChoice->getId_restaurant());
        $data3 = $req3->fetch();

        $myWeekChoice->setNb_participants($data3['nb_participants']);

        $req3->closeCursor();

        // Récupération pseudo et avatar
        $req4 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myWeekChoice->getCaller() . '"');
        $data4 = $req4->fetch();

        $myWeekChoice->setPseudo($data4['pseudo']);
        $myWeekChoice->setAvatar($data4['avatar']);

        $req4->closeCursor();
      }

      // Ajout au tableau
      $jour                             = $i - $monday;
      $listWeekChoices[$semaine[$jour]] = $myWeekChoice;
    }
    $req1->closeCursor();

    return $listWeekChoices;
  }

  // METIER : Insère un ou plusieurs choix utilisateur
  // RETOUR : Aucun
  function insertChoices($post, $user)
  {
    global $bdd;

    $max_choices = 5;
    $control_ok  = true;

    // Calcul des dates de la semaine
    $nb_jours_lundi    = 1 - date("N");
    $nb_jours_vendredi = 5 - date("N");
    $monday            = date("Ymd", strtotime('+' . $nb_jours_lundi . ' days'));
    $friday            = date("Ymd", strtotime('+' . $nb_jours_vendredi . ' days'));

    // Contrôle saisie possible en fonction des dates
    if (date("Ymd") < $monday OR date("Ymd") > $friday)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_saisie'] = true;
    }

    // Contrôle choix saisi en double
    if ($control_ok == true)
    {
      for ($i = 1; $i <= $max_choices; $i++)
      {
        if (isset($post['select_lieu'][$i]) AND !empty($post['select_lieu'][$i]) AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          for ($j = 1; $j <= $max_choices; $j++)
          {
            if (isset($post['select_lieu'][$j]) AND !empty($post['select_lieu'][$j]) AND isset($post['select_restaurant'][$j]) AND !empty($post['select_restaurant'][$j]))
            {
              if ($i != $j AND $post['select_lieu'][$i] == $post['select_lieu'][$j])
              {
                $control_ok                         = false;
                $_SESSION['alerts']['wrong_choice'] = true;
                break;
              }
            }
          }
        }
      }
    }

    // Contrôle choix déjà existant
    if($control_ok == true)
    {
      for ($i = 1; $i <= $max_choices; $i++)
      {
        if (isset($post['select_lieu'][$i]) AND !empty($post['select_lieu'][$i]) AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant = "' . $post['select_restaurant'][$i] . '" AND identifiant = "' . $user . '" AND date = "' . date("Ymd") . '"');
          $donnees = $req1->fetch();

          if ($req1->rowCount() > 0)
          {
            $control_ok                                 = false;
            $_SESSION['alerts']['wrong_choice_already'] = true;
            break;
          }

          $req1->closeCursor();
        }
      }
    }

    // Récupération des données et insertion en base
    if ($control_ok == true)
    {
      for ($i = 1; $i <= $max_choices; $i++)
      {
        if (isset($post['select_lieu'][$i]) AND !empty($post['select_lieu'][$i]) AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          // Id restaurant
          $id_restaurant = $post['select_restaurant'][$i];

          // Identifiant utilisateur
          $identifiant   = $user;

          // Date de saisie
          $date          = date("Ymd");

          // Heure choisie
          if (isset($post['select_heures'][$i]) AND !empty($post['select_heures'][$i]) AND isset($post['select_minutes'][$i]) AND !empty($post['select_minutes'][$i]))
            $time        = $post['select_heures'][$i] . $post['select_minutes'][$i];
          else
            $time        = "";

          // Transports choisis
          $transports    = "";

          if (isset($post['checkbox_feet'][$i]) AND $post['checkbox_feet'][$i] == "F")
            $transports .= $post['checkbox_feet'][$i] . ';';

          if (isset($post['checkbox_bike'][$i]) AND $post['checkbox_bike'][$i] == "B")
            $transports .= $post['checkbox_bike'][$i] . ';';

          if (isset($post['checkbox_tram'][$i]) AND $post['checkbox_tram'][$i] == "T")
            $transports .= $post['checkbox_tram'][$i] . ';';

          if (isset($post['checkbox_car'][$i]) AND $post['checkbox_car'][$i] == "C")
            $transports .= $post['checkbox_car'][$i] . ';';

          // Menu saisi
          $menu          = "";

          if (!isset($post['saisie_entree'][$i]) AND !isset($post['saisie_plat'][$i]) AND !isset($post['saisie_dessert'][$i]))
            $menu       .= ";;;";
          else
          {
            $menu       .= str_replace(";", " ", $post['saisie_entree'][$i]) . ';';
            $menu       .= str_replace(";", " ", $post['saisie_plat'][$i]) . ';';
            $menu       .= str_replace(";", " ", $post['saisie_dessert'][$i]) . ';';
          }

          // Tableau d'un choix
          $choice = array('id_restaurant' => $id_restaurant,
                          'identifiant'   => $identifiant,
                          'date'          => $date,
                          'time'          => $time,
                          'transport'     => $transports,
                          'menu'          => $menu
                        );

          // On insère dans la table
          $req2 = $bdd->prepare('INSERT INTO food_advisor_users(id_restaurant,
                                                                identifiant,
                                                                date,
                                                                time,
                                                                transport,
                                                                menu
                                                              )
                                                        VALUES(:id_restaurant,
                                                               :identifiant,
                                                               :date,
                                                               :time,
                                                               :transport,
                                                               :menu
                                                              )');
          $req2->execute($choice);
          $req2->closeCursor();

          // Relance de la détermination si besoin
          relanceDetermination();
        }
      }
    }
  }

  // METIER : Supprime un choix utilisateur
  // RETOUR : Aucun
  function deleteChoice($id)
  {
    global $bdd;

    // Suppression de la base
    $req = $bdd->exec('DELETE FROM food_advisor_users WHERE id = ' . $id);

    // Relance de la détermination si besoin
    relanceDetermination();
  }

  // METIER : Tri des propositions
  // RETOUR : Propositions triées
  function triPropositions($propositions)
  {
    // Tri par détermination puis par nombre de participants
    foreach ($propositions as $proposition)
    {
      $tri_determined[]      = $proposition->getDetermined();
      $tri_nb_participants[] = $proposition->getNb_participants();
    }

    array_multisort($tri_determined, SORT_DESC, $tri_nb_participants, SORT_DESC, $propositions);

    return $propositions;
  }

  // METIER : Relance la détermination
  // RETOUR : Aucun
  function relanceDetermination()
  {
    global $bdd;

    // Si une détermination du jour a déjà été effectuée, on doit relancer la détermination ou éventuellement la supprimer si c'était le dernier choix
    $existant = false;

    $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $existant    = true;
      $id_existant = $data1['id'];
    }

    $req1->closeCursor();

    if ($existant == true)
    {
      // Nombre de choix restants
      $req2 = $bdd->query('SELECT COUNT(id) AS nb_choix_restants FROM food_advisor_users WHERE date = "' . date("Ymd") . '"');
      $data2 = $req2->fetch();
      $nb_choix_restants = $data2['nb_choix_restants'];
      $req2->closeCursor();

      // Relance de la détermination
      if ($nb_choix_restants > 0)
      {
        $appelants    = getCallers();
        $propositions = getPropositions();

        if (!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
          setDetermination($propositions, $appelants);
      }
      // Suppression de la détermination
      else
        $req3 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id = ' . $id_existant);
    }
  }

  // METIER : Insère un nouveau restaurant
  // RETOUR : Id enregistrement créé
  function insertRestaurant($post, $files, $user)
  {
    $new_id     = NULL;
    $control_ok = true;

    // Récupération des données et sauvegarde en session
    if ($control_ok == true)
    {
      $nom_restaurant         = $post['name_restaurant'];
      $telephone_restaurant   = $post['phone_restaurant'];
      $website_restaurant     = $post['website_restaurant'];
      $plan_restaurant        = $post['plan_restaurant'];
      $description_restaurant = $post['description_restaurant'];

      if ($post['location'] == "other_location"  AND !empty($post['saisie_other_location']))
        $lieu_restaurant      = $post['saisie_other_location'];
      else
        $lieu_restaurant      = $post['location'];

      if (isset($post['types_restaurants']))
      {
        $types_restaurant     = array_unique($post['types_restaurants']);

        foreach ($types_restaurant as $keyType => $type)
        {
          if (empty($type))
            unset($types_restaurant[$keyType]);
        }
      }

      $_SESSION['save']['name_restaurant']        = $post['name_restaurant'];
      $_SESSION['save']['phone_restaurant']       = $post['phone_restaurant'];
      $_SESSION['save']['website_restaurant']     = $post['website_restaurant'];
      $_SESSION['save']['plan_restaurant']        = $post['plan_restaurant'];
      $_SESSION['save']['description_restaurant'] = $post['description_restaurant'];
      $_SESSION['save']['location']               = $post['location'];
      $_SESSION['save']['saisie_other_location']  = $post['saisie_other_location'];

      if (isset($post['types_restaurants']))
        $_SESSION['save']['types_restaurants']    = $types_restaurant;
    }

    // Tri et formatage types de restaurants
    if ($control_ok == true)
    {
      $types_formatted = "";

      if (!empty($types_restaurant))
      {
        // Majuscules et tri
        $types_restaurant = array_map('ucfirst', $types_restaurant);
        asort($types_restaurant);

        // Formatage
        foreach ($types_restaurant as $type)
        {
          $types_formatted .= $type . ";";
        }
      }
    }

    // Contrôle numéro téléphone numérique
    if ($control_ok == true)
    {
      if (!empty($telephone_restaurant))
      {
        if (!is_numeric($telephone_restaurant) OR strlen($telephone_restaurant) != 10)
        {
          $_SESSION['alerts']['wrong_phone_number'] = true;
          $control_ok                               = false;
        }
      }
    }

    // Enregistrement et contrôles image
    if ($control_ok == true)
    {
      $new_name = "";

      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Si on a bien une image
   		if ($files['image_restaurant']['name'] != NULL)
   		{
        // Dossier de destination
   			$restaurant_dir = $dossier . '/';

        // Données du fichier
   			$file      = $files['image_restaurant']['name'];
   			$tmp_file  = $files['image_restaurant']['tmp_name'];
   			$size_file = $files['image_restaurant']['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
   			if ($size_file < $maxsize)
   			{
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
            exit("Le fichier est introuvable");

          // Contrôle type de fichier
          $type_file = $files['image_restaurant']['type'];

          if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
            exit("Le fichier n'est pas une image valide");
          else
   				{
   					$type_image = pathinfo($file, PATHINFO_EXTENSION);
   					$new_name   = rand() . '.' . $type_image;
   				}

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $restaurant_dir . $new_name))
            exit("Impossible de copier le fichier dans $restaurant_dir");

          // Rotation de l'image
          $rotate = rotateImage($restaurant_dir . $new_name, $type_image);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
          imagethumb($restaurant_dir . $new_name, $restaurant_dir . $new_name, 500, FALSE, TRUE);
        }
      }
    }

    // Enregistrement BDD
    if ($control_ok == true)
    {
      global $bdd;

      $restaurant = array('name'        => $nom_restaurant,
                          'picture'     => $new_name,
                          'types'       => $types_formatted,
                          'location'    => $lieu_restaurant,
                          'phone'       => $telephone_restaurant,
                          'website'     => $website_restaurant,
                          'plan'        => $plan_restaurant,
                          'description' => $description_restaurant
                        );

      $req = $bdd->prepare('INSERT INTO food_advisor_restaurants(name,
                                                                 picture,
                    																						 types,
                                                                 location,
                                                                 phone,
                                                                 website,
                    																						 plan,
                                                                 description
                                                                )
                    																      VALUES(:name,
                                                                 :picture,
                    																						 :types,
                                                                 :location,
                    																					   :phone,
                                                                 :website,
                                                                 :plan,
                                                                 :description
                                                                )');
      $req->execute($restaurant);
      $req->closeCursor();

      // Récupération Id créé
      $new_id = $bdd->lastInsertId();

      // Génération succès
      insertOrUpdateSuccesValue('restaurant-finder', $user, 1);

      // Ajout expérience
      insertExperience($user, 'add_restaurant');

      // Message d'alerte
      $_SESSION['alerts']['restaurant_added'] = true;
    }

    return $new_id;
  }

  // METIER : Mise à jour d'un restaurant
  // RETOUR : Aucun
  function updateRestaurant($post, $files, $id_restaurant)
  {
    $control_ok = true;

    global $bdd;

    // Récupération des données
    if ($control_ok == true)
    {
      $nom_restaurant         = $post['name_restaurant'];
      $telephone_restaurant   = $post['phone_restaurant'];
      $website_restaurant     = $post['website_restaurant'];
      $plan_restaurant        = $post['plan_restaurant'];
      $description_restaurant = $post['description_restaurant'];

      if ($post['location'] == "other_location" AND !empty($post['update_other_location']))
        $lieu_restaurant      = $post['update_other_location'];
      else
        $lieu_restaurant      = $post['location'];

      if (isset($post['types_restaurants_update_' . $id_restaurant]))
      {
        $types_restaurant     = array_unique($post['types_restaurants_update_' . $id_restaurant]);

        foreach ($types_restaurant as $keyType => $type)
        {
          if (empty($type))
            unset($types_restaurant[$keyType]);
        }
      }
    }

    // Tri et formatage types de restaurants
    if ($control_ok == true)
    {
      $types_formatted = "";

      if (!empty($types_restaurant))
      {
        // Majuscules et tri
        $types_restaurant = array_map('ucfirst', $types_restaurant);
        asort($types_restaurant);

        // Formatage
        foreach ($types_restaurant as $type)
        {
          $types_formatted .= $type . ";";
        }
      }
    }

    // Contrôle numéro téléphone numérique
    if ($control_ok == true)
    {
      if (!empty($telephone_restaurant))
      {
        if (!is_numeric($telephone_restaurant) OR strlen($telephone_restaurant) != 10)
        {
          $_SESSION['alerts']['wrong_phone_number'] = true;
          $control_ok                               = false;
        }
      }
    }

    // Enregistrement et contrôles image
    if ($control_ok == true)
    {
      $new_name = "";

      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Si on a bien une image
      if ($files['image_restaurant']['name'] != NULL)
      {
        // Dossier de destination
        $restaurant_dir = $dossier . '/';

        // Suppression ancienne image
        $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
        $data1 = $req1->fetch();

        if (isset($data1['picture']) AND !empty($data1['picture']))
          unlink ("../../includes/images/foodadvisor/" . $data1['picture']);

        $req1->closeCursor();

        // Données du fichier
        $file      = $files['image_restaurant']['name'];
        $tmp_file  = $files['image_restaurant']['tmp_name'];
        $size_file = $files['image_restaurant']['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
        if ($size_file < $maxsize)
        {
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
            exit("Le fichier est introuvable");

          // Contrôle type de fichier
          $type_file = $files['image_restaurant']['type'];

          if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
            exit("Le fichier n'est pas une image valide");
          else
          {
            $type_image = pathinfo($file, PATHINFO_EXTENSION);
            $new_name   = rand() . '.' . $type_image;
          }

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $restaurant_dir . $new_name))
            exit("Impossible de copier le fichier dans $restaurant_dir");

          // Rotation de l'image
          $rotate = rotateImage($restaurant_dir . $new_name, $type_image);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
          imagethumb($restaurant_dir . $new_name, $restaurant_dir . $new_name, 500, FALSE, TRUE);
        }
      }
      else
      {
        // Récupération nom fichier déjà présent
        $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
        $data1 = $req1->fetch();

        if (isset($data1['picture']) AND !empty($data1['picture']))
          $new_name = $data1['picture'];

        $req1->closeCursor();
      }
    }

    // Enregistrement BDD
    if ($control_ok == true)
    {
      $restaurant = array('name'        => $nom_restaurant,
                          'picture'     => $new_name,
                          'types'       => $types_formatted,
                          'location'    => $lieu_restaurant,
                          'phone'       => $telephone_restaurant,
                          'website'     => $website_restaurant,
                          'plan'        => $plan_restaurant,
                          'description' => $description_restaurant
                        );

      $req2 = $bdd->prepare('UPDATE food_advisor_restaurants SET name        = :name,
                                                                 picture     = :picture,
                                                                 types       = :types,
                                                                 location    = :location,
                                                                 phone       = :phone,
                                                                 website     = :website,
                                                                 plan        = :plan,
                                                                 description = :description
                                                           WHERE id = ' . $id_restaurant);
      $req2->execute($restaurant);
      $req2->closeCursor();

      // Message d'alerte
      $_SESSION['alerts']['restaurant_updated'] = true;
    }
  }

  // METIER : Supprime un restaurant
  // RETOUR : Aucun
  function deleteRestaurant($id_restaurant)
  {
    global $bdd;

    // Suppression image
    $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
    $data1 = $req1->fetch();

    if (isset($data1['picture']) AND !empty($data1['picture']))
      unlink ("../../includes/images/foodadvisor/" . $data1['picture']);

    $req1->closeCursor();

    // Suppression enregistrement en base
    $req2 = $bdd->exec('DELETE FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);

    // Message alerte
    $_SESSION['alerts']['restaurant_deleted'] = true;
  }
?>
