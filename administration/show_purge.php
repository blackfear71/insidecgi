<?php
	// Contrôles communs Administrateur
	include('../includes/controls_admin.php');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - Purge</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Purge des fichiers temporaires
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="bandeau_titre_article">
					<div class="previs_article">Images présentes dans le dossier <i>portail/referenceguide/temp</i></div>
				</div>

				<form method="post" action="purge.php">
					<input type="submit" name="purge_all" value="Purger le dossier" class="bouton_purge"/>
				</form>

				<div class="zone_purge_img">
					<?php
						// On affiche tous les fichiers (sous forme d'images) présentes dans le dossier temporaire
						$files = glob('../portail/referenceguide/temp/*.*');
						$i = 0;
						foreach($files as $filename)
						{
							echo '<img src="' . $filename . '" alt="' . $filename . '" title="' . $filename . '" class="img_purge" />';
							$i++;
						}

						if ($i == 0)
							echo '<p class="submitted" style="text-align: center; color: black;">Pas de fichiers à purger.</p>';
					?>
				</div>

				<form method="post" action="purge.php">
					<input type="submit" name="purge_all" value="Purger le dossier" class="bouton_purge"/>
				</form>
			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

	</body>

</html>
