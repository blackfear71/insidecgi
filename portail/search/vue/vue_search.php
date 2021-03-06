<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Recherche';
      $styleHead       = 'styleSearch.css';
      $scriptHead      = '';
      $angularHead     = false;
      $chatHead        = true;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Recherche';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section class="section_no_nav">
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********************/
          /* Résultats recherche */
          /***********************/
          echo '<div class="zone_recherche">';
            if (!empty($resultats))
            {
              // Message pas de résultats
              if (empty($resultats['movie_house'])
              AND empty($resultats['food_advisor'])
              AND empty($resultats['petits_pedestres'])
              AND empty($resultats['missions']))
                echo '<div class="titre_section"><img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" /><div class="texte_titre_section">Pas de résultats trouvés pour "' . $_SESSION['search']['text_search'] . '" !</div></div>';

              // Résultats par section
              if (!empty($resultats['movie_house']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/movie_house.png" alt="movie_house" class="logo_titre_section" /><div class="texte_titre_section">Movie House<div class="count_search">' . $resultats['nb_movie_house'] . '</div></div></div>';

                // Résultats
                foreach ($resultats['movie_house'] as $resultatsMH)
                {
                  echo '<a href="../moviehouse/details.php?id_film=' . $resultatsMH->getId() . '&action=goConsulter" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsMH->getFilm();
                        echo '</td>';

                        echo '<td class="zone_resultat_info">';
                          if (!empty($resultatsMH->getDate_theater()))
                            echo 'Sortie au cinéma le ' . formatDateForDisplay($resultatsMH->getDate_theater());
                          else
                            echo 'Sortie au cinéma non communiquée';
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }

              if (!empty($resultats['food_advisor']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/restaurants.png" alt="restaurants" class="logo_titre_section" /><div class="texte_titre_section">Restaurants<div class="count_search">' . $resultats['nb_food_advisor'] . '</div></div></div>';

                // Résultats
                foreach ($resultats['food_advisor'] as $resultatsFA)
                {
                  echo '<a href="../foodadvisor/restaurants.php?action=goConsulter&anchor=' . $resultatsFA->getId() . '" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsFA->getName();
                        echo '</td>';

                        echo '<td class="zone_resultat_info">';
                          echo $resultatsFA->getLocation();
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }

              if (!empty($resultats['petits_pedestres']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/petits_pedestres.png" alt="petits_pedestres" class="logo_titre_section" /><div class="texte_titre_section">Les Petits Pédestres<div class="count_search">' . $resultats['nb_petits_pedestres'] . '</div></div></div>';

                // Résultats
                foreach ($resultats['petits_pedestres'] as $resultatsPP)
                {
                  echo '<a href="../petitspedestres/parcours.php?id_parcours=' . $resultatsPP->getId() . '&action=goConsulter" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsPP->getNom();
                        echo '</td>';

                        echo '<td class="zone_resultat_info">';
                          echo formatDistanceForDisplay($resultatsPP->getDistance());
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }

              if (!empty($resultats['missions']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/missions.png" alt="missions" class="logo_titre_section" /><div class="texte_titre_section">Missions<div class="count_search">' . $resultats['nb_missions'] . '</div></div></div>';

                // Résultats
                foreach ($resultats['missions'] as $resultatsMI)
                {
                  echo '<a href="../missions/details.php?id_mission=' . $resultatsMI->getId() . '&action=goConsulter" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsMI->getMission();
                        echo '</td>';

                        echo '<td class="zone_resultat_info">';
                          if (date('Ymd') > $resultatsMI->getDate_fin())
                            echo 'Terminée le ' . formatDateForDisplay($resultatsMI->getDate_fin());
                          else
                            echo 'Débutée le ' . formatDateForDisplay($resultatsMI->getDate_deb());
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }
            }
            else
            {
              echo '<div class="titre_section"><img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" /><div class="texte_titre_section">Pas de résultats</div></div>';

              echo '<div class="empty">Veuillez saisir et relancer la recherche afin d\'obtenir des résultats...</div>';
            }
          echo '</div>';
				?>
			</article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
