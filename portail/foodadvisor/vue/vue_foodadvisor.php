<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "FA";
      $style_head   = "styleFA.css";
      $script_head  = "scriptFA.js";
      $chat_head    = true;
      $masonry_head = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Les enfants ! À table !";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Liens
          echo '<div class="zone_liens_saisie">';
            // Saisie utilisateur
            echo '<a onclick="afficherMasquerSaisieChoix(\'zone_saisie_choix\', \'zone_marge_choix\');" title="Proposer où manger" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/food_advisor.png" alt="food_advisor" class="image_lien" style="border-radius: 50%;" /></div>';
              echo '<div class="zone_texte_lien">Proposer où manger</div>';
            echo '</a>';

            // Restaurants
            echo '<a href="restaurants.php?action=goConsulter" title="Les restaurants" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Les restaurants</div>';
            echo '</a>';
          echo '</div>';

          // Saisie choix
          include('vue/saisie_choix.php');

          // Détails détermination
          include('vue/details_determination.php');

          // Propositions, choix et résumé de la semaine
          echo '<div class="zone_propositions_determination" style="display: none;">';
            // Propositions
            include('vue/propositions.php');

            // Mes choix
            include('vue/mes_choix.php');

            // Résumé de la semaine
            include('vue/resume_semaine.php');
          echo '</div>';
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script type="text/javascript">
      // Récupération liste utilisateurs & identifiant pour le script
      var listLieux        = <?php echo $listeLieuxJson; ?>;
      var listeRestaurants = <?php echo $listeRestaurantsJson; ?>;
    </script>
  </body>
</html>
