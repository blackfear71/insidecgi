<?php
  // CONTROLE : Référence thème unique
  // RETOUR : Booléen
  function controleReferenceUnique($reference)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $isUnique = physiqueReferenceUnique($reference);

    if ($isUnique == false)
    {
      $_SESSION['alerts']['already_ref_theme'] = true;
      $control_ok                              = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Format de date
  // RETOUR : Booléen
  function controleFormatDate($date, $format)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (validateDate($date, $format) != true)
    {
      $_SESSION['alerts']['wrong_date'] = true;
      $control_ok                       = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Date 1 < Date 2
  // RETOUR : Booléen
  function controleOrdreDates($date1, $date2)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($date1 > $date2)
    {
      $_SESSION['alerts']['date_less'] = true;
      $control_ok                      = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Dates thème non superposées
  // RETOUR : Booléen
  function controleSuperpositionDates($dateDeb, $dateFin, $idTheme)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $isOver = physiqueSuperpositionDates($dateDeb, $dateFin, $idTheme);

    if ($isOver == true)
    {
      $_SESSION['alerts']['date_conflict'] = true;
      $control_ok                          = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Niveau numérique et positif
  // RETOUR : Booléen
  function controleNiveauNumerique($niveau)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!is_numeric($niveau) OR $niveau <= 0)
    {
      $_SESSION['alerts']['level_theme_numeric'] = true;
      $control_ok                                = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Fichier autres que logo
  // RETOUR : Booléen
  function controleAutresFichiers($typeFichier, $nomFichier)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($typeFichier != 'logo' AND (empty($nomFichier) OR $nomFichier == NULL))
    {
      $_SESSION['alerts']['missing_theme_file'] = true;
      $control_ok                               = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Contrôle d'un fichier après contrôles communs
  // RETOUR : Booléen
  function controleFichier($fileDatas)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($fileDatas['control_ok'] == false)
      $control_ok = false;

    // Retour
    return $control_ok;
  }
?>