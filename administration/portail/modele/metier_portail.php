<?php
  // METIER : Génération du portail administration
  // RETOUR : Tableau des liens
  function getPortail($alertUsers, $alertFilms, $alertCalendars, $alertAnnexes, $nombreBugs, $nombreEvols)
  {
    // Informations utilisateurs
    $infosusers = array('ligne_1' => 'Infos',
                        'ligne_2' => 'UTILISATEURS',
                        'ligne_3' => '',
                        'lien'    => '../infosusers/infosusers.php?action=goConsulter');

    // Gestion utilisateurs
    if ($alertUsers == true)
      $alert = ' ( ! )';
    else
      $alert = '';

    $manageusers = array('ligne_1' => 'Gestion',
                         'ligne_2' => 'UTILISATEURS' . $alert,
                         'ligne_3' => '',
                         'lien'    => '../manageusers/manageusers.php?action=goConsulter');

    // Gestion thèmes
    $themes = array('ligne_1' => 'Gestion',
                    'ligne_2' => 'THÈMES',
                    'ligne_3' => '',
                    'lien'    => '../themes/themes.php?action=goConsulter');

    // Gestion succès
    $success = array('ligne_1' => 'Gestion',
                     'ligne_2' => 'SUCCÈS',
                     'ligne_3' => '',
                     'lien'    => '../success/success.php?action=goConsulter');

    // Gestion films
    if ($alertFilms == true)
      $alert = ' ( ! )';
    else
      $alert = '';

    $movies = array('ligne_1' => 'Gestion',
                    'ligne_2' => 'MOVIE',
                    'ligne_3' => 'HOUSE' . $alert,
                    'lien'    => '../movies/movies.php?action=goConsulter');

    // Gestion calendriers
    if ($alertCalendars == true OR $alertAnnexes == true)
      $alert = ' ( ! )';
    else
      $alert = '';

    $calendars = array('ligne_1' => 'Gestion',
                       'ligne_2' => 'CALENDARS' . $alert,
                       'ligne_3' => '',
                       'lien'    => '../calendars/calendars.php?action=goConsulter');

    // Gestion missions
    $missions = array('ligne_1' => 'Gestion',
                      'ligne_2' => 'MISSIONS',
                      'ligne_3' => '',
                      'lien'    => '../missions/missions.php?action=goConsulter');

    // Rapports bugs/évolutions
    $bugs = array('ligne_1' => 'Rapports',
                  'ligne_2' => 'BUGS (' . $nombreBugs . ')',
                  'ligne_3' => 'ÉVOLUTIONS (' . $nombreEvols . ')',
                  'lien'    => '../reports/reports.php?view=unresolved&action=goConsulter');

    // Gestion alertes
    $alerts = array('ligne_1' => 'Gestion',
                    'ligne_2' => 'ALERTES',
                    'ligne_3' => '',
                    'lien'    => '../alerts/alerts.php?action=goConsulter');

    // Gestion CRON
    $cron = array('ligne_1' => 'Gestion',
                  'ligne_2' => 'CRON',
                  'ligne_3' => '',
                  'lien'    => '../cron/cron.php?action=goConsulter');

    // Journal des modifications
    $changelog = array('ligne_1' => 'Journal des',
                       'ligne_2' => 'MODIFICATIONS',
                       'ligne_3' => '',
                       'lien'    => '../changelog/changelog.php?action=goConsulter');

    // Générateur de code
    $generator = array('ligne_1' => 'Générateur de',
                       'ligne_2' => 'CODE',
                       'ligne_3' => '',
                       'lien'    => '../codegenerator/codegenerator.php?action=goConsulter');

    // Assemblage portail
    $portail = array($infosusers,
                     $manageusers,
                     $themes,
                     $success,
                     $movies,
                     $calendars,
                     $missions,
                     $bugs,
                     $alerts,
                     $cron,
                     $changelog,
                     $generator
                    );

    return $portail;
  }

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    // Appel physique
    $alert = physiqueAlerteUsers();

    // Retour
    return $alert;
  }

  // METIER : Contrôle alertes Movie House
  // RETOUR : Booléen
  function getAlerteFilms()
  {
    // Appel physique
    $alert = physiqueAlerteFilms();

    // Retour
    return $alert;
  }

  // METIER : Contrôle alertes Calendars
  // RETOUR : Booléen
  function getAlerteCalendars()
  {
    // Appel physique
    $alert = physiqueAlerteCalendars();

    // Retour
    return $alert;
  }

  // METIER : Contrôle alertes Annexes
  // RETOUR : Booléen
  function getAlerteAnnexes()
  {
    // Appel physique
    $alert = physiqueAlerteAnnexes();

    // Retour
    return $alert;
  }

  // METIER : Nombre de bugs en attente
  // RETOUR : Nombre de bugs
  function getNombreBugs()
  {
    // Appel physique
    $nombreBugs = physiqueNombreBugs();

    // Retour
    return $nombreBugs;
  }

  // METIER : Nombre d'évolutions en attente
  // RETOUR : Nombre d'évolutions
  function getNombreEvols()
  {
    // Appel physique
    $nombreEvolutions = physiqueNombreEvolutions();

    // Retour
    return $nombreEvolutions;
  }

  // METIER : Extraction de la base de données
  // RETOUR : Aucun
  function extractBdd()
  {
    // Initialisations
    $contenu = '';
    $semaine = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
    $mois    = array('Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre');

    // Entête du fichier
    $contenu .= '-- Inside SQL Dump
--
-- Généré le :  ' . $semaine[date('w')] . ' ' . date('d') . ' ' . $mois[date('n')] . ' ' . date('Y') . ' à '. date('H:i') . '

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `inside`
--

';

    // Récupération de la liste des tables à extraire
    $tables = physiqueTablesBdd();

    // Traitement d'extraction de chaque table
    foreach ($tables as $table)
    {
      // Comptage du nomnbre de colonnes et de lignes
      $dimensionsTable = physiqueDimensionsTable($table);

      // Entête de la table
      $contenu .= '-- --------------------------------------------------------

--
-- Structure de la table `' . $table . '`
--';

      // Initialisation du contenu de la table (CREATE TABLE)
      $contenu .= physiqueCreateTable($table);

      // Description de la table
      $contenu .= '--
-- Contenu de la table `' . $table . '`
--
';

      // Récupération du contenu de chaque table
      $contenu .= physiqueContenuTable($table, $dimensionsTable);
    }

    // Fin de la table
    $contenu .= "
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
";

    // Génération nom du fichier
    $fileName = 'inside_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.sql';

    // Génération du fichier
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: Binary');
    header('Content-disposition: attachment; filename="' . $fileName . '"');

    // Retour
    echo $contenu;
    exit;
  }
?>
