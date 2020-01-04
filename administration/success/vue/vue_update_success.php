<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Succès";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Gestion succès";

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********************/
          /* Explications succès */
          /***********************/
          echo '<div class="avertissement_succes">';
            echo 'Il est possible de modifier ici le niveau, l\'ordonnancement, le titre, la description, la condition et les explications des succès. Bien contrôler l\'ordonnancement par rapport au niveau pour éviter les doublons. Il n\'est pas possible de modifier la référence ni l\'image, il faut donc supprimer le succès via l\'écran précédent. Pour les explications, insérer les caractères <i>%limit%</i> permet de les remplacer par la valeur de la conditon d\'obtention du succès.';
          echo '</div>';

          /************************/
          /* Affichage des succès */
          /************************/
          $lvl = 0;

          echo '<form method="post" action="success.php?action=doModifier" class="zone_succes_admin" style="display: none;">';
            foreach ($listeSuccess as $keySuccess => $success)
            {
              if ($success->getLevel() != $lvl)
              {
                echo formatTitleLvl($success->getLevel());
                $lvl = $success->getLevel();

                // Définit une zone pour appliquer la Masonry
                echo '<div class="zone_niveau_mod_succes_admin">';
              }

              if ($success->getDefined() == "Y")
                echo '<div class="succes_liste_mod">';
              else
                echo '<div class="succes_liste_mod" style="background-color: #b3b3b3;">';

                echo '<div class="succes_mod_left">';
                  // Id succès (caché)
                  echo '<input type="hidden" name="id[' . $success->getId() . ']" value="' . $success->getId() . '" />';

                  // Logo succès
                  echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes" />';

                  // Référence
                  echo '<div class="reference_succes">Ref. ' . $success->getReference() . '</div>';

                  // Niveau
                  echo '<div class="titre_succes">Niveau :</div>';
                  echo '<input type="text" value="' . $success->getLevel() . '" name="level[' . $success->getId() . ']" maxlength="4" class="saisie_modification_succes" />';

                  // Ordonnancement
                  echo '<div class="titre_succes">Ordre :</div>';
                  echo '<input type="text" value="' . $success->getOrder_success() . '" name="order_success[' . $success->getId() . ']" maxlength="3" class="saisie_modification_succes" />';
                echo '</div>';

                echo '<div class="succes_mod_right">';
                  // Titre succès
                  echo '<div class="titre_succes">Titre :</div>';
                  echo '<input type="text" value="' . $success->getTitle() . '" name="title[' . $success->getId() . ']" class="saisie_modification_succes" />';

                  // Description succès
                  echo '<div class="titre_succes">Description :</div>';
                  echo '<textarea name="description[' . $success->getId() . ']" class="textarea_modification_succes">' . $success->getDescription() . '</textarea>';

                  // Condition succès
                  echo '<div class="titre_succes">Condition :</div>';
                  echo '<input type="text" value="' . $success->getLimit_success() . '" name="limit_success[' . $success->getId() . ']" maxlength="3" class="saisie_modification_succes" />';

                  // Code défini
                  echo '<div class="titre_succes">Code défini :</div>';
                  echo '<div class="defined_succes">';
                    if ($success->getDefined() == "Y")
                    {
                      echo '<input type="radio" name="defined[' . $success->getId() . ']" value="Y" checked /><div class="radio_space">Oui</div>';
                      echo '<input type="radio" name="defined[' . $success->getId() . ']" value="N" />Non';
                    }
                    else
                    {
                      echo '<input type="radio" name="defined[' . $success->getId() . ']" value="Y" /><div class="radio_space">Oui</div>';
                      echo '<input type="radio" name="defined[' . $success->getId() . ']" value="N" checked />Non';
                    }
                  echo '</div>';
                echo '</div>';

                echo '<div class="succes_mod_bottom">';
                  // Explications
                  echo '<div class="titre_succes">Explications :</div>';
                  echo '<textarea name="explanation[' . $success->getId() . ']" class="textarea_modification_succes_2">' . $success->getExplanation() . '</textarea>';
                echo '</div>';
              echo '</div>';

              if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
              {
                // Termine la zone Masonry du niveau
                echo '</div>';
              }
            }

            echo '<input type="submit" value="Mettre à jour les succès" class="bouton_modification_succes" />';
          echo '</form>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>