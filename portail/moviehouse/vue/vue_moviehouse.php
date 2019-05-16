<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "MH";
      $style_head      = "styleMH.css";
      $script_head     = "scriptMH.js";
      $chat_head       = true;
      $datepicker_head = true;
      $masonry_head    = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Movie House";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');

          // Liens
          echo '<div class="zone_liens_saisie">';
            // Bouton saisie
            echo '<a id="ajouterFilm" title="Ajouter un film" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/movie_house.png" alt="movie_house" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter un film</div>';
            echo '</a>';
          echo '</div>';

          // Saisie film
          include('vue/vue_saisie_film.php');

          // Vues & Années
          echo '<div class="zone_movies_left">';
            include('vue/vue_onglets.php');
          echo '</div>';

          // Accueil ou fiches
          echo '<div class="zone_movies_right">';
            switch ($_GET['view'])
            {
              case "main":
                include("vue/vue_table_films_synthese.php");
                break;

              case "user":
                include("vue/vue_table_films_details.php");
                break;

              case "cards":
                include("vue/vue_films_fiches.php");
               break;

              case "home":
              default:
                include("vue/vue_films_accueil.php");
                break;
            }
          echo '</div>';
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
	</body>
</html>
