<?php
  include_once('../../includes/classes/restaurants.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Récupération de la liste des lieux
  // RETOUR : Liste des lieux
  function getLieux()
  {
    // Récupération de la liste des lieux
    $listeLieux = physiqueLieux();

    // Retour
    return $listeLieux;
  }

  // METIER : Détermine si l'utilisateur fait bande à part
  // RETOUR : Booléen
  function getSolo($user)
  {
    // Vérification si bande à part
    $solo = physiqueSolo($user);

    // Retour
    return $solo;
  }

  // METIER : Récupère les choix du jour
  // RETOUR : Liste des choix du jour (tous)
  function getPropositions($recuperationDetails)
  {
    // Récupération des différents restaurants proposés
    $listePropositions = physiquePropositions();

    // Récupération des données de chaque restaurant
    foreach ($listePropositions as $proposition)
    {
      // Lecture des données restaurant
      $restaurant = physiqueDonneesRestaurant($proposition->getId_restaurant());

      // Ajout des données restaurant à la proposition
      $proposition->setName($restaurant->getName());
      $proposition->setPicture($restaurant->getPicture());
      $proposition->setLocation($restaurant->getLocation());
      $proposition->setTypes($restaurant->getTypes());
      $proposition->setPhone($restaurant->getPhone());
      $proposition->setWebsite($restaurant->getWebsite());
      $proposition->setPlan($restaurant->getPlan());
      $proposition->setLafourchette($restaurant->getLafourchette());
      $proposition->setOpened($restaurant->getOpened());
      $proposition->setMin_price(str_replace('.', ',', $restaurant->getMin_price()));
      $proposition->setMax_price(str_replace('.', ',', $restaurant->getMax_price()));
      $proposition->setDescription($restaurant->getDescription());

      // Contrôle restaurant disponible ce jour
      $availableDay   = true;
      $explodedOpened = explode(';', $proposition->getOpened());

      foreach ($explodedOpened as $keyOpened => $opened)
      {
        if (!empty($opened))
        {
          if (date('N') == $keyOpened + 1 AND $opened == 'N')
          {
            $availableDay = false;
            break;
          }
        }
      }

      // Récupération des données si restaurant ouvert
      if ($availableDay == true)
      {
        // Nombre de participants
        $proposition->setNb_participants(physiqueNombreParticipants($proposition->getId_restaurant()));

        // Vérification proposition déterminée
        $propositionDeterminee = physiquePropositionDeterminee($proposition->getId_restaurant());

        // Récupération des données de la proposition déterminée
        if (!empty($propositionDeterminee))
        {
          // Lecture des données proposition déterminée
          $proposition->setDetermined('Y');
          $proposition->setCaller($propositionDeterminee->getCaller());
          $proposition->setReserved($propositionDeterminee->getReserved());

          // Recherche pseudo et avatar appelant
          $appelant = physiqueUser($proposition->getCaller());

          $proposition->setPseudo($appelant->getPseudo());
          $proposition->setAvatar($appelant->getAvatar());
        }

        // Récupération détails proposition
        if ($recuperationDetails == true)
        {
          // Lecture détail de chaque utilisateur
          $detailsProposition = physiqueDetailsProposition($proposition->getId_restaurant());

          // Recherche pseudo et avatar utilisateur
          foreach ($detailsProposition as &$detail)
          {
            $user = physiqueUser($detail['identifiant']);

            $detail['pseudo'] = $user->getPseudo();
            $detail['avatar'] = $user->getAvatar();
          }

          unset($detail);

          // Récupération des détails
          $proposition->setDetails($detailsProposition);
        }
      }
    }

    // Tris
    if (!empty($listePropositions))
    {
      // Tri par nombre de participants pour affecter le classement
      foreach ($listePropositions as $proposition)
      {
        $triNombreParticipants[] = $proposition->getNb_participants();
      }

      // Tri
      array_multisort($triNombreParticipants, SORT_DESC, $listePropositions);

      unset($triNombreParticipants);

      // Affectation du classement
      $prevNombreParticpants = 0;
      $currentClassement     = 0;

      foreach ($listePropositions as $proposition)
      {
        $currentNombreParticipants = $proposition->getNb_participants();

        if ($currentNombreParticipants != $prevNombreParticpants)
        {
          $currentClassement    += 1;
          $prevNombreParticpants = $currentNombreParticipants;
        }

        // On enregistre le rang
        $proposition->setClassement($currentClassement);
      }

      // Tri par détermination puis par nombre de participants pour affichage
      foreach ($listePropositions as $proposition)
      {
        $triDetermined[]         = $proposition->getDetermined();
        $triNombreParticipants[] = $proposition->getNb_participants();
      }

      // Tri
      array_multisort($triDetermined, SORT_DESC, $triNombreParticipants, SORT_DESC, $listePropositions);
    }

    // Retour
    return $listePropositions;
  }

  // METIER : Récupère un des restaurants pouvant être déterminé ce jour
  // RETOUR : Id restaurant déterminé
  function getRestaurantDetermined($listePropositions)
  {
    // Initialisations
    $idRestaurant = NULL;
    $control_ok   = true;

    // Contrôle date de détermination
    $control_ok = controleDateDetermination();

    // Contrôle heure de détermination
    if ($control_ok == true)
      $control_ok = controleHeureDetermination();

    // Détermination Id restaurant aléatoire
    if ($control_ok == true)
    {
      // Extraction des Id en tête
      $idRestaurants = array();

      foreach ($listePropositions as $proposition)
      {
        if ($proposition->getClassement() == 1)
        {
          array_push($idRestaurants, $proposition->getId_restaurant());
        }
      }

      // Détermination Id aléatoire
      $idRestaurant = $idRestaurants[array_rand($idRestaurants, 1)];
    }

    // Retour
    return $idRestaurant;
  }

  // METIER : Vérification si réservé et retour identifiant
  // RETOUR : Identifiant réservation
  function getReserved()
  {
    // Récupération de l'identifiant de l'appelant
    $appelant = physiqueIdentifiantCaller();

    // Retour
    return $appelant;
  }

  // METIER : Détermine un des participants du jour n'ayant pas appelé dans la semaine
  // RETOUR : Participant pouvant appeler
  function getCallers($idRestaurant)
  {
    // Initialisations
    $caller = '';

    // Calcul des dates de la semaine
    $nombreJoursLundi    = 1 - date('N');
    $nombreJoursVendredi = 5 - date('N');
    $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
    $vendredi            = date('Ymd', strtotime('+' . $nombreJoursVendredi . ' days'));

    // Récupération de la liste des participants du jour
    $listeParticipants = physiqueParticipants($idRestaurant);

    // Récupération de la liste des appelants de la semaine
    $listeAppelants = physiqueAppelants($lundi, $vendredi);

    // Filtrage des participants ayant déjà appelé
    $listeParticipantsFiltres = $listeParticipants;

    foreach ($listeParticipantsFiltres as $keyParticipant => $participant)
    {
      foreach ($listeAppelants as $appelant)
      {
        if ($participant == $appelant)
        {
          unset($listeParticipantsFiltres[$keyParticipant]);
          break;
        }
      }
    }

    // Détermination appelant aléatoire parmi ceux restant, ou par défaut une des personnes du jour
    if (!empty($listCallers))
      $caller = $listeParticipantsFiltres[array_rand($listCallers, 1)];
    else
      $caller = $listeParticipants[array_rand($listeParticipants, 1)];

    // Retour
    return $caller;
  }

  // METIER : Récupère les choix de l'utilisateur
  // RETOUR : Liste des choix du jour (utilisateur)
  function getMyChoices($user)
  {
    // Récupération des choix de l'utilisateur
    $listeChoix = physiqueChoix($user);

    // Ajout des informations des restaurants
    foreach ($listeChoix as $monChoix)
    {
      $restaurant = physiqueDonneesRestaurant($monChoix->getId_restaurant());

      $monChoix->setName($restaurant->getName());
      $monChoix->setPicture($restaurant->getPicture());
      $monChoix->setLocation($restaurant->getLocation());
      $monChoix->setOpened($restaurant->getOpened());
    }

    // Retour
    return $listeChoix;
  }

  // METIER : Détermine celui qui réserve
  // RETOUR : Aucun
  function setDetermination($propositions, $idRestaurant, $appelant)
  {
    // Détermination s'il y a des propositions
    if (!empty($propositions))
    {
      // Contrôle si détermination déjà existante
      $determinationExistante = physiqueDeterminationExistante();

      // Si la détermination existe déjà, mise à jour
      if ($determinationExistante == true)
      {
        // Récupération des données de la détermination
        $determination = physiqueDetermination();

        // Modification de l'enregistrement en base
        $nouvelleDetermination = array('id_restaurant' => $idRestaurant,
                                       'caller'        => $appelant
                                      );

        physiqueUpdateDetermination($nouvelleDetermination, $determination['idTable']);

        // Génération succès (pour l'appelant si modifié)
        insertOrUpdateSuccesValue('star-chief', $determination['oldCaller'], -1);
      }
      // Sinon insertion
      else
      {
        // Insertion de l'enregistrement en base
        $nouvelleDetermination = array('id_restaurant' => $idRestaurant,
                                       'date'          => date('Ymd'),
                                       'caller'        => $appelant,
                                       'reserved'      => 'N'
                                      );

         physiqueInsertionDetermination($nouvelleDetermination);
      }

      // Génération succès (pour le nouvel appelant)
      insertOrUpdateSuccesValue('star-chief', $appelant, 1);
    }
  }

  // METIER : Relance la détermination
  // RETOUR : Aucun
  function relanceDetermination()
  {
    // Contrôle si détermination déjà existante
    $determinationExistante = physiqueDeterminationExistante();

    // Si une détermination du jour a déjà été effectuée, on doit relancer la détermination ou éventuellement la supprimer si c'était le dernier choix
    if ($determinationExistante == true)
    {
      // Recherche du nombre de choix restants
      $nombreChoixRestants = physiqueChoixRestants();

      // Relance de la détermination si possible, sinon suppression
      if ($nombreChoixRestants > 0)
      {
        // Récupération des propositions (sans détails)
        $propositions = getPropositions(false);

        // Récupération de l'Id du restaurant déterminé
        $idRestaurant = getRestaurantDetermined($propositions);

        // Détermination si bande à part
        $isSolo = getSolo($_SESSION['user']['identifiant']);

        // Détermination si restaurant réservé
        $isReserved = getReserved();

        // Lancement de la détermination
        if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
        AND (!isset($_SESSION['alerts']['heure_determination'])    OR $_SESSION['alerts']['heure_determination']    != true)
        AND  $isSolo != true AND empty($isReserved))
        {
          // Récupération des appelants possibles
          $appelant = getCallers($idRestaurant);

          // Lancement de la détermination
          setDetermination($propositions, $idRestaurant, $appelant);
        }
      }
      else
      {
        // Suppression de la détermination du jour
        physiqueDeleteDetermination();
      }
    }
  }

  // METIER : Insère un choix rapide
  // RETOUR : Id restaurant
  function insertFastChoice($post, $isSolo, $user)
  {
    // Initialisations
    $idRestaurant = $post['id_restaurant'];
    $control_ok   = true;

    // Contrôle date de saisie
    $control_ok = controleDateSaisie();

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie();

    // Contrôle bande à part
    if ($control_ok == true)
      $control_ok = controleSoloSaisie($isSolo);

    // Contrôle choix déjà existant
    if ($control_ok == true)
      $control_ok = controleChoixExistant($idRestaurant, $user);

    // Lecture des données restaurant
    if ($control_ok == true)
      $restaurant = physiqueDonneesRestaurant($idRestaurant);

    // Contrôle restaurant ouvert
    if ($control_ok == true)
      $control_ok = controleRestaurantOuvert($restaurant->getOpened());

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $choix = array('id_restaurant' => $idRestaurant,
                     'identifiant'   => $user,
                     'date'          => $date,
                     'time'          => $time,
                     'transports'    => $transports,
                     'menu'          => $menu
                    );

      physiqueInsertionChoix($choix);

      // Relance de la détermination si besoin
      relanceDetermination();
    }

    // Retour
    return $idRestaurant;
  }
?>
