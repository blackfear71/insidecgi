<?php
  include_once('appel_bdd.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/profile.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/themes.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/missions.php');

  // Contrôles Index, initialisation session
  // RETOUR : Aucun
  function controlsIndex()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

  	// Si déjà connecté
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != "admin")
      header('location: /inside/portail/portail/portail.php?action=goConsulter');
    elseif (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] == "admin")
      header('location: /inside/administration/portail/portail.php?action=goConsulter');
    else
      $_SESSION['index']['connected'] = false;

    // Mobile
    if (!isset($_SESSION['index']['mobile']))
      $_SESSION['index']['mobile'] = isMobile();
  }

  // Contrôles Administrateur, initialisation session
  // RETOUR : Aucun
  function controlsAdmin()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non utilisateur normal
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != "admin")
      header('location: /inside/portail/portail/portail.php?action=goConsulter');

    // Contrôle administrateur connecté
    if ($_SESSION['index']['connected'] == false)
      header('location: /inside/index.php');

    // Mobile
    $_SESSION['index']['mobile'] = false;
  }

  // Contrôles Utilisateur, initialisation session, mission et thème
  // RETOUR : Aucun
  function controlsUser()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non administrateur
  	if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] == "admin")
      header('location: /inside/administration/portail/portail.php?action=goConsulter');

    // Contrôle utilisateur connecté
  	if ($_SESSION['index']['connected'] == false)
      header('location: /inside/index.php');
    else
    {
      // Contrôle page accessible mobile
      $isAccessible = isAccessible($_SERVER['PHP_SELF']);

      // Redirection si non accessible
      if ($isAccessible == false)
        header('location: /inside/portail/portail/portail.php?action=goConsulter');
      else
      {
        // Récupération expérience
        getExperience($_SESSION['user']['identifiant']);

        // Initialisation génération mission
        if (!isset($_SESSION['missions']))
          $_SESSION['missions'] = array();

        // Récupération des missions à générer
        $missions = getMissionsToGenerate();

        // On génère les boutons de mission si besoin pour chaque mission
        foreach ($missions as $key => $mission)
        {
          if (empty($_SESSION['missions'][$key]))
          {
            if (!empty($mission) AND date("His") >= $mission->getHeure())
            {
              // Nombre de boutons à générer pour la mission en cours
              $nbButtonsToGenerate = controlMissionComplete($_SESSION['user']['identifiant'], $mission);

              if ($nbButtonsToGenerate > 0)
              {
                $missionGenerated = generateMissions($nbButtonsToGenerate, $mission, $key);
                $_SESSION['missions'][$key] = $missionGenerated;
              }
            }
          }
          else
          {
            if (date('His') < $mission->getHeure())
              unset($_SESSION['missions'][$key]);
            else
            {
              // Nombre de boutons à générer pour la mission en cours
              $nbButtonsToGenerate = controlMissionComplete($_SESSION['user']['identifiant'], $mission);

              if ($nbButtonsToGenerate != count($_SESSION['missions'][$key]))
              {
                $missionGenerated = generateMissions($nbButtonsToGenerate, $mission, $key);
                $_SESSION['missions'][$key] = $missionGenerated;
              }
            }
          }
        }

        //var_dump($_SESSION['missions']);

        // Détermination thème
        $_SESSION['theme'] = setTheme();

        //var_dump($_SESSION['theme']);
      }
    }
  }

  // Contrôle si on est sur mobile
  // RETOUR : Booléen
  function isMobile()
  {
    // Initialisations
    $isMobile  = false;
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Contrôle
    if (preg_match('/iphone/i', $userAgent)
    ||  preg_match('/android/i', $userAgent)
    ||  preg_match('/blackberry/i', $userAgent)
    ||  preg_match('/symb/i', $userAgent)
    ||  preg_match('/ipad/i', $userAgent)
    ||  preg_match('/ipod/i', $userAgent)
    ||  preg_match('/phone/i', $userAgent))
      $isMobile = true;

    // Retour
    return $isMobile;
  }

  // Contrôle si la page courante est accessible sur mobile
  // RETOUR : Booléen
  function isAccessible($path)
  {
    // Initialisations
    $isAccessible = true;

    // Contrôle
    if ($_SESSION['index']['mobile'] == true)
    {
      if ($path != '/inside/portail/portail/portail.php'
      AND $path != '/inside/portail/foodadvisor/foodadvisor.php')
        $isAccessible = false;
    }

    // Retour
    return $isAccessible;
  }

  // Récupération des alertes à afficher
  // RETOUR : Liste des alertes
  function getAlertesInside()
  {
    // Initialisations
    $messages = array();

    // Récupération à partir de la session
    if (isset($_SESSION['alerts'])AND !empty($_SESSION['alerts']))
    {
      // Initialisation variables d'alerte
      foreach ($_SESSION['alerts'] as $key_alert => $alert)
      {
        if ($alert != true)
          unset($_SESSION['alerts'][$key_alert]);
      }

      // Boucle de lecture des messages d'alerte
      foreach ($_SESSION['alerts'] as $key_alert => $alert)
      {
        if (isset($alert) AND $alert == true)
        {
          global $bdd;
          
          $reponse = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $key_alert . '"');
          $donnees = $reponse->fetch();

          // On ajoute la ligne au tableau (logo + message)
          if ($reponse->rowCount() > 0)
            $ligneMessage = array('logo' => $donnees['type'], 'texte' => $donnees['message']);
          else
            $ligneMessage = array('logo' => 'question', 'texte' => 'Message d\'alerte non défini pour : ' . $key_alert);

          array_push($messages, $ligneMessage);

          $reponse->closeCursor();

          // Réinitialisation de l'erreur
          unset($_SESSION['alerts'][$key_alert]);
        }
      }
    }

    // Retour
    return $messages;
  }

  // Récupération expérience utilisateur
  // RETOUR : Tableau d'expérience
  function getExperience($identifiant)
  {
    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, experience FROM users WHERE identifiant = "' . $identifiant . '"');
    $donnees = $reponse->fetch();

    $experience = $donnees['experience'];
    $niveau     = convertExperience($experience);
    $exp_min    = 10 * $niveau ** 2;
    $exp_max    = 10 * ($niveau + 1) ** 2;
    $exp_lvl    = $exp_max - $exp_min;
    $progress   = $experience - $exp_min;
    $percent    = floor($progress * 100 / $exp_lvl);

    $_SESSION['user']['experience'] = array('niveau'   => $niveau,
                                            'exp_min'  => $exp_min,
                                            'exp_max'  => $exp_max,
                                            'exp_lvl'  => $exp_lvl,
                                            'progress' => $progress,
                                            'percent'  => $percent
                                           );

    $reponse->closeCursor();
  }

  // Récupération des missions actives
  // RETOUR : Objets mission
  function getMissionsToGenerate()
  {
    $missions  = array();
    $date_jour = date('Ymd');

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE ' . $date_jour . ' >= date_deb AND ' . $date_jour . ' <= date_fin ORDER BY date_deb ASC');
    while ($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);
      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    return $missions;
  }

  // Contrôle mission déjà complétée
  // RETOUR : Nombre de missions à générer
  function controlMissionComplete($user, $mission)
  {
    $missionToGenerate = 0;
    $date_jour         = date('Ymd');

    global $bdd;

    // Objectif mission
    $reponse1 = $bdd->query('SELECT * FROM missions WHERE id = ' . $mission->getId());
    $donnees1 = $reponse1->fetch();

    $objectifMission = $donnees1['objectif'];

    $reponse1->closeCursor();

    // Objectif atteint par l'utilisateur dans la journée
    $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission->getId() . ' AND identifiant = "' . $user . '" AND date_mission = ' . $date_jour);
    $donnees2 = $reponse2->fetch();

    $avancementUser = $donnees2['avancement'];

    $reponse2->closeCursor();

    if ($avancementUser < $objectifMission)
      $missionToGenerate = $objectifMission - $avancementUser;

    return $missionToGenerate;
  }

  // Génération contexte mission (boutons)
  // RETOUR : Tableau contexte
  function generateMissions($nb, $mission, $key)
  {
    $missionButtons            = array();

    $listPages                 = array('/inside/portail/bugs/bugs.php',
                                       '/inside/portail/calendars/calendars.php',
                                       '/inside/portail/changelog/changelog.php',
                                       '/inside/portail/collector/collector.php',
                                       '/inside/portail/cookingbox/cookingbox.php',
                                       //'/inside/portail/eventmanager/eventmanager.php',
                                       '/inside/portail/expensecenter/expensecenter.php',
                                       '/inside/portail/foodadvisor/foodadvisor.php',
                                       '/inside/portail/foodadvisor/restaurants.php',
                                       '/inside/portail/ideas/ideas.php',
                                       '/inside/portail/missions/missions.php',
                                       '/inside/portail/missions/details.php',
                                       '/inside/portail/moviehouse/details.php',
                                       '/inside/portail/moviehouse/mailing.php',
                                       '/inside/portail/moviehouse/moviehouse.php',
                                       '/inside/portail/notifications/notifications.php',
                                       '/inside/portail/petitspedestres/parcours.php',
                                       '/inside/portail/portail/portail.php',
                                       '/inside/portail/profil/profil.php',
                                       '/inside/portail/search/search.php'
                                      );
    $listZonesCompletes        = array('header',
                                       'footer',
                                       'article'
                                      );
    $listPositionsHorizontales = array('left',
                                       'right',
                                       'middle',
                                      );
    $listPositionsVerticales   = array('top',
                                       'bottom',
                                       'middle'
                                      );
    $listPositionsArticle      = array('top_left',
                                       'top_right',
                                       'middle_left',
                                       'middle_right',
                                       'bottom_left',
                                       'bottom_right',
                                      );

    for ($i = 0; $i < $nb; $i++)
    {
      $myMissionButtons = array();

      // Id mission
      $id_mission = $mission->getId();

      // Référence mission
      $reference = $mission->getReference();

      // Référence mission remplie
      $ref_mission = $i;

      // Page
      $page = $listPages[array_rand($listPages)];

      // Zone
      $zone = $listZonesCompletes[array_rand($listZonesCompletes)];

      // Positions
      switch ($zone)
      {
        case 'article':
          $position = $listPositionsArticle[array_rand($listPositionsArticle)];
          break;

        case 'header':
        case 'nav':
        case 'footer':
          $position = $listPositionsHorizontales[array_rand($listPositionsHorizontales)];
          break;

        default:
          $position = '';
          break;
      }

      // Icônes
      switch ($position)
      {
        case 'left':
          $icone = $mission->getReference() . '_g';
          break;

        case 'middle':
          $icone = $mission->getReference() . '_m';
          break;

        case 'right':
          $icone = $mission->getReference() . '_d';
          break;

        case 'top_left':
        case 'middle_left':
        case 'bottom_left':
          $icone = $mission->getReference() . '_g';
          break;

        case 'top_right':
        case 'middle_right':
        case 'bottom_right':
          $icone = $mission->getReference() . '_d';
          break;

        default:
          $icone = '';
          break;
      }

      // Classe position
      if (!empty($zone) AND !empty($position))
      {
        // Cas des pages sans onglets
        if  ($zone == 'article'
        AND ($position == 'top_left'
        OR   $position == 'top_right')
        AND ($page == '/inside/portail/bugs/bugs.php'
        OR   $page == '/inside/portail/changelog/changelog.php'
        OR   $page == '/inside/portail/ideas/ideas.php'
        OR   $page == '/inside/portail/notifications/notifications.php'
        OR   $page == '/inside/portail/portail/portail.php'
        OR   $page == '/inside/portail/profil/profil.php'
        OR   $page == '/inside/portail/search/search.php'))
          $classe = $zone . '_' . $position . '_mission_no_nav';
        else
          $classe = $zone . '_' . $position . '_mission';
      }
      else
        $classe = '';

      $myMissionButtons = array('id_mission'  => $id_mission,
                                'reference'   => $reference,
                                'ref_mission' => $ref_mission,
                                'key_mission' => $key,
                                'page'        => $page,
                                'zone'        => $zone,
                                'position'    => $position,
                                'icon'        => $icone,
                                'class'       => $classe
                               );

      $duplicate = controlGeneratedMission($missionButtons, $myMissionButtons);

      // Si mission non dupliquée alors on l'insère dans le tableau, sinon on revient une occurence en arrière pour la regénérer
      if ($duplicate == false)
        array_push($missionButtons, $myMissionButtons);
      else
        $i--;
    }

    return $missionButtons;
  }

  // Contrôle missions en double
  // RETOUR : booléen
  function controlGeneratedMission($tableauMissions, $mission)
  {
    $duplicated = false;

    // Modifier le compteur si de nouvelles pages sont rajoutées (actuellement 17*(3+3+6) = 204 emplacements possibles)
    if (!empty($tableauMissions) AND count($tableauMissions) <= 204)
    {
      foreach ($tableauMissions as $missionExistante)
      {
        if ($mission['id_mission'] == $missionExistante['id_mission']
        AND $mission['page']       == $missionExistante['page']
        AND $mission['zone']       == $missionExistante['zone']
        AND $mission['position']   == $missionExistante['position'])
        {
          $duplicated = true;
          break;
        }
      }
    }

    return $duplicated;
  }

  // Détermination du thème
  // RETOUR : Tableau chemins & types de thème
  function setTheme()
  {
    $theme = array();

    global $bdd;

    // Contrôle thème mission en cours
    $theme_present = false;

    $req1 = $bdd->query('SELECT * FROM themes WHERE type = "M" AND ' . date("Ymd") . ' >= date_deb AND ' . date("Ymd") . ' <= date_fin');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $theme_present = true;
      $myTheme       = Theme::withData($data1);
    }

    $req1->closeCursor();

    // Thème mission si en cours
    if ($theme_present == true)
    {
      if ($myTheme->getLogo() == "Y")
      {
        $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                       'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                       'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                       'logo'       => '/inside/includes/images/themes/logos/' . $myTheme->getReference() . '_l.png'
                      );
      }
      else
      {
        $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                       'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                       'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                       'logo'       => NULL
                      );
      }
    }
    // Thème personnalisé
    else
    {
      // Lecture préférence thème utilisateur
      $req2 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['user']['identifiant'] . '"');
      $data2 = $req2->fetch();
      $preferences = Preferences::withData($data2);
      $req2->closeCursor();

      if (!empty($preferences->getRef_theme()))
      {
        $req3 = $bdd->query('SELECT * FROM themes WHERE reference = "' . $preferences->getRef_theme() . '"');
        $data3 = $req3->fetch();

        if ($req3->rowCount() > 0)
        {
          $myTheme = Theme::withData($data3);

          if ($myTheme->getLogo() == "Y")
          {
            $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                           'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                           'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                           'logo'       => '/inside/includes/images/themes/logos/' . $myTheme->getReference() . '_l.png'
                          );
          }
          else
          {
            $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                           'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                           'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                           'logo'       => NULL
                          );
          }
        }

        $req3->closeCursor();
      }
    }

    return $theme;
  }

  // Formatage titres niveaux (succès)
  // RETOUR : titre niveau formaté
  function formatTitleLvl($lvl)
  {
    $name_lvl = "";

    switch ($lvl)
    {
      case "1";
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div><div class="texte_titre_section">Seuls les plus forts y parviendront.</div></div></div>';
        break;

      case "2";
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div><div class="texte_titre_section">Vous êtes encore là ?</div></div>';
        break;

      case "3";
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div><div class="texte_titre_section">Votre charisme doit être impressionnant.</div></div>';
        break;

      default:
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div></div>';
        break;
    }

    return $name_lvl;
  }

  // Formatage gagnants mission
  // Retour : phrase formatée
  function formatGagnants($listWinners)
  {
    switch (count($listWinners))
    {
      case 1:
        $phrase = 'Félicitations à <span class="contenu_gras">' . htmlspecialchars($listWinners[0]) . '</span> pour sa victoire écrasante !';
        break;

      case 0:
        $phrase = '';
        break;

      default:
        $phrase = 'Félicitations à ';

        foreach ($listWinners as $winner)
        {
          if ($winner == end($listWinners))
          {
            $phrase = substr($phrase, 0, -2);
            $phrase .= ' et <span class="contenu_gras">' . htmlspecialchars($winner) . '</span>';
          }
          else
            $phrase .= '<span class="contenu_gras">' . htmlspecialchars($winner) . '</span>, ';
        }

        $phrase .= ' pour leur magnifique victoire !';
        break;
    }

    return $phrase;
  }

  // Génération notification
  // RETOUR : Aucun
  function insertNotification($author, $category, $content)
  {
    $date = date("Ymd");
    $time = date("His");

    global $bdd;

    // Stockage de l'enregistrement en table
    $req = $bdd->prepare('INSERT INTO notifications(author, date, time, category, content) VALUES(:author, :date, :time, :category, :content)');
    $req->execute(array(
      'author'   => $author,
      'date'     => $date,
      'time'     => $time,
      'category' => $category,
      'content'  => $content
        ));
    $req->closeCursor();
  }

  // Suppression notification
  // RETOUR : Aucun
  function deleteNotification($category, $content)
  {
    global $bdd;

    // Suppression de la table
    $req = $bdd->exec('DELETE FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '"');
  }

  // Contrôle notification existante
  // RETOUR : Booléen
  function controlNotification($category, $content)
  {
    $exist = false;

    global $bdd;

    if ($category == 'comments')
      $req = $bdd->query('SELECT * FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '" AND date = ' . date(Ymd));
    else
      $req = $bdd->query('SELECT * FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '"');
    $data = $req->fetch();

    if ($req->rowCount() > 0)
      $exist = true;

    $req->closeCursor();

    return $exist;
  }

  // Formatage phrases cultes
  // Retour : phrase formatée
  function formatCollector($collector)
  {
    $formatted = "";

    $search    = array("[", "]");
    $replace   = array('<strong class="strong_red">', '</strong>');
    $formatted = str_replace($search, $replace, $collector);

    return $formatted;
  }

  // Dé-formatage phrases cultes
  // Retour : phrase dé-formatée
  function unformatCollector($collector)
  {
    $unformatted = "";

    $search      = array("[", "]");
    $replace     = array("", "");
    $unformatted = str_replace($search, $replace, $collector);

    return $unformatted;
  }

  // Suppression des caractères ASCII invisibles (?)
  // Retour : phrase nettoyée
  function deleteInvisible($phrase)
  {
    $cleaned = preg_replace('[\xE2\x80\x8E]', '', $phrase);

    return $cleaned;
  }

  // Lecture liste des utilisateurs (chat)
  // Retour : Tableau d'utilisateurs
  function getUsersChat()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // Rotation automatique des images en mode Portrait
  // RETOUR : Aucun
  function rotateImage($image, $type)
  {
    $degrees = 0;

    // Récupération des données EXIF
    $exif = exif_read_data($image);

    // Rotation
    if (!empty($exif['Orientation']))
    {
      switch ($exif['Orientation'])
      {
        case 3:
          $degrees = 180;
          break;

        case 6:
          $degrees = -90;
          break;

        case 8:
          $degrees = 90;
          break;

        case 1:
        default:
          $degrees = 0;
          break;
      }
    }

    if ($degrees != 0)
    {
      switch ($type)
      {
        case 'jpeg':
        case 'jpg':
          $source = imagecreatefromjpeg($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagejpeg($rotate, $image);
          break;

        case 'png':
          $source = imagecreatefrompng($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagepng($rotate, $image);
          break;

        case 'gif':
          $source = imagecreatefromgif($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagegif($rotate, $image);
          break;

        case 'bmp':
          $source = imagecreatefrombmp($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagebmp($rotate, $image);
          break;

        default:
          break;
      }
    }
  }

  // Génération valeur succès niveau
  // RETOUR : Aucun
  function insertOrUpdateSuccesLevel($experience, $identifiant)
  {
    // Récupération du niveau
    $level = convertExperience($experience);

    // Insertion des valeurs des succès
    insertOrUpdateSuccesValue('level_1', $identifiant, $level);
    insertOrUpdateSuccesValue('level_5', $identifiant, $level);
    insertOrUpdateSuccesValue('level_10', $identifiant, $level);
  }

  // Génération valeur succès mission
  // RETOUR : Aucun
  function insertOrUpdateSuccesMission($reference, $identifiant)
  {
    switch ($reference)
    {
      case 'noel_2017':
        insertOrUpdateSuccesValue('christmas2017', $identifiant, 1);
        insertOrUpdateSuccesValue('christmas2017_2', $identifiant, 1);
        break;

      case 'paques_2018':
        insertOrUpdateSuccesValue('golden-egg', $identifiant, 1);
        insertOrUpdateSuccesValue('rainbow-egg', $identifiant, 1);
        break;

      case 'halloween_2018':
        insertOrUpdateSuccesValue('wizard', $identifiant, 1);
        break;

      case 'noel_2018':
        insertOrUpdateSuccesValue('christmas2018', $identifiant, 1);
        insertOrUpdateSuccesValue('christmas2018_2', $identifiant, 1);
        break;

      case 'noel_2019':
        insertOrUpdateSuccesValue('christmas2019', $identifiant, 1);
        break;

      default:
        break;
    }
  }

  // Génération valeur succès
  // RETOUR : Aucun
  function insertOrUpdateSuccesValue($reference, $identifiant, $incoming)
  {
    $value  = NULL;
    $action = NULL;

    global $bdd;

    // Détermination valeur à insérer
    switch ($reference)
    {
      // Valeur saisie conservée
      case "beginning":
      case "developper":
      case "padawan":
      case "level_1":
      case "level_5":
      case "level_10":
        $value = $incoming;

        // Vérification succès débloqué
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != "admin")
        {
          $already_unlocked = false;

          // Récupération des données du succès
          $req0 = $bdd->query('SELECT * FROM success WHERE reference = "' . $reference . '"');
          $data0 = $req0->fetch();
          $limit = $data0['limit_success'];
          $req0->closeCursor();

          // Comparaison avec l'ancienne valeur
          $req1 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
          $data1 = $req1->fetch();

          if ($req1->rowCount() > 0)
          {
            if ($data1['value'] >= $limit)
              $already_unlocked = true;
          }

          $req1->closeCursor();

          // Test si succès débloqué
          if ($already_unlocked == false AND $value == $limit)
            $_SESSION['success'][$reference] = true;
        }
        break;

      // Incrémentation de la valeur précédente avec "incoming" (incoming <= 1)
      case "publisher":
      case "viewer":
      case "commentator":
      case "listener":
      case "speaker":
      case "funny":
      case "self-satisfied":
      case "buyer":
      case "generous":
      case "creator":
      case "applier":
      case "debugger":
      case "compiler":
      case "restaurant-finder":
      case "star-chief":
      case "cooker":
      case "recipe-master":
      case "christmas2017":
      case "christmas2017_2":
      case "golden-egg":
      case "rainbow-egg":
      case "wizard":
      case "christmas2018":
      case "christmas2018_2":
      case "christmas2019":
        // Récupération de l'ancienne valeur si besoin
        $req0 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data0 = $req0->fetch();

        if ($req0->rowCount() > 0)
          $value = $data0['value'] + $incoming;
        else
          $value = $incoming;

        $req0->closeCursor();

        // Vérification succès débloqué
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != "admin")
        {
          // Récupération des données du succès
          $req1 = $bdd->query('SELECT * FROM success WHERE reference = "' . $reference . '"');
          $data1 = $req1->fetch();
          $limit = $data1['limit_success'];
          $req1->closeCursor();

          // Test si succès débloqué
          if ($value == $limit)
            $_SESSION['success'][$reference] = true;
        }
        break;

      // Incrémentation de la valeur précédente avec "incoming" (incoming > 1)
      case "eater":
        // Récupération de l'ancienne valeur si besoin
        $req0 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data0 = $req0->fetch();

        if ($req0->rowCount() > 0)
          $value = $data0['value'] + $incoming;
        else
          $value = $incoming;

        $req0->closeCursor();

        // Vérification succès débloqué
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != "admin")
        {
          $already_unlocked = false;

          // Récupération des données du succès
          $req1 = $bdd->query('SELECT * FROM success WHERE reference = "' . $reference . '"');
          $data1 = $req1->fetch();
          $limit = $data1['limit_success'];
          $req1->closeCursor();

          // Comparaison avec l'ancienne valeur
          $req2 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
          $data2 = $req2->fetch();

          if ($req2->rowCount() > 0)
          {
            if ($data2['value'] >= $limit)
              $already_unlocked = true;
          }

          $req2->closeCursor();

          // Test si succès débloqué
          if ($already_unlocked == false AND $value >= $limit)
            $_SESSION['success'][$reference] = true;
        }
        break;

      // Valeur maximale conservée
      case "greedy":
        // Récupération des données du succès
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != "admin")
        {
          $req0 = $bdd->query('SELECT * FROM success WHERE reference = "' . $reference . '"');
          $data0 = $req0->fetch();
          $limit = $data0['limit_success'];
          $req0->closeCursor();
        }

        // Comparaison avec l'ancienne valeur
        $req1 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data1 = $req1->fetch();

        if ($req1->rowCount() > 0)
        {
          // Récupération nouvelle valeur
          if ($incoming > $data1['value'])
            $value = $incoming;

          // Vérification succès débloqué
          if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != "admin")
          {
            if ($data1['value'] < $limit AND $value >= $limit)
              $_SESSION['success'][$reference] = true;
          }
        }
        else
        {
          // Récupération nouvelle valeur
          $value = $incoming;

          // Vérification succès débloqué
          if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != "admin")
          {
            if ($value >= $limit)
              $_SESSION['success'][$reference] = true;
          }
        }

        $req1->closeCursor();
        break;

      default:
        $value = NULL;
        break;
    }

    /****************************************/
    /*** Détermination action à effectuer ***/
    /****************************************/
    if (!is_null($value))
    {
      if ($value == 0)
        $action = 'delete';
      else
      {
        $req3 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data3 = $req3->fetch();

        if ($req3->rowCount() > 0)
          $action = 'update';
        else
          $action = 'insert';

        $req3->closeCursor();
      }
    }

    /***************************************************************/
    /*** Insertion / modification / suppression de chaque succès ***/
    /***************************************************************/
    switch ($action)
    {
      case 'insert':
        $req4 = $bdd->prepare('INSERT INTO success_users(reference, identifiant, value) VALUES(:reference, :identifiant, :value)');
        $req4->execute(array(
          'reference'   => $reference,
          'identifiant' => $identifiant,
          'value'       => $value
          ));
        $req4->closeCursor();
        break;

      case 'update':
        $req4 = $bdd->prepare('UPDATE success_users SET value = :value WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $req4->execute(array(
          'value' => $value
        ));
        $req4->closeCursor();
        break;

      case 'delete':
        $req4 = $bdd->exec('DELETE FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

      default:
        break;
    }
  }

  // Mise à jour expérience
  // RETOUR : Aucun
  function insertExperience($identifiant, $action)
  {
    global $bdd;

    $experience = 0;

    switch ($action)
    {
      case 'add_expense':
        $experience = 5;
        break;

      case 'add_film':
      case 'add_idea':
      case 'add_restaurant':
      case 'all_missions':
        $experience = 10;
        break;

      case 'add_collector':
      case 'add_bug':
      case 'add_recipe':
        $experience = 15;
        break;

      case 'winner_mission_3':
        $experience = 30;
        break;

      case 'winner_mission_2':
        $experience = 50;
        break;

      case 'winner_mission_1':
        $experience = 100;
        break;

      default:
        break;
    }

    // Lecture expérience actuelle de l'utilisateur
    $req = $bdd->query('SELECT id, identifiant, experience FROM users WHERE identifiant = "' . $identifiant . '"');
    $data = $req->fetch();
    $current_experience = $data['experience'];
    $req->closeCursor();

    $new_experience = $current_experience + $experience;

    // Mise à jour de l'utilisateur
    $req2 = $bdd->prepare('UPDATE users SET experience = :experience WHERE identifiant = "' . $identifiant . '"');
    $req2->execute(array(
      'experience' => $new_experience
    ));
    $req2->closeCursor();

    // Mise à jour des succès des niveaux
    insertOrUpdateSuccesLevel($new_experience, $identifiant);
  }

  // Formatage Id type de restaurant
  // RETOUR : Id formaté
  function formatId($id)
  {
    // Transforme les caractères accentués en entités HTML
    $formatted = htmlentities($id, ENT_NOQUOTES, "utf-8");

    // Remplace les entités HTML pour avoir juste le premier caractères non accentué
    $formatted = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $formatted);

    // Remplace les ligatures tel que : œ, Æ ...
    $formatted = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $formatted);

    // Supprime tout le reste
    $formatted = preg_replace('#&[^;]+;#', '', $formatted);

    // Remplace les espaces
    $formatted = str_replace(" ", "_", $formatted);

    // Passe en minuscule
    $formatted = strtolower($formatted);

    return $formatted;
  }

  // Formatage du numéro de téléphone
  // RETOUR : Numéro formaté
  function formatPhoneNumber($phone)
  {
    $formattedPhone = substr($phone, 0, 2) . "." . substr($phone, 2, 2) . "." . substr($phone, 4, 2) . "." . substr($phone, 6, 2) . "." . substr($phone, 8, 2);

    return $formattedPhone;
  }

  // Conversion de l'expérience en niveau
  // RETOUR : Niveau
  function convertExperience($exp)
  {
    $level = floor(sqrt($exp / 10));

    return $level;
  }

  // Décode certains caractères
  // RETOUR : Chaîne décodée
  function decodeString($chaine)
  {
    $search  = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;");
    $replace = array("et", "", "", "", "");
    $chaine  = str_replace($search, $replace, $chaine);

    return $chaine;
  }

	// Formatage explications succès
  // RETOUR : Explications formatées
	function formatExplanation($string, $replace, $search)
	{
		$explanations = str_replace($search, $replace, $string);

		return $explanations;
	}

  // Génère le chemin vers l'avatar
  // RETOUR : Chemin & titre image
  function formatAvatar($avatar, $pseudo, $niveau, $alt)
  {
    // Niveau chemin
    switch ($niveau)
    {
      case 1:
        $level = "..";
        break;

      case 2:
        $level = "../..";
        break;

      case 0:
      default:
        $level = "/inside";
        break;
    }

    // Chemin
    if (isset($avatar) AND !empty($avatar))
      $path = $level . "/includes/images/profil/avatars/" . $avatar;
    else
      $path = $level . "/includes/icons/common/default.png";

    // Pseudo
    $pseudo = formatUnknownUser($pseudo, true, false);

    // Formatage
    $formattedAvatar = array("path"  => $path,
                             "alt"   => $alt,
                             "title" => $pseudo
                            );

    return $formattedAvatar;
  }

  // Formate le pseudo en longueur
  // RETOUR : Pseudo formaté
  function formatPseudo($pseudo, $limit)
  {
    if (strlen($pseudo) > $limit)
      $pseudo = substr($pseudo, 0, $limit) . "...";

    return $pseudo;
  }

  // Formate le pseudo utilisateur désinscrit
  // RETOUR : Pseudo ancien utilisateur
  function formatUnknownUser($pseudo, $majuscule, $italique)
  {
    if (!isset($pseudo) OR empty($pseudo))
    {
      if ($majuscule == true)
      {
        if ($italique == true)
          $pseudo = "<i>Un ancien utilisateur</i>";
        else
          $pseudo = "Un ancien utilisateur";
      }
      else
      {
        if ($italique == true)
          $pseudo = "<i>un ancien utilisateur</i>";
        else
          $pseudo = "un ancien utilisateur";
      }
    }

    return $pseudo;
  }

  // Contrôle une image avant de la télécharger
  // RETOUR : Booléen
  function controlsUploadFile($file, $name, $types)
  {
    $control_ok = true;

    $output = array('control_ok' => false,
                    'new_name'   => '',
                    'tmp_file'   => '',
                    'type_file'  => ''
                   );

    // Si on a bien une image
    if ($file['name'] != NULL)
    {
      // Données du fichier
      $name_file  = $file['name'];
      $type_file  = $file['type'];
      $tmp_file   = $file['tmp_name'];
      $error_file = $file['error'];
      $size_file  = $file['size'];

      // Limite taille maximale fichier (15 Mo)
      $maxsize = 15728640;

      // Contrôle taille fichier
      if ($error_file == 2 OR $size_file > $maxsize)
      {
        $_SESSION['alerts']['file_too_big'] = true;
        $control_ok                         = false;
      }

      // Contrôle fichier temporaire existant
      if ($control_ok == true)
      {
        if (!is_uploaded_file($tmp_file))
        {
          $_SESSION['alerts']['temp_not_found'] = true;
          $control_ok                           = false;
        }
      }

      // Contrôle type de fichier
      if ($control_ok == true)
      {
        switch ($types)
        {
          case 'jpg':
          case 'jpeg':
            if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg'))
            {
              $_SESSION['alerts']['wrong_file_type'] = true;
              $control_ok                            = false;
            }
            break;

          case 'png':
            if (!strstr($type_file, 'png'))
            {
              $_SESSION['alerts']['wrong_file_type'] = true;
              $control_ok                            = false;
            }
            break;

          case 'all':
          default:
            if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
            {
              $_SESSION['alerts']['wrong_file_type'] = true;
              $control_ok                            = false;
            }
            break;
        }
      }

      // Récupération infos
      if ($control_ok == true)
      {
        $type_image = pathinfo($name_file, PATHINFO_EXTENSION);
        $new_name   = $name . '.' . $type_image;

        $output = array('control_ok' => true,
                        'new_name'   => $new_name,
                        'tmp_file'   => $tmp_file,
                        'type_file'  => $type_image
                       );
      }
    }

    return $output;
  }

  // Télécharge une image sur le serveur
  // RETOUR : Booléen
  function uploadFile($file, $controls, $folder)
  {
    $control_ok = true;

    $tmp_file = $controls['tmp_file'];
    $name     = $controls['new_name'];

    if (!move_uploaded_file($tmp_file, $folder . $name))
    {
      $_SESSION['alerts']['wrong_file'] = true;
      $control_ok                       = false;
    }

    return $control_ok;
  }

  // Génère une chaîne aléatoire
  // RETOUR : Chaîne aléatoire
  function generateRandomString($nombreCarateres)
  {
    $string = "";
    $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    srand((double)microtime() * 1000000);

    for ($i = 0; $i < $nombreCarateres; $i++)
    {
      $string .= $chaine[rand() % strlen($chaine)];
    }

    return $string;
  }
?>