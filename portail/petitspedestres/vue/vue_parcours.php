<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="stylePP.css" />

  	<title>Inside - PP</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$ajouter_parcours = true;
					$modify_parcours = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
        <!-- Bandeau catégorie -->
				<a href="parcours.php?action=liste">
					<img src="../../includes/images/petits_pedestres_band.png" alt="petits_pedestres_band" class="bandeau_categorie" />
				</a>

				<div class="PP-parcours">
          <div class="PP-titre">
            <?php echo $parcours->getNom(); ?>
          </div>

          <p>
            Distance : <?php echo $parcours->getDistance() . ' km'; ?><br/>
            Lieu : <?php echo $parcours->getLieu(); ?>

            <?php
              if ($parcours->isImageSet())
              {
                echo '<br/><img src="' . $parcours->getImage() .'" alt="' . $parcours->getNom() . ' classe="img_article" /><br/>';
              }
            ?>
          </p>
        </div>
      </article>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
