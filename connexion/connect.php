<?php 
	session_start();

	// Appel de la BDD
	include("../includes/appel_bdd.php");

	// lecture par requête de la BDD
	$reponse = $bdd->query('SELECT * FROM users');

	$login = htmlspecialchars($_POST['login']);

	while ($donnees = $reponse->fetch())
	{
		$_SESSION['connected'] = NULL;

		if (isset($login) AND $login == $donnees['identifiant']) // 2 boucles if pour comparer pseudo et MDP
		{
			$mdp = htmlspecialchars(hash('sha1', $_POST['mdp'] . $donnees['salt'])); // On crypte de la même façon qu'à l'identification pour comparer, avec un grain de sel
			if (isset($mdp) AND $mdp == $donnees['mot_de_passe'])
			{
				// Sauvegarde des données utilisateur en SESSION
				$_SESSION['connected'] = true;
				$_SESSION['id'] = $donnees['id'];
				$_SESSION['identifiant'] = $donnees['identifiant'];
				$_SESSION['full_name'] = $donnees['full_name'];
				$_SESSION['wrong'] = false;
				
				// Recherche et sauvegarde des preferences utilisateur en SESSION
				if ($_SESSION['identifiant'] != "admin")
				{
					$reponse2 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
					$donnees2 = $reponse2->fetch();
					
					$_SESSION['view_movie_house'] = $donnees2['view_movie_house'];
					$_SESSION['today_movie_house'] = $donnees2['today_movie_house'];
					
					$reponse2->closeCursor();
				}
				
				break; // Important sinon la boucle continue et la variable connected passera forcément sur false alors qu'elle doit rester true !
			}
			else // Sinon, on affiche un message d'erreur
			{
				$_SESSION['connected'] = false;
				$_SESSION['wrong'] = true;
				
				break;
			}
		}
		else
		{
			$_SESSION['connected'] = false;
			$_SESSION['wrong'] = true;
		}
	}

	$reponse->closeCursor();

	// Redirection si OK sinon message d'erreur et retour à la page de connexion
	
	if ($_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
	{
		header('location: ../administration/administration.php');
	}
	elseif ($_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
	{
		header('location: ../portail/portail.php');
	}
	else
	{
		header('location: ../index.php');
	}
?>