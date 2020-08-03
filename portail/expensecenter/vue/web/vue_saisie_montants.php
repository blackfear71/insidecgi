<?php
  /**********************************/
  /*** Zone de saisie de montants ***/
  /**********************************/
  echo '<div id="zone_add_montants" style="display: none;" class="fond_saisie_depense">';
    echo '<div class="zone_saisie_depense">';
      // Titre
      echo '<div class="titre_saisie_depense">Saisir des montants</div>';

      // Bouton fermeture
      echo '<a id="resetMontants" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      // Saisie dépense
      echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doInsererMontants" class="form_saisie_depense">';
        echo '<input type="hidden" name="id_expense" value="" />';

        // Achat
        echo '<div class="zone_saisie_left">';
          // Acheteur
          echo '<select name="buyer_user" class="saisie_buyer" required>';
            echo '<option value="" hidden>Choisissez un acheteur...</option>';

            foreach ($listeUsers as $user)
            {
              if ($user->getIdentifiant() == $_SESSION['save']['buyer'])
                echo '<option value="' . $_SESSION['save']['buyer'] . '" selected>' . $user->getPseudo() . '</option>';
              else
                echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
            }
          echo '</select>';

          // Frais additionnels
          echo '<div class="zone_saisie_prix">';
            echo '<input type="text" name="depense" value="' . $_SESSION['save']['price'] . '" autocomplete="off" placeholder="Frais additionnels" maxlength="6" class="saisie_prix" />';
            echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="euros" class="euro" />';
          echo '</div>';

          // Commentaire
          echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

          // Bouton validation
          echo '<div class="zone_bouton_saisie_montants">';
            echo '<input type="submit" name="add_depense" value="Valider" id="bouton_saisie_montants" class="saisie_bouton" />';
          echo '</div>';
        echo '</div>';

        // Montants utilisateurs
        echo '<div class="zone_saisie_right">';
          // Parts
          echo '<div class="zone_saisie_utilisateurs">';
            foreach ($listeUsers as $user)
            {
              $savedAmounts = false;

              if (isset($_SESSION['save']['tableau_montants']) AND !empty($_SESSION['save']['tableau_montants']))
              {
                if (isset($_SESSION['save']['tableau_montants'][$user->getIdentifiant()]))
                  $savedAmounts = true;
              }

              echo '<div class="zone_saisie_utilisateur">';
                // Pseudo
                echo '<div class="pseudo_depense">' . $user->getPseudo() . '</div>';

                // Avatar
                $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';

                // Identifiant (caché)
                echo '<input type="hidden" name="identifiant_montant[' . $user->getId() . ']" value="' . $user->getIdentifiant() . '" />';

                // Montant
                echo '<div class="zone_montant">';
                  if ($savedAmounts == true)
                    echo '<input type="text" name="montant_user[' . $user->getId() . ']" maxlength="6" value="' . $_SESSION['save']['tableau_montants'][$user->getIdentifiant()] . '" class="montant" />';
                  else
                    echo '<input type="text" name="montant_user[' . $user->getId() . ']" maxlength="6" value="" class="montant" />';

                  // Symbole
                  echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="euros" class="euro_saisie" />';
                echo '</div>';
              echo '</div>';
            }
          echo '</div>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>