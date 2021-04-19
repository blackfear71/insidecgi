<?php
  include_once('../includes/functions/modeles_mails.php');
  include_once('../includes/classes/expenses.php');
  include_once('../includes/classes/missions.php');
  include_once('../includes/classes/movies.php');
  include_once('../includes/classes/profile.php');

  // METIER : Insertion notification sortie cinéma du jour
  // RETOUR : Compte-rendu traitement
  function generateNotificationsSortieCinema()
  {
    // Initialisations
    $nombreNotifications = 0;
    $log                 = array('titre'  => 'Sortie cinéma du jour',
                                 'status' => 'KO',
                                 'infos'  => ''
                                );

    // Récupération de la liste des films ayant une sortie ce jour
    $listeFilmsSortie = physiqueSortiesOrganisees();

    // Contrôle et insertion des notifications
    if (count($listeFilmsSortie) > 0)
    {
      foreach ($listeFilmsSortie as $film)
      {
        // Contrôle notification non existante
        $notificationCinemaExist = controlNotification('cinema', $film->getId());

        // Insertion notification
        if ($notificationCinemaExist != true)
        {
          insertNotification('admin', 'cinema', $film->getId());

          // Compteur de notifications générées
          $nombreNotifications++;
        }

        // Ajout des données au log
        $log['status'] = 'OK';
        $log['infos']  = $nombreNotifications . ' notifications insérées';
      }
    }
    else
    {
      // Ajout des données au log
      $log['status'] = 'OK';
      $log['infos']  = 'Pas de sorties organisées';
    }

    // Retour
    return $log;
  }

  // METIER : Lecture des données missions
  // RETOUR : Tableau de compte-rendus des traitements
  function generateNotificationsMissions()
  {
    // Initialisations
    $listeLogsMissions = array();

    // Récupération de la durée des missions
    $dureesMissions = physiqueDureesMissions();

    // Contrôle et insertion des notifications
    if (count($dureesMissions) > 0)
    {
      foreach ($dureesMissions as $mission)
      {
        if ($mission['duration'] == 'O' OR $mission['duration'] == 'F' OR $mission['duration'] == 'L')
        {
          // Détermination des données
          switch ($mission['duration'])
          {
            case 'O':
              $notification = 'one_mission';
              $log          = array('titre'  => 'Mission unique (' . htmlspecialchars($mission['mission']) . ')',
                                    'status' => 'KO',
                                    'infos'  => ''
                                   );
              break;

            case 'F':
              $notification = 'start_mission';
              $log          = array('titre'  => 'Début de mission (' . htmlspecialchars($mission['mission']) . ')',
                                    'status' => 'KO',
                                    'infos'  => ''
                                   );
              break;

            case 'L':
              $notification = 'end_mission';
              $log          = array('titre'  => 'Fin de mission (' . htmlspecialchars($mission['mission']) . ')',
                                    'status' => 'KO',
                                    'infos'  => ''
                                   );
              break;

            default:
              break;
          }

          // Contrôle notification non existante
          $notificationMissionExist = controlNotification($notification, $mission['id_mission']);

          // Insertion notification
          if ($notificationMissionExist != true)
          {
            insertNotification('admin', $notification, $mission['id_mission']);

            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Notification insérée';
          }
          else
          {
            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Notification déjà insérée';
          }

          // Ajout du compte-rendu à la liste des logs
          array_push($listeLogsMissions, $log);
        }
      }
    }
    else
    {
      // Ajout des données au log
      $log['status'] = 'OK';
      $log['infos']  = 'Pas de sorties organisées';
    }

    // Retour
    return $listeLogsMissions;
  }

  // METIER : Attribution expérience fin de mission
  // RETOUR : Compte-rendu traitement
  function insertExperienceGagnants()
  {
    // Initialisations
    $chaineExecutee    = false;
    $dossierQuotidien  = '../cron/logs/daily';
    $listeLogsMissions = array();

    // Scan des fichiers présents par ordre décroissant
    $fichiersQuotidiens = scandir($dossierQuotidien, 1);

    // Suppression des racines du dossier de la liste
    unset($fichiersQuotidiens[array_search('..', $fichiersQuotidiens)]);
    unset($fichiersQuotidiens[array_search('.', $fichiersQuotidiens)]);

    // Détermination si la chaîne CRON est déjà passée
    if (!empty($fichiersQuotidiens))
    {
      // Tri sur date
      foreach ($fichiersQuotidiens as $fichier)
      {
        $triAnnee[]   = substr($fichier, 12, 4);
        $triMois[]    = substr($fichier, 9, 2);
        $triJour[]    = substr($fichier, 6, 2);
        $triHeure[]   = substr($fichier, 17, 2);
        $triMinute[]  = substr($fichier, 20, 2);
        $triSeconde[] = substr($fichier, 23, 2);
      }

      array_multisort($triAnnee, SORT_DESC, $triMois, SORT_DESC, $triJour, SORT_DESC, $triHeure, SORT_DESC, $triMinute, SORT_DESC, $triSeconde, SORT_DESC, $fichiersQuotidiens);

      // Test si CRON déjà passé
      foreach ($fichiersQuotidiens as $fichier)
      {
        // Récupération de la date du fichier
        $dateFichier = substr($fichier, 12, 4) . substr($fichier, 9, 2) . substr($fichier, 6, 2);

        // Si le premier fichier est antérieur à la date du jour, on arrête la boucle
        if ($dateFichier < date('Ymd'))
          break;

        // Si le fichier date du jour alors on sait qu'on a déjà attribué l'expérience
        if ($dateFichier == date('Ymd'))
        {
          $chaineExecutee = true;
          break;
        }
      }
    }

    // Traitement des participants des missions si la chaîne n'a pas été exécutée
    if ($chaineExecutee == false)
    {
      // Récupération de la date de la veille
      $dateMoins1 = date('Ymd', strtotime('now - 1 Days'));

      // Récupération des missions se terminant la veille
      $listeMissions = physiqueFinsMissionsVeille($dateMoins1);

      // Traitement de chaque mission
      if (!empty($listeMissions))
      {
        foreach ($listeMissions as $mission)
        {
          // Initialisation du log de mission
          $log = array('titre'  => 'Expérience mission (' . htmlspecialchars($mission->getMission()) . ')',
                       'status' => 'KO',
                       'infos'  => ''
                      );

          // Récupération des participants de la mission et de leur avancement
          $listeParticipants = physiqueParticipantsMission($mission->getId());

          if (!empty($listeParticipants))
          {
            // Tri sur avancement
            foreach ($listeParticipants as $participant)
            {
              $triRank[] = $participant['avancement'];
            }

            array_multisort($triRank, SORT_DESC, $listeParticipants);

            // Réinitialisation du tri pour la prochaine occurence
            unset($triRank);

            // Affectation du rang
            $prevTotal   = 0;
            $currentRank = 0;

            foreach ($listeParticipants as $identifiant => &$participant)
            {
              $currentTotal = $participant['avancement'];

              if ($currentTotal != $prevTotal)
              {
                $currentRank += 1;
                $prevTotal    = $currentTotal;
              }

              // Suppression des rangs > 3 sinon on enregistre le rang
              if ($currentRank > 3)
                unset($listeParticipants[$identifiant]);
              else
                $participant['rank'] = $currentRank;
            }

            unset($participant);

            // Ajout de l'expérience pour chaque gagnant
            foreach ($listeParticipants as $identifiant => $participant)
            {
              switch ($participant['rank'])
              {
                case 1:
                  insertExperience($identifiant, 'winner_mission_1');
                  break;

                case 2:
                  insertExperience($identifiant, 'winner_mission_2');
                  break;

                case 3:
                  insertExperience($identifiant, 'winner_mission_3');
                  break;

                default:
                  break;
              }
            }

            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Expérience attribuée';
          }
          else
          {
            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Pas de participants';
          }

          // Ajout du compte-rendu à la liste des logs
          array_push($listeLogsMissions, $log);
        }
      }
      else
      {
        // Ajout des données au log
        $log = array('titre'  => 'Expérience missions',
                     'status' => 'OK',
                     'infos'  => 'Pas de missions'
                    );

        // Ajout du compte-rendu à la liste des logs
        array_push($listeLogsMissions, $log);
      }
    }
    else
    {
      // Ajout des données au log
      $log = array('titre'  => 'Expérience missions',
                   'status' => 'OK',
                   'infos'  => 'Chaîne déjà exécutée'
                  );

      // Ajout du compte-rendu à la liste des logs
      array_push($listeLogsMissions, $log);
    }

    // Retour
    return $listeLogsMissions;
  }

  // METIER : Recalcul des dépenses pour tous les utilisateurs
  // RETOUR : Compte-rendu traitement
  function reinitializeExpenses()
  {
    // Initialisations
    $log = array('titre'  => 'Remise à plat des bilans des dépenses',
                 'status' => 'KO',
                 'infos'  => ''
                );

    // Récupération de la liste des utilisateurs
    $listeUsers = physiqueUsers();

    // Traitement du bilan de chaque utilisateur
    foreach ($listeUsers as $user)
    {
      // Initialisations
      $bilanUser = 0;

      // Récupération des dépenses
      $listeDepenses = physiqueDepenses();

      // Récupération des parts des dépense
      foreach ($listeDepenses as $depense)
      {
        // Récupération du nombre de parts total, du nombre de parts de l'utilisateur et du nombre de participants
        $nombresParts = physiqueNombresParts($depense->getId(), $user->getIdentifiant());

        // Calcul du bilan par type de dépense
        if ($depense->getType() == 'M')
        {
          // Frais d'achat
          $fraisAchat = formatAmountForInsert($depense->getPrice());

          // Montant de la part
          $montantUser = formatAmountForInsert($nombresParts['nombre_parts_user']);

          // Calcul de la répartition des frais
          if (empty($fraisAchat))
            $fraisAchat = 0;

          if ($montantUser != 0)
            $fraisUser = $fraisAchat / $nombresParts['nombre_users'];
          else
            $fraisUser = 0;

          // Calcul du bilan de l'utilisateur (s'il participe ou qu'il est l'acheteur)
          if ($user->getIdentifiant() == $depense->getBuyer() OR $montantUser != 0)
          {
            if ($user->getIdentifiant() == $depense->getBuyer())
              $bilanUser += $fraisAchat + $nombresParts['nombre_parts_total'] - ($montantUser + $fraisUser);
            else
              $bilanUser -= $montantUser + $fraisUser;
          }
        }
        else
        {
          // Prix d'achat
          $prixAchat = formatAmountForInsert($depense->getPrice());

          // Prix par parts
          if ($nombresParts['nombre_parts_total'] != 0)
            $prixParPart = $prixAchat / $nombresParts['nombre_parts_total'];
          else
            $prixParPart = 0;

          // Somme des dépenses moins les parts consommées pour calculer le bilan
          if ($user->getIdentifiant() == $depense->getBuyer())
            $bilanUser += $prixAchat - ($prixParPart * $nombresParts['nombre_parts_user']);
          else
            $bilanUser -= $prixParPart * $nombresParts['nombre_parts_user'];
        }
      }

      // Modification de l'enregistrement en base
      physiqueUpdateBilanDepensesUser($user->getIdentifiant(), $bilanUser);
    }

    // Ajout des données au log
    $log['status'] = 'OK';
    $log['infos']  = 'Bilans recalculés';

    // Retour
    return $log;
  }

  // METIER : Envoi d'un mail de gestion à l'administrateur
  // RETOUR : Compte-rendu traitement
  function sendMailAdmin()
  {
    // Initialisations
    $log = array('titre'  => 'Envoi du mail d\'administration',
                 'status' => 'KO',
                 'infos'  => ''
                );

    // Récupération de l'email de l'administrateur
    $emailAdministrateur = physiqueMailAdmin();

    // Récupération des informations et envoi du mail
    if (!empty($emailAdministrateur))
    {
      // Récupération du nombre de requêtes des utilisateurs (changement de mot de passe)
      $nombreRequetesMotDePasse = physiqueRequetesUsers('Y');

      // Récupération du nombre de requêtes des utilisateurs (changement de mot de passe)
      $nombreRequetesInscription = physiqueRequetesUsers('I');

      // Récupération du nombre de requêtes des utilisateurs (changement de mot de passe)
      $nombreRequetesDesinscription = physiqueRequetesUsers('D');

      // Récupération du nombre de films à supprimer
      $nombreDemandesSuppressionsFilms = physiqueDemandesSuppressions('movie_house');

      // Récupération du nombre de calendriers à supprimer
      $nombreDemandesSuppressionsCalendriers = physiqueDemandesSuppressions('calendars');

      // Récupération du nombre d'annexes à supprimer
      $nombreDemandesSuppressionsAnnexes = physiqueDemandesSuppressions('calendars_annexes');

      // Nombre de bugs en cours
      $nombreBugsEnCours = physiqueNombreBugsEvolutions('B');

      // Nombre d'évolutions en cours
      $nombreEvolutionsEnCours = physiqueNombreBugsEvolutions('E');

      // Création d'un tableau des demandes
      $tableauDemandes = array('nombre_requetes_mot_de_passe'             => $nombreRequetesMotDePasse,
                               'nombre_requetes_inscription'              => $nombreRequetesInscription,
                               'nombre_requetes_desinscription'           => $nombreRequetesDesinscription,
                               'nombre_demandes_suppressions_films'       => $nombreDemandesSuppressionsFilms,
                               'nombre_demandes_suppressions_calendriers' => $nombreDemandesSuppressionsCalendriers,
                               'nombre_demandes_suppressions_annexes'     => $nombreDemandesSuppressionsAnnexes,
                               'nombre_bugs_en_cours'                     => $nombreBugsEnCours,
                               'nombre_evolutions_en_cours'               => $nombreEvolutionsEnCours
                              );

      // Connexion au serveur de mails et initialisations
      include_once('../includes/functions/appel_mail.php');
      require('../includes/libraries/php/phpmailer/class.phpmailer.php');
      require('../includes/libraries/php/phpmailer/class.smtp.php');

      // Destinataire du mail
      $mail->clearAddresses();
      $mail->AddAddress($emailAdministrateur, 'Administrateur Inside');

      // Objet du mail
      $mail->Subject = 'Inside - Gestion du site';

      // Contenu du mail
      $message = getModeleMailAdministration($tableauDemandes);
      $mail->MsgHTML($message);

      // Envoi du mail
      if (!$mail->Send())
      {
        // Ajout des données au log
        $log['status'] = 'KO';
        $log['infos']  = 'Erreur lors de l\'envoi du mail';
      }
      else
      {
        // Ajout des données au log
        $log['status'] = 'OK';
        $log['infos']  = 'Mail envoyé';
      }
    }
    else
    {
      $log['status'] = 'KO';
      $log['infos']  = 'Pas d\'adresse mail renseignée';
    }

    // Retour
    return $log;
  }

  // METIER : Création du fichier de log
  // RETOUR : Aucun
  function generateLog($typeLog, $traitements, $heureDebut, $heureFin)
  {
    // On vérifie la présence des dossiers, sinon on les créé
    $dossier                 = 'logs';
    $sousDossierQuotidien    = 'daily';
    $sousDossierHebdomadaire = 'weekly';

    if (!is_dir($dossier))
      mkdir($dossier);

    if (!is_dir($dossier . '/' . $sousDossierQuotidien))
      mkdir($dossier . '/' . $sousDossierQuotidien);

    if (!is_dir($dossier . '/' . $sousDossierHebdomadaire))
      mkdir($dossier . '/' . $sousDossierHebdomadaire);

    // Création de la ligne de titre
    if ($typeLog == 'j')
      $titreLog = "/******************************/\r\n/* Traitement CRON journalier */\r\n/******************************/\r\n";
    elseif ($typeLog == 'h')
      $titreLog = "/********************************/\r\n/* Traitement CRON hebdomadaire */\r\n/********************************/\r\n";

    // Création de la ligne de type de traitement
    if (isset($_POST['daily_cron']) OR isset($_POST['weekly_cron']))
      $executionLog = '## Traitement asynchrone';
    else
      $executionLog = '## Traitement automatique';

    // Création de la ligne de date du traitement
    $dateLog = '## Date.......................' . date('d/m/Y');

    // Création de la ligne d'état du traitement global
    $etatTraitementsKO = false;

    foreach ($traitements as $traitement)
    {
      if ($traitement['status'] == 'KO')
      {
        $etatTraitementsKO = true;
        break;
      }
    }

    if ($etatTraitementsKO == false)
      $etatLog = '## Etat traitements...........OK';
    else
      $etatLog = '## Etat traitements...........KO';

    // Création de la ligne de durée totale des traitements
    $dureeTotale = calculDureeTraitement($heureDebut, $heureFin);
    $dureeLog    = '## Durée traitements..........' . $dureeTotale['heures'] . ' heures, ' . $dureeTotale['minutes'] . ' minutes et ' . $dureeTotale['secondes'] . ' secondes';

    // Création et ouverture du fichier
    if ($typeLog == 'j')
      $log = fopen('logs/daily/' . $typeLog . 'log_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.txt', 'a+');
    elseif ($typeLog == 'h')
      $log = fopen('logs/weekly/' . $typeLog . 'log_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.txt', 'a+');

    // Repositionnement du curseur au début du fichier
    fseek($log, 0);

    // Ecriture du fichier
    fputs($log, $titreLog);
    fputs($log, "\r\n");
    fputs($log, $executionLog);
    fputs($log, "\r\n");
    fputs($log, $dateLog);
    fputs($log, "\r\n");
    fputs($log, $etatLog);
    fputs($log, "\r\n");
    fputs($log, $dureeLog);
    fputs($log, "\r\n");

    if (!empty($traitements))
    {
      foreach ($traitements as $traitement)
      {
        $titreTraitement  = '/* ' . $traitement['titre'] . ' */';
        $statutTraitement = '## Status.....................' . $traitement['status'];
        $infosTraitement  = '## Informations...............' . $traitement['infos'];

        fputs($log, "\r\n");
        fputs($log, $titreTraitement);
        fputs($log, "\r\n");
        fputs($log, $statutTraitement);
        fputs($log, "\r\n");
        fputs($log, $infosTraitement);
        fputs($log, "\r\n");
      }
    }

    // Fermeture du fichier
    fclose($log);
  }
?>
