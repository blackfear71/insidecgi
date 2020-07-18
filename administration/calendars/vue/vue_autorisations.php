<?php
  echo '<div class="titre_section"><img src="../../includes/icons/admin/download_grey.png" alt="download_grey" class="logo_titre_section" /><div class="texte_titre_section">Autorisations de gestion des calendriers</div></div>';

  echo '<form method="post" action="calendars.php?action=doUpdateAutorisations" class="form_autorisations">';
    echo '<div class="zone_autorisations">';
      foreach ($listeAutorisations as $autorisation)
      {
        if ($autorisation->getManage_calendars() == 'Y')
        {
          echo '<div id="bouton_autorisation_' . $autorisation->getIdentifiant() . '" class="switch_autorisation switch_checked">';
            echo '<input id="autorisation_' . $autorisation->getIdentifiant() . '" type="checkbox" name="autorization[' . $autorisation->getIdentifiant() . ']" checked />';
            echo '<label for="autorisation_' . $autorisation->getIdentifiant() . '" class="label_switch">' . $autorisation->getPseudo() . '</label>';
          echo '</div>';
        }
        else
        {
          echo '<div id="bouton_autorisation_' . $autorisation->getIdentifiant() . '" class="switch_autorisation">';
            echo '<input id="autorisation_' . $autorisation->getIdentifiant() . '" type="checkbox" name="autorization[' . $autorisation->getIdentifiant() . ']" />';
            echo '<label for="autorisation_' . $autorisation->getIdentifiant() . '" class="label_switch">' . $autorisation->getPseudo() . '</label>';
          echo '</div>';
        }
      }
    echo '</div>';

    echo '<input type="submit" name="saisie_autorisations" value="Mettre à jour" class="saisie_autorisations" />';
  echo '</form>';
?>
