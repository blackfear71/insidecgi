<?php
  /*******************************/
  /*** Zone de saisie de choix ***/
  /*******************************/
  echo '<div id="zone_saisie_choix" class="fond_saisie_restaurant">';
    echo '<div id="zone_marge_choix" class="zone_saisie_choix">';
      // Titre
      echo '<div class="titre_saisie_restaurant">Proposer où manger</div>';

      // Bouton fermeture
      echo '<a onclick="afficherMasquer(\'zone_saisie_choix\');" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      // Saisie restaurant initale
      echo '<form method="post" action="foodadvisor.php?action=doAjouter" class="form_choices">';
        echo '<div id="zone_choix">';
          // Titre
          echo '<div class="titre_choix">Proposition 1</div>';

          // Choix restaurant
          echo '<div id="zone_listbox_restaurant_1" class="zone_listbox">';
            echo '<a id="choix_restaurant_1" onclick="afficherMasquerNoDelay(\'choix_restaurant_1\'); afficherListboxLieux(\'zone_listbox_restaurant_1\');" class="bouton_choix">';
              echo '<span class="fond_plus">+</span>';
              echo 'Restaurant';
            echo '</a>';
          echo '</div>';

          // Choix horaire
          echo '<div id="zone_listbox_horaire_1" class="zone_listbox">';
            echo '<a id="choix_horaire_1" onclick="afficherMasquerNoDelay(\'choix_horaire_1\'); afficherListboxHoraires(\'zone_listbox_horaire_1\', \'choix_horaire_1\', \'create\', \'\');" class="bouton_choix">';
              echo '<span class="fond_plus">+</span>';
              echo 'Horaire';
            echo '</a>';
          echo '</div>';

          // Choix transports
          echo '<div id="zone_checkbox_transports_1" class="zone_listbox">';
            echo '<a id="choix_transports_1" onclick="afficherMasquerNoDelay(\'choix_transports_1\'); afficherCheckboxTransports(\'zone_checkbox_transports_1\');" class="bouton_choix">';
              echo '<span class="fond_plus">+</span>';
              echo 'Transports';
            echo '</a>';
          echo '</div>';

          // Menu
          echo '<div id="zone_saisie_menu_1" class="zone_listbox">';
            echo '<a id="choix_menu_1" onclick="afficherMasquerNoDelay(\'choix_menu_1\'); afficherSaisieMenu(\'zone_saisie_menu_1\');" class="bouton_choix">';
              echo '<span class="fond_plus">+</span>';
              echo 'Menu';
            echo '</a>';
          echo '</div>';

          // Séparation
          echo '<div class="separation_choix"></div>';
        echo '</div>';

        echo '<div class="zone_boutons">';
          // Ajout autre saisie
          echo '<a id="saisie_autre_choix" onclick="addChoice(\'zone_choix\', \'zone_marge_choix\');" class="bouton_autre_choix">';
            echo '<span class="fond_plus">+</span>';
            echo 'Ajouter une autre proposition';
          echo '</a>';

          // Validation
          echo '<input type="submit" name="submit_choices" value="Soumettre les propositions" class="bouton_validation_choix" />';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
