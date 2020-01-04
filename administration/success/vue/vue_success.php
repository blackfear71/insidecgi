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
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_no_nav">
				<?php
          $modify_success = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

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

          /****************/
          /* Ajout succès */
          /****************/
          echo '<div class="entete_admin">';
            echo 'Ajouter un succès';
          echo '</div>';

          echo '<form method="post" action="success.php?action=doAjouter" class="form_saisie_succes" enctype="multipart/form-data">';
            echo '<table class="table_saisie_succes">';
              echo '<tr>';
                // Titre
                echo '<td class="td_saisie_succes_tit">';
                  echo '<input type="text" name="title" placeholder="Titre" value="' . $_SESSION['save']['title_success'] . '" class="saisie_succes_tit" required />';
                echo '</td>';

                // Référence
                echo '<td class="td_saisie_succes_ref">';
                  echo '<input type="text" name="reference" placeholder="Référence" value="' . $_SESSION['save']['reference_success'] . '" maxlength="255" class="saisie_succes_ref" required />';
                echo '</td>';

                // Niveau
                echo '<td class="td_saisie_succes_niv">';
                  echo '<input type="text" name="level" placeholder="Niveau" value="' . $_SESSION['save']['level'] . '" maxlength="4" class="saisie_succes_niv" required />';
                echo '</td>';

                // Ordonnancement
                echo '<td class="td_saisie_succes_ord">';
                  echo '<input type="text" name="order_success" placeholder="Ordonnancement" value="' . $_SESSION['save']['order_success'] . '" maxlength="3" class="saisie_succes_ord" required />';
                echo '</td>';

                // Bouton parcourir
                echo '<td class="td_saisie_succes_par">';
                  echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';
                  echo '<div class="zone_parcourir_succes">';
                    echo '<div class="label_parcourir">Parcourir</div>';
                    echo '<input type="file" accept=".png" name="success" class="bouton_parcourir_succes" required />';
                  echo '</div>';
                echo '</td>';

                // Bouton envoi
                echo '<td class="td_saisie_succes_env">';
                  echo '<input type="submit" name="send" value="" class="send_succes" />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Description
                echo '<td colspan="4" class="td_saisie_succes_desc">';
                  echo '<input type="text" name="description" placeholder="Description" value="' . $_SESSION['save']['description_success'] . '" class="saisie_description" required />';
                echo '</td>';

                // Condition
                echo '<td colspan="2" class="td_saisie_succes_cond">';
                  echo '<input type="text" name="limit_success" placeholder="Condition" value="' . $_SESSION['save']['limit_success'] . '" maxlength="3" class="saisie_limit" required />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Explications
                echo '<td colspan="6" class="td_saisie_succes_expl">';
                  echo '<input type="text" name="explanation" placeholder="Explications (utiliser %limit%)" value="' . $_SESSION['save']['explanation_success'] . '" class="saisie_explaination" required />';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
          echo '</form>';

          // Indications ajout succès
          echo '<div class="titre_explications">Lors de l\'ajout d\'un succès</div>';

          echo '<div class="contenu_explications">';
            echo 'Ne pas oublier d\'ajouter le code de la fonction <strong>initializeSuccess()</strong> dans <strong>metier_administration.php</strong> ainsi que la fonction
            <strong>insertOrUpdateSuccesValue()</strong> dans <strong>fonctions_communes.php</strong>.';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'Si c\'est un succès relatif à une mission, mettre à jour également la fonction <strong>insertOrUpdateSuccesMission()</strong> dans <strong>fonctions_communes.php</strong>
            et la fonction <strong>isMissionEnded()</strong> dans <strong>metier_profil.php</strong>. Une fois le code ajouté, modifier le succès pour changer son état à "<strong>Défini</strong>".';
          echo '</div>';

          // Indications suppression succès
          echo '<div class="titre_explications">Lors de la suppression d\'un succès</div>';

          echo '<div class="contenu_explications">';
            echo 'Ne pas oublier de supprimer le code de la fonction <strong>initializeSuccess()</strong> dans <strong>metier_administration.php</strong> et
            <strong>insertOrUpdateSuccesValue()</strong> dans <strong>fonction_communes.php</strong>.';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'Si c\'est un succès relatif à une mission, mettre à jour également la fonction <strong>insertOrUpdateSuccesMission()</strong> dans <strong>fonctions_communes.php</strong>
            et la fonction <strong>isMissionEnded()</strong> dans <strong>metier_profil.php</strong>.';
          echo '</div>';
          
          // Gestion des succès
          echo '<div class="entete_admin">';
            echo 'Gérer les succès des utilisateurs';
          echo '</div>';

          echo '<form id="purgeSuccess" method="post" action="success.php?action=doPurger" class="form_init_succes">';
            echo '<input type="submit" name="purge_success" value="Purger les succès" class="bouton_init eventConfirm" />';
            echo '<input type="hidden" value="Voulez-vous vraiment purger les succès ? Ceci est définitif." class="eventMessage" />';
          echo '</form>';

          echo '<div class="explications_init">';
            echo 'Ce bouton permet d\'effacer tous les succès des utilisateurs dans la base de données sauf les suivants :';

            echo '<ul class="margin_top_0 margin_bottom_0">';
              echo '<li>greedy</li>';
              echo '<li>restaurant-finder</li>';
            echo '</ul>';
          echo '</div>';

          echo '<form id="initializeSuccess" method="post" action="success.php?action=doInitialiser" class="form_init_succes">';
            echo '<input type="submit" name="init_success" value="Initialiser les succès" class="bouton_init eventConfirm" />';
            echo '<input type="hidden" value="Voulez-vous vraiment initialiser les succès ?" class="eventMessage" />';
          echo '</form>';

          echo '<div class="explications_init">';
            echo 'Ce bouton permet d\'initialiser les succès pour tous les utilisateurs. Il faut faire attention lors de son utilisation car il va remplacer les valeurs déjà
            acquises par tous les utilisateurs et potentiellement bloquer des succès déjà débloqués. Le traitement peut prendre du temps en fonction du nombre de succès et d\'utilisateurs. Une
            purge est effectuée en fin de traitement sur tous les éventuels succès à 0.';
          echo '</div>';

          // Affichage des succès
          $lvl = 0;

          echo '<div class="zone_succes_admin" style="display: none;">';
            foreach ($listeSuccess as $keySuccess => $success)
            {
              if ($success->getLevel() != $lvl)
              {
                echo formatTitleLvl($success->getLevel());
                $lvl = $success->getLevel();

                // Définit une zone pour appliquer la Masonry
                echo '<div class="zone_niveau_succes_admin">';
              }

              echo '<div class="ensemble_succes">';
                // Suppression succès
                echo '<form method="post" id="delete_success_' . $success->getId() . '" action="success.php?action=doSupprimer" class="form_suppression_succes">';
                  echo '<input type="hidden" name="id_success" value="' . $success->getId() . '" />';
                  echo '<input type="submit" name="delete_success" value="" title="Supprimer le succès" class="bouton_delete eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer le succès &quot;' . formatOnclick($success->getTitle()) . '&quot; ?" class="eventMessage" />';
                echo '</form>';

                if ($success->getDefined() == "Y")
                  echo '<div class="succes_liste">';
                else
                  echo '<div class="succes_liste" style="background-color: #b3b3b3;">';

                  // Ordonnancement
                  echo '<div class="ordonnancement_succes">' . $success->getOrder_success() . '</div>';

                  // Condition
                  echo '<div class="condition_succes">/ ' . $success->getLimit_success() . '</div>';

                  // Logo succès
                  echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes" />';

                  // Titre succès
                  echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

                  // Description succès
                  echo '<div class="description_succes">' . $success->getDescription() . '</div>';

                  // Explications succès
                  if ($success->getDefined() == "Y")
                    echo '<div class="explications_succes">' . formatExplanation($success->getExplanation(), $success->getLimit_success(), '%limit%') . '</div>';
                  else
                    echo '<div class="explications_succes" style="background-color: #979797;">' . formatExplanation($success->getExplanation(), $success->getLimit_success(), '%limit%') . '</div>';
                echo '</div>';
              echo '</div>';

              if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
              {
                // Termine la zone Masonry du niveau
                echo '</div>';
              }
            }
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>