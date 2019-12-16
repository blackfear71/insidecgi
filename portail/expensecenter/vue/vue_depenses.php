<?php
  /****************/
  /*** Dépenses ***/
  /****************/
  echo '<div class="zone_expenses_right">';
    // Dépenses
    echo '<div class="titre_section"><img src="../../includes/icons/expensecenter/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_section" /><div class="texte_titre_section">Les dépenses</div></div>';

    if (!empty($listeDepenses))
    {
      foreach ($listeDepenses as $depense)
      {
        echo '<div class="zone_depense" id="' . $depense->getId() . '">';
          echo '<div id="zone_shadow_' . $depense->getId() . '" class="zone_shadow">';
            // Partie supérieure
            echo '<div class="zone_depense_top">';
              echo '<div class="zone_achat">';
                // Avatar acheteur
                $avatarFormatted = formatAvatar($depense->getAvatar(), $depense->getPseudo(), 2, "avatar");

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar" />';

                // Pseudo acheteur
                echo '<div class="pseudo_achat">' . formatUnknownUser($depense->getPseudo(), true, true) . '</div>';

                // Date achat
                echo '<div class="date_achat">' . formatDateForDisplay($depense->getDate()) . '</div>';

                // Prix
                echo '<div class="valeur_achat">' . formatBilanForDisplay($depense->getPrice()) . '</div>';
              echo '</div>';

              // Liste des parts utilisateurs
              echo '<div class="zone_parts">';
                if (!empty($depense->getParts()))
                {
                  foreach ($depense->getParts() as $parts)
                  {
                    echo '<div class="zone_parts_utilisateur">';
                      // Avatar utilisateur
                      $avatarFormatted = formatAvatar($parts->getAvatar(), $parts->getPseudo(), 2, "avatar");

                      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';

                      // Nombre de parts
                      echo '<div class="parts_depense">' . $parts->getParts() . '</div>';
                    echo '</div>';
                  }
                }
                else
                  echo '<div class="regularisation">Régularisation</div>';
              echo '</div>';
            echo '</div>';

            // Partie inférieure
            echo '<div class="zone_depense_bottom">';
              // Commentaire
              echo '<div class="commentaire_depense">' . nl2br($depense->getComment()) . '</div>';

              // Modifier
              echo '<a id="modifier_' . $depense->getId() . '" title="Modifier" class="lien_depense modifierDepense"><img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="icone_depense" /></a>';

              // Supprimer
              echo '<form id="delete_expense_' . $depense->getId() . '" method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doSupprimer" class="form_supprimer_depense">';
                echo '<input type="hidden" name="id_expense" value="' . $depense->getId() . '" />';
                echo '<input type="submit" name="delete_depense" value="" title="Supprimer" class="icone_supprimer_depense eventConfirm" />';
                echo '<input type="hidden" value="Supprimer la dépense de ' . formatOnclick($depense->getPseudo()) . ' du ' . formatDateForDisplay($depense->getDate()) . ' et d\'un montant de ' . $depense->getPrice() . ' € ?" class="eventMessage" />';
              echo '</form>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas de dépenses pour cette année...</div>';
  echo '</div>';
?>
