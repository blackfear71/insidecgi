<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMH.css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptMH.js"></script>

		<title>Inside - MH</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Movie House";

			  include('../../includes/onglets.php') ;
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$add_film   = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;
          $notifs     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<!-- Switch entre accueil, vue générale et vue personnelle-->
				<div class="switch_view_2">
					<?php
						$listeSwitch = array('home' => array('lib' => 'Accueil',  'date' => date("Y")),
																 'main' => array('lib' => 'Synthèse', 'date' => $_GET['year']),
																 'user' => array('lib' => 'Détails',  'date' => $_GET['year'])
																);

						foreach ($listeSwitch as $view => $lib_view)
						{
							if ($_GET['view'] == $view)
								$switch = '<a href="moviehouse.php?view=' . $view . '&year=' . $lib_view['date'] . '&action=goConsulter" class="link_switch_active">' . $lib_view['lib'] . '</a>';
							else
								$switch = '<a href="moviehouse.php?view=' . $view . '&year=' . $lib_view['date'] . '&action=goConsulter" class="link_switch_inactive">' . $lib_view['lib'] . '</a>';

							echo $switch;
						}
					?>
				</div>

				<!-- Affichage de la page en fonction de la vue -->
				<?php
          switch($_GET['view'])
          {
            case "main":
              include("vue/onglets_moviehouse.php");
              include("vue/table_films_main.php");
              break;

            case "user":
              include("vue/onglets_moviehouse.php");
              include("vue/table_films_user.php");
              break;

            case "home":
            default:
              include("vue/table_films_home.php");
              break;
          }
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
