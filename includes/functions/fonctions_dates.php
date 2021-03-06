<?php
  // DATE : Contrôle date valide (format JJ/MM/AAAA)
  // RETOUR : Booléen
  function validateDate($date)
  {
    // Initialisations
    $dateValide = false;

    // Vérification de la date
    if (strlen($date) == 10)
    {
      $jour  = substr($date, 0, 2);
      $mois  = substr($date, 3, 2);
      $annee = substr($date, 6, 4);

      if (is_numeric($jour) AND is_numeric($mois) AND is_numeric($annee))
      {
        if (checkdate($mois, $jour, $annee) == true)
          $dateValide = true;
      }
    }

    // Retour
    return $dateValide;
  }

  // DATE : Contrôle date valide (format AAAA-MM-JJ) sur mobile
  // RETOUR : Booléen
  function validateDateMobile($date)
  {
    // Initialisations
    $dateValide = false;

    // Vérification de la date
    if (strlen($date) == 10)
    {
      $jour  = substr($date, 8, 2);
      $mois  = substr($date, 5, 2);
      $annee = substr($date, 0, 4);

      if (is_numeric($jour) AND is_numeric($mois) AND is_numeric($annee))
      {
        if (checkdate($mois, $jour, $annee) == true)
          $dateValide = true;
      }
    }

    // Retour
    return $dateValide;
  }

  // DATE : Formate une date pour affichage (AAAAMMJJ -> JJ/MM/AAAA)
  // RETOUR : Date formatée
  function formatDateForDisplay($date)
  {
    // Formatage de la date
    if (strlen($date) == 8)
      $dateFormat = substr($date, 6, 2) . '/' . substr($date, 4, 2) . '/' . substr($date, 0, 4);
    else
      $dateFormat = $date;

    // Retour
    return $dateFormat;
  }

  // DATE : Formate une date pour affichage (AAAAMMJJ -> AAAA-MM-JJ) sur mobile
  // RETOUR : Date formatée
  function formatDateForDisplayMobile($date)
  {
    // Formatage de la date
    if (strlen($date) == 8)
      $dateFormat = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
    else
      $dateFormat = $date;

    // Retour
    return $dateFormat;
  }

  // DATE : Formate une date pour insertion en base (JJ/MM/AAAA -> AAAAMMJJ)
  // RETOUR : Date formaté
  function formatDateForInsert($date)
  {
    // Formatage de la date
    if (strlen($date) == 10)
      $dateFormat = substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    else
      $dateFormat = $date;

    // Retour
    return $dateFormat;
  }

  // DATE : Formate une date pour insertion en base (AAAA-MM-JJ -> AAAAMMJJ) sur mobile
  // RETOUR : Date formaté
  function formatDateForInsertMobile($date)
  {
    // Formatage de la date
    if (strlen($date) == 10)
      $dateFormat = substr($date, 0, 4) . substr($date, 5, 2) . substr($date, 8, 2);
    else
      $dateFormat = $date;

    // Retour
    return $dateFormat;
  }

  // DATE : Formate une heure pour affichage (HHMMSS -> HH:MM:SS)
  // RETOUR : Heure formatée
  function formatTimeForDisplay($time)
  {
    // Formatage de l'heure
    if (strlen($time) == 6)
      $timeFormat = substr($time, 0, 2) . ':' . substr($time, 2, 2) . ':' . substr($time, 4, 2);
    else
      $timeFormat = $time;

    // Retour
    return $timeFormat;
  }

  // DATE : Formate une heure pour affichage (HHMMSS -> HH:MM)
  // RETOUR : Heure formatée
  function formatTimeForDisplayLight($time)
  {
    // Formatage de l'heure
    if (strlen($time) == 6 OR strlen($time) == 4)
      $timeFormat = substr($time, 0, 2) . ':' . substr($time, 2, 2);
    else
      $timeFormat = $time;

    // Retour
    return $timeFormat;
  }

  // DATE : Formate une semaine pour affichage (suppression zéro initial)
  // RETOUR : Semaine formatée
  function formatWeekForDisplay($week)
  {
    // Formatage du numéro de semaine
    if (intval($week) < 10)
      $weekFormat = str_replace('0', '', $week);
    else
      $weekFormat = $week;

    // Retour
    return $weekFormat;
  }

  // DATE : Formate une semaine pour insertion en base (ajout zéro initial)
  // RETOUR : Semaine formatée
  function formatWeekForInsert($week)
  {
    // Formatage du numéro de semaine
    if (intval($week) < 10)
      $weekFormat = '0' . intval($week);
    else
      $weekFormat = $week;

    // Retour
    return $weekFormat;
  }

  // DATE : Formate un jour pour affichage (light)
  // RETOUR : Jour formaté
  function formatDayForDisplayLight($day)
  {
    // Formatage du jour
    $dayFormat = strtoupper(substr($day, 0, 3) . '.');

    // Retour
    return $dayFormat;
  }

  // DATE : Formate un mois pour affichage
  // RETOUR : Mois formaté
  function formatMonthForDisplay($month)
  {
    // Liste des mois
    $listeMois = array('01' => 'Janvier',
                       '02' => 'Février',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Août',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Décembre'
                      );

    // Récupération du mois
    $monthFormat = $listeMois[$month];

    // Retour
    return $monthFormat;
  }

  // DATE : Formate un mois pour affichage (light)
  // RETOUR : Mois formaté
  function formatMonthForDisplayLight($month)
  {
    // Liste des mois
    $listeMois = array('01' => 'JAN',
                       '02' => 'FÉV',
                       '03' => 'MAR',
                       '04' => 'AVR',
                       '05' => 'MAI',
                       '06' => 'JUIN',
                       '07' => 'JUIL',
                       '08' => 'AOÛT',
                       '09' => 'SEP',
                       '10' => 'OCT',
                       '11' => 'NOV',
                       '12' => 'DÉC'
                      );

    // Récupération du mois
    $monthFormat = $listeMois[$month];

    // Retour
    return $monthFormat;
  }

  // DATE : Formate un mois pour affichage (strong)
  // RETOUR : Mois formaté
  function formatMonthForDisplayStrong($month)
  {
    // Liste des mois
    $listeMois = array('01' => 'JANVIER',
                       '02' => 'FÉVRIER',
                       '03' => 'MARS',
                       '04' => 'AVRIL',
                       '05' => 'MAI',
                       '06' => 'JUIN',
                       '07' => 'JUILLET',
                       '08' => 'AOÛT',
                       '09' => 'SEPTEMBRE',
                       '10' => 'OCTOBRE',
                       '11' => 'NOVEMBRE',
                       '12' => 'DÉCEMBRE'
                      );

    // Récupération du mois
    $monthFormat = $listeMois[$month];

    // Retour
    return $monthFormat;
  }

  // DATE : Calcule la durée en heures/minutes/secondes pour un traitement
  // RETOUR : Tableau de durée
  function calculDureeTraitement($heureDeb, $heureFin)
  {
    // Initialisations
    $dureeFormat = array('heures'   => 0,
                         'minutes'  => 0,
                         'secondes' => 0
                        );

    if (strlen($heureDeb) == 6 AND strlen($heureFin) == 6)
    {
      // Calcul des durées (en secondes)
      $heureFinSecondes = substr($heureFin, 0, 2) * 60 * 60 + substr($heureFin, 2, 2) * 60 + substr($heureFin, 4, 2);
      $heureDebSecondes = substr($heureDeb, 0, 2) * 60 * 60 + substr($heureDeb, 2, 2) * 60 + substr($heureDeb, 4, 2);
      $dureeSecondes    = $heureFinSecondes - $heureDebSecondes;

      // Conversion
      $total    = $dureeSecondes;
      $heures   = intval(abs($total / 3600));
      $total    = $total - ($heures * 3600);
      $minutes  = intval(abs($total / 60));
      $total    = $total - ($minutes * 60);
      $secondes = $total;

      // Construction du tableau
      $dureeFormat = array('heures'   => $heures,
                           'minutes'  => $minutes,
                           'secondes' => $secondes
                          );
    }

    // Retour
    return $dureeFormat;
  }

  // DATE : Calcule la durée en jours entre 2 dates pour une mission
  // RETOUR : Ecart
  function ecartDatesMission($dateDeb, $dateFin)
  {
    // Calcul de l'écart
    $date1 = strtotime($dateDeb);
    $date2 = strtotime($dateFin);

    $calcul = abs($date2 - $date1);

    // Formatage
    $ecart = ($calcul / (60 * 60 * 24)) + 1;

    // Retour
    return $ecart;
  }

  // DATE : Détermine si un jour est férié (AAAAMMJJ)
  // RETOUR : Jour férié
  function isJourFerie($date)
  {
    // Initialisations
    $jourFerie = '';

    // Récupération des données
    $annee = substr($date, 0, 4);

    // Calcul des jours fériés variables
    $dimanchePaques    = date('Ymd', easter_date($annee));
    $lundiPaques       = date('Ymd', strtotime($dimanchePaques . ' + 1 days'));
    $ascension         = date('Ymd', strtotime($dimanchePaques . ' + 39 days'));
    $dimanchePentecote = date('Ymd', strtotime($dimanchePaques . ' + 49 days'));
    $lundiPentecote    = date('Ymd', strtotime($dimanchePaques . ' + 50 days'));

    // Liste des jours fériés
    $joursFeries = array($annee . '0101'    => array('reference' => 'nouvel_an',
                                                     'nom_jour'  => 'Jour de l\'an'),
                         $annee . '0402'    => array('reference' => 'vendredi_saint',
                                                     'nom_jour'  => 'Vendredi Saint'),
                         $dimanchePaques    => array('reference' => 'dimanche_paques',
                                                     'nom_jour'  => 'Dimanche de Pâques'),
                         $lundiPaques       => array('reference' => 'lundi_paques',
                                                     'nom_jour'  => 'Lundi de Pâques'),
                         $annee . '0501'    => array('reference' => 'fete_travail',
                                                     'nom_jour'  => 'Fête du travail'),
                         $annee . '0508'    => array('reference' => 'victoire_1945',
                                                     'nom_jour'  => 'Victoire 1945'),
                         $ascension         => array('reference' => 'ascension',
                                                     'nom_jour'  => 'Ascension'),
                         $dimanchePentecote => array('reference' => 'dimanche_pentecote',
                                                     'nom_jour'  => 'Dimanche de Pentecôte'),
                         $lundiPentecote    => array('reference' => 'lundi_pentecote',
                                                     'nom_jour'  => 'Lundi de Pentecôte'),
                         $annee . '0714'    => array('reference' => 'fete_nationale',
                                                     'nom_jour'  => 'Fête Nationale'),
                         $annee . '0815'    => array('reference' => 'assomption',
                                                     'nom_jour'  => 'Assom-ption'),
                         $annee . '1101'    => array('reference' => 'toussaint',
                                                     'nom_jour'  => 'Toussaint'),
                         $annee . '1111'    => array('reference' => 'armistice_1918',
                                                     'nom_jour'  => 'Armistice 1918'),
                         $annee . '1225'    => array('reference' => 'noel',
                                                     'nom_jour'  => 'Noël'),
                         $annee . '1226'    => array('reference' => 'saint_etienne',
                                                     'nom_jour'  => 'Saint-Etienne')
                        );

    // Détermination si date fériée
    if (isset($joursFeries[$date]))
      $jourFerie = $joursFeries[$date];

    // Retour
    return $jourFerie;
  }

  // DATE : Détermine si un jour est en vacances (AAAAMMJJ)
  // RETOUR : Jour vacances
  function isVacances($date, $vacances, $zone)
  {
    // Initialisations
    $jourVacances = false;

    // Recherche si jour faisant partie de vacances
    if (isset($vacances[$date]) AND $vacances[$date][$zone] == 'true')
      $jourVacances = true;

    // Retour
    return $jourVacances;
  }
?>
