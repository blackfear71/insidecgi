<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des utilisateurs
  // RETOUR : Liste des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, team, new_team, status, pseudo, avatar, email, anniversary, experience
                        FROM users
                        WHERE identifiant != "admin"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des identifiants des films
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsFilms()
  {
    // Initialisations
    $listeUsersFilms = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT identifiant_add
                        FROM movie_house
                        ORDER BY identifiant_add ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersFilms, $data['identifiant_add']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersFilms;
  }

  // PHYSIQUE : Lecture des identifiants des commentaires de films
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsCommentairesFilms()
  {
    // Initialisations
    $listeUsersComments = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT author
                        FROM movie_house_comments
                        ORDER BY author ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersComments, $data['author']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersComments;
  }

  // PHYSIQUE : Lecture des identifiants des phrases cultes
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsCollector()
  {
    // Initialisations
    $listeUsersCollector = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT author
                        FROM collector
                        ORDER BY author ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersCollector, $data['author']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersCollector;
  }

  // PHYSIQUE : Lecture des identifiants des dépenses
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsDepenses()
  {
    // Initialisations
    $listeUsersExpenses = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT buyer
                        FROM expense_center
                        ORDER BY buyer ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersExpenses, $data['buyer']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersExpenses;
  }

  // PHYSIQUE : Lecture des identifiants des parts des dépenses
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsPartsDepenses()
  {
    // Initialisations
    $listeUsersParts = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT identifiant
                        FROM expense_center_users
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersParts, $data['identifiant']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersParts;
  }

  // PHYSIQUE : Lecture des identifiants des bugs/évolutions
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsBugs()
  {
    // Initialisations
    $listeUsersBugs = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT author
                        FROM bugs
                        ORDER BY author ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersBugs, $data['author']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersBugs;
  }

  // PHYSIQUE : Lecture des identifiants des idées #TheBox
  // RETOUR : Liste des utilisateurs uniques
  function physiqueIdentifiantsTheBox()
  {
    // Initialisations
    $listeUsersTheBox = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT author
                        FROM ideas
                        ORDER BY author ASC');

    while ($data = $req->fetch())
    {
      array_push($listeUsersTheBox, $data['author']);
    }

    $req->closeCursor();

    // Retour
    return $listeUsersTheBox;
  }

  // PHYSIQUE : Lecture de la liste des équipes
  // RETOUR : Liste des équipes
  function physiqueListeEquipes()
  {
    // Initialisations
    $listeEquipes = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM teams
                        ORDER BY team ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Team à partir des données remontées de la bdd
      $equipe = Team::withData($data);

      // On ajoute la ligne au tableau
      $listeEquipes[$equipe->getReference()] = $equipe;
    }

    $req->closeCursor();

    // Retour
    return $listeEquipes;
  }

  // PHYSIQUE : Lecture alerte utilisateurs
  // RETOUR : Booléen
  function physiqueAlerteUsers()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_status_users
                        FROM users
                        WHERE identifiant != "admin" AND (status = "P" OR status = "I" OR status = "D"  OR status = "T")
                        ORDER BY identifiant ASC');
    $data = $req->fetch();

    if ($data['nombre_status_users'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Lecture du nombre de films ajoutés
  // RETOUR : Nombre de films ajoutés
  function physiqueFilmsAjoutesUser($identifiant)
  {
    // Initialisations
    $nombreAjouts = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreAjouts
                        FROM movie_house
                        WHERE identifiant_add = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreAjouts = $data['nombreAjouts'];

    $req->closeCursor();

    // Retour
    return $nombreAjouts;
  }

  // PHYSIQUE : Lecture du nombre de commentaires de films ajoutés
  // RETOUR : Nombre de commentaires ajoutés
  function physiqueCommentairesFilmsUser($identifiant)
  {
    // Initialisations
    $nombreComments = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreComments
                        FROM movie_house_comments
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreComments = $data['nombreComments'];

    $req->closeCursor();

    // Retour
    return $nombreComments;
  }

  // PHYSIQUE : Lecture du bilan des dépenses d'un utilisateur
  // RETOUR : Bilan des dépenses de l'utilisateur
  function physiqueBilanDepensesUser($identifiant)
  {
    // Initialisations
    $bilanUser = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, expenses
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $bilanUser = $data['expenses'];

    $req->closeCursor();

    // Retour
    return $bilanUser;
  }

  // PHYSIQUE : Lecture du nombre de phrases cultes ajoutées
  // RETOUR : Nombre de phrases cultes ajoutés
  function physiqueCollectorAjoutesUser($identifiant)
  {
    // Initialisations
    $nombreCollector = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                        FROM collector
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreCollector = $data['nombreCollector'];

    $req->closeCursor();

    // Retour
    return $nombreCollector;
  }

  // PHYSIQUE : Lecture du nombre de réservations de restaurants
  // RETOUR : Nombre de réservations
  function physiqueReservationsUser($identifiant)
  {
    // Initialisations
    $nombreReservations = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreReservations
                        FROM food_advisor_choices
                        WHERE caller = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreReservations = $data['nombreReservations'];

    $req->closeCursor();

    // Retour
    return $nombreReservations;
  }

  // PHYSIQUE : Lecture du nombre de gâteaux de la semaine
  // RETOUR : Nombre de gâteaux de la semaine
  function physiqueGateauxSemaineUser($identifiant)
  {
    // Initialisations
    $nombreGateauxSemaine = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreGateauxSemaine
                        FROM cooking_box
                        WHERE identifiant = "' . $identifiant . '" AND cooked = "Y"');

    $data = $req->fetch();

    $nombreGateauxSemaine = $data['nombreGateauxSemaine'];

    $req->closeCursor();

    // Retour
    return $nombreGateauxSemaine;
  }

  // PHYSIQUE : Lecture du nombre de recettes partagées
  // RETOUR : Nombre de recettes partagées
  function physiqueRecettesUser($identifiant)
  {
    // Initialisations
    $nombreRecettes = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreRecettes
                        FROM cooking_box
                        WHERE identifiant = "' . $identifiant . '" AND name != "" AND picture != ""');

    $data = $req->fetch();

    $nombreRecettes = $data['nombreRecettes'];

    $req->closeCursor();

    // Retour
    return $nombreRecettes;
  }

  // PHYSIQUE : Lecture de la liste des dépenses
  // RETOUR : Liste des dépenses
  function physiqueDepenses()
  {
    // Initialisations
    $listeExpenses = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center
                        ORDER BY id ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Expenses à partir des données remontées de la bdd
      $expense = Expenses::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeExpenses, $expense);
    }

    $req->closeCursor();

    // Retour
    return $listeExpenses;
  }

  // PHYSIQUE : Lecture des parts d'une dépense
  // RETOUR : Nombre de parts d'une dépense
  function physiquePartsDepensesUser($idExpense, $identifiant)
  {
    // Initialisations
    $nombreParts = array('total'              => 0,
                         'utilisateur'        => 0,
                         'nombreUtilisateurs' => 0
                        );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center_users
                        WHERE id_expense = ' . $idExpense);

    while ($data = $req->fetch())
    {
      // Nombre de parts total
      $nombreParts['total'] += $data['parts'];

      // Nombre de parts de l'utilisateur
      if ($identifiant == $data['identifiant'])
        $nombreParts['utilisateur'] = $data['parts'];

      // Nombre de participants
      $nombreParts['nombreUtilisateurs'] += 1;
    }

    $req->closeCursor();

    // Retour
    return $nombreParts;
  }

  // PHYSIQUE : Lecture du nombre total de films ajoutés
  // RETOUR : Nombre total de films ajoutés
  function physiqueFilmsAjoutesTotal()
  {
    // Initialisations
    $nombreAjouts = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreAjouts
                        FROM movie_house');

    $data = $req->fetch();

    $nombreAjouts = $data['nombreAjouts'];

    $req->closeCursor();

    // Retour
    return $nombreAjouts;
  }

  // PHYSIQUE : Lecture du nombre total de commentaires de films ajoutés
  // RETOUR : Nombre total de commentaires ajoutés
  function physiqueCommentairesFilmsTotal()
  {
    // Initialisations
    $nombreComments = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreComments
                        FROM movie_house_comments');

    $data = $req->fetch();

    $nombreComments = $data['nombreComments'];

    $req->closeCursor();

    // Retour
    return $nombreComments;
  }

  // PHYSIQUE : Lecture du nombre total de phrases cultes ajoutées
  // RETOUR : Nombre total de phrases cultes ajoutées
  function physiqueCollectorTotal()
  {
    // Initialisations
    $nombreCollector = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                        FROM collector');

    $data = $req->fetch();

    $nombreCollector = $data['nombreCollector'];

    $req->closeCursor();

    // Retour
    return $nombreCollector;
  }

  // PHYSIQUE : Lecture du nombre total de réservations de restaurants
  // RETOUR : Nombre total de réservations de restaurants
  function physiqueReservationsTotal()
  {
    // Initialisations
    $nombreReservations = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreReservations
                        FROM food_advisor_choices
                        WHERE caller != ""');

    $data = $req->fetch();

    $nombreReservations = $data['nombreReservations'];

    $req->closeCursor();

    // Retour
    return $nombreReservations;
  }

  // PHYSIQUE : Lecture du nombre total de gâteaux de la semaine
  // RETOUR : Nombre total de gâteaux de la semaine
  function physiqueGateauxSemaineTotal()
  {
    // Initialisations
    $nombreGateauxSemaine = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreGateauxSemaine
                        FROM cooking_box
                        WHERE cooked = "Y"');

    $data = $req->fetch();

    $nombreGateauxSemaine = $data['nombreGateauxSemaine'];

    $req->closeCursor();

    // Retour
    return $nombreGateauxSemaine;
  }

  // PHYSIQUE : Lecture du nombre total de recettes partagées
  // RETOUR : Nombre total de recettes partagées
  function physiqueRecettesTotal()
  {
    // Initialisations
    $nombreRecettes = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreRecettes
                        FROM cooking_box
                        WHERE name != "" AND picture != ""');

    $data = $req->fetch();

    $nombreRecettes = $data['nombreRecettes'];

    $req->closeCursor();

    // Retour
    return $nombreRecettes;
  }

  // PHYSIQUE : Lecture des dépenses sans parts
  // RETOUR : Booléen
  function physiqueDepenseSansParts($idExpense)
  {
    // Initialisations
    $noParts = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreParts
                        FROM expense_center_users
                        WHERE id_expense = ' . $idExpense);

    $data = $req->fetch();

    if ($data['nombreParts'] == 0)
      $noParts = true;

    $req->closeCursor();

    // Retour
    return $noParts;
  }

  // PHYSIQUE : Lecture du nombre de bugs soumis d'un utilisateur
  // RETOUR : Nombre de bugs soumis de l'utilisateur
  function physiqueBugsSoumisUser($identifiant)
  {
    // Initialisations
    $nombreBugsSoumis = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugsSoumis
                        FROM bugs
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreBugsSoumis = $data['nombreBugsSoumis'];

    $req->closeCursor();

    // Retour
    return $nombreBugsSoumis;
  }

  // PHYSIQUE : Lecture du nombre de bugs résolus d'un utilisateur
  // RETOUR : Nombre de bugs résolus de l'utilisateur
  function physiqueBugsResolusUser($identifiant)
  {
    // Initialisations
    $nombreBugsResolus = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugsResolus
                        FROM bugs
                        WHERE author = "' . $identifiant . '" AND resolved = "Y"');

    $data = $req->fetch();

    $nombreBugsResolus = $data['nombreBugsResolus'];

    $req->closeCursor();

    // Retour
    return $nombreBugsResolus;
  }

  // PHYSIQUE : Lecture du nombre d'idées #TheBox soumises d'un utilisateur
  // RETOUR : Nombre d'idées soumises de l'utilisateur
  function physiqueTheBoxUser($identifiant)
  {
    // Initialisations
    $nombreTheBox = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBox
                        FROM ideas
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreTheBox = $data['nombreTheBox'];

    $req->closeCursor();

    // Retour
    return $nombreTheBox;
  }

  // PHYSIQUE : Lecture du nombre d'idées #TheBox en charge d'un utilisateur
  // RETOUR : Nombre d'idées en charge de l'utilisateur
  function physiqueTheBoxEnChargeUser($identifiant)
  {
    // Initialisations
    $nombreTheBoxEnCharge = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBoxEnCharge
                        FROM ideas
                        WHERE developper = "' . $identifiant . '" AND (status = "C" OR status = "P")');

    $data = $req->fetch();

    $nombreTheBoxEnCharge = $data['nombreTheBoxEnCharge'];

    $req->closeCursor();

    // Retour
    return $nombreTheBoxEnCharge;
  }

  // PHYSIQUE : Lecture du nombre d'idées #TheBox terminées d'un utilisateur
  // RETOUR : Nombre d'idées terminées ou rejetées de l'utilisateur
  function physiqueTheBoxTermineesUser($identifiant)
  {
    // Initialisations
    $nombreTheBoxTerminees = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBoxTerminees
                        FROM ideas
                        WHERE developper = "' . $identifiant . '" AND (status = "D" OR status = "R")');

    $data = $req->fetch();

    $nombreTheBoxTerminees = $data['nombreTheBoxTerminees'];

    $req->closeCursor();

    // Retour
    return $nombreTheBoxTerminees;
  }

  // PHYSIQUE : Lecture du nombre total de bugs soumis
  // RETOUR : Nombre total de bugs/évolutions soumis
  function physiqueBugsSoumisTotal()
  {
    // Initialisations
    $nombreBugsSoumis = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugsSoumis
                        FROM bugs');

    $data = $req->fetch();

    $nombreBugsSoumis = $data['nombreBugsSoumis'];

    $req->closeCursor();

    // Retour
    return $nombreBugsSoumis;
  }

  // PHYSIQUE : Lecture du nombre total de bugs résolus
  // RETOUR : Nombre total de bugs/évolutions résolus
  function physiqueBugsResolusTotal()
  {
    // Initialisations
    $nombreBugsResolus = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugsResolus
                        FROM bugs
                        WHERE resolved = "Y"');

    $data = $req->fetch();

    $nombreBugsResolus = $data['nombreBugsResolus'];

    $req->closeCursor();

    // Retour
    return $nombreBugsResolus;
  }

  // PHYSIQUE : Lecture du nombre total d'idées #TheBox soumises
  // RETOUR : Nombre total d'idées soumises
  function physiqueTheBoxTotal()
  {
    // Initialisations
    $nombreTheBox = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBox
                        FROM ideas');

    $data = $req->fetch();

    $nombreTheBox = $data['nombreTheBox'];

    $req->closeCursor();

    // Retour
    return $nombreTheBox;
  }

  // PHYSIQUE : Lecture du nombre total d'idées #TheBox en charge
  // RETOUR : Nombre total d'idées en charge
  function physiqueTheBoxEnChargeTotal()
  {
    // Initialisations
    $nombreTheBoxEnCharge = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBoxEnCharge
                        FROM ideas
                        WHERE developper != "" AND (status = "C" OR status = "P")');

    $data = $req->fetch();

    $nombreTheBoxEnCharge = $data['nombreTheBoxEnCharge'];

    $req->closeCursor();

    // Retour
    return $nombreTheBoxEnCharge;
  }

  // PHYSIQUE : Lecture du nombre total d'idées #TheBox terminées
  // RETOUR : Nombre total d'idées terminées ou rejetées
  function physiqueTheBoxTermineesTotal()
  {
    // Initialisations
    $nombreTheBoxTerminees = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBoxTerminees
                        FROM ideas
                        WHERE (status = "D" OR status = "R")');

    $data = $req->fetch();

    $nombreTheBoxTerminees = $data['nombreTheBoxTerminees'];

    $req->closeCursor();

    // Retour
    return $nombreTheBoxTerminees;
  }

  // PHYSIQUE : Lecture du pseudo d'un utilisateur
  // RETOUR : Pseudo utilisateur
  function physiquePseudoUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $pseudo = $data['pseudo'];

    $req->closeCursor();

    // Retour
    return $pseudo;
  }

  // PHYSIQUE : Lecture des données d'un utilisateur
  // RETOUR : Objet utilisateur
  function physiqueDonneesDesinscriptionUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar, expenses
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');
    $data = $req->fetch();

    $user = Profile::withData($data);

    $req->closeCursor();

    return $user;
  }

  // PHYSIQUE : Lecture des missions en cours
  // RETOUR : Liste des Id de missions
  function physiqueMissionsEnCours()
  {
    // Initialisations
    $idMissionsEnCours = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE date_deb <= ' . date('Ymd') . ' AND date_fin >= ' . date('Ymd'));

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($idMissionsEnCours, $data['id']);
    }

    $req->closeCursor();

    // Retour
    return $idMissionsEnCours;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle équipe
  // RETOUR : Id alerte
  function physiqueInsertionEquipe($equipe)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO teams(reference,
                                            team,
                                            activation)
                                    VALUES(:reference,
                                           :team,
                                           :activation)');

    $req->execute($equipe);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour équipe
  // RETOUR : Aucun
  function physiqueUpdateEquipe($equipe, $referenceTemporaire)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE teams
                          SET reference  = :reference,
                              team       = :team,
                              activation = :activation
                          WHERE reference = "' . $referenceTemporaire . '"');

    $req->execute($equipe);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour utilisateur
  // RETOUR : Aucun
  function physiqueUpdateProfilUser($user, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET team     = :team,
                              new_team = :new_team,
                              status   = :status
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute($user);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour statut utilisateur
  // RETOUR : Aucun
  function physiqueUpdateStatusUser($identifiant, $status)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET status = :status
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'status' => $status
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour mot de passe utilisateur
  // RETOUR : Aucun
  function physiqueSetNewPassword($identifiant, $salt, $password, $status)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET salt     = :salt,
                              password = :password,
                              status   = :status
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'salt'     => $salt,
      'password' => $password,
      'status'   => $status
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour Speaker des phrases cultes lors de la désinscription
  // RETOUR : Aucun
  function physiqueUpdateSpeakerCollector($identifiant, $pseudo)
  {
    // Initialisations
    $speaker     = substr($pseudo, 0, 100);
    $typeSpeaker = 'other';

    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE collector
                          SET speaker      = :speaker,
                              type_speaker = :type_speaker
                          WHERE speaker = "' . $identifiant . '"');

    $req->execute(array(
      'speaker'      => $speaker,
      'type_speaker' => $typeSpeaker
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour statut des idées #TheBox
  // RETOUR : Aucun
  function physiqueUpdateStatusTheBox($identifiant)
  {
    // Initialisations
    $status     = 'O';
    $developper = '';

    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE ideas
                          SET status     = :status,
                              developper = :developper
                          WHERE developper = "' . $identifiant . '" AND status != "D" AND status != "R"');

    $req->execute(array(
      'status'     => $status,
      'developper' => $developper
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour des missions en cours
  // RETOUR : Aucun
  function physiqueUpdateMissionsEnCours($idMission, $identifiant, $equipe)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE missions_users
                          SET team = :team
                          WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'team' => $equipe
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression des votes films d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteStarsFilmsUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM movie_house_users
                       WHERE identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des votes phrases cultes d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteVotesCollectorUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM collector_users
                       WHERE identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des missions d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteMissionsUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM missions_users
                       WHERE identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des succès d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteSuccessUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM success_users
                       WHERE identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des votes restaurant d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteVotesRestaurantsUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_users
                       WHERE identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des déterminations d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteDeterminationsRestaurantsUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_choices
                       WHERE caller = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des semaines de gâteau futures d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteSemainesGateauxUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM cooking_box
                       WHERE (year > ' . date('Y') . ' OR (year = ' . date('Y') . ' AND week > ' . date('W') . ')) AND identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression des préférences d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeletePreferences($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM preferences
                       WHERE identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression de la nouvelle équipe
  // RETOUR : Aucun
  function physiqueDeleteEquipe($equipe)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM teams
                       WHERE reference = "' . $equipe . '" AND activation = "N"');
  }

  // PHYSIQUE : Suppression des recettes d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteRecette($identifiant, $equipe)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM cooking_box
                       WHERE  identifiant = "' . $identifiant . '"
                         AND  team        = "' . $equipe . '"
                         AND (year > ' . date('Y') . ' OR (year = ' . date('Y') . ' AND week >= ' . date('W') . '))
                         AND  cooked      = "N"
                         AND  name        = ""
                         AND  picture     = ""');
  }

  // PHYSIQUE : Suppression d'un utilisateur
  // RETOUR : Aucun
  function physiqueDeleteUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM users
                       WHERE identifiant = "' . $identifiant . '"');
  }
?>
