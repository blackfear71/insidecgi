<?php
  echo '<div class="titre_section"><img src="../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Mes informations</div></div>';

  // Avatar actuel & suppression
  echo '<div class="zone_profil_avatar_parametres">';
    if (!empty($profil->getAvatar()))
      echo '<img src="../includes/images/profil/avatars/' . $profil->getAvatar() . '" alt="avatar" title="' . $profil->getPseudo() . '" class="avatar_profil" />';
    else
      echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $profil->getPseudo() . '" class="avatar_profil" />';

    echo '<div class="texte_parametres">Avatar actuel</div>';

    echo '<form method="post" action="profil.php?action=doSupprimerAvatar" enctype="multipart/form-data">';
      echo '<input type="submit" name="delete_avatar" value="Supprimer" class="bouton_validation" />';
    echo '</form>';
  echo '</div>';

  // Modification avatar
  echo '<form method="post" action="profil.php?action=doModifierAvatar" enctype="multipart/form-data" class="form_update_avatar">';
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

    echo '<span class="zone_parcourir_avatar">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_avatar loadAvatar" required /></span>';

    echo '<div class="mask_avatar">';
      echo '<img id="avatar" alt="" class="avatar_update_profil" />';
    echo '</div>';

    echo '<input type="submit" name="post_avatar" value="Modifier l\'avatar" class="bouton_validation" />';
  echo '</form>';

  // Mise à jour informations
  echo '<form method="post" action="profil.php?action=doUpdateInfos" class="form_update_infos">';
    // Pseudo
    echo '<img src="../includes/icons/common/inside_red.png" alt="inside_red" class="logo_parametres" />';
    echo '<input type="text" name="pseudo" placeholder="Pseudo" value="' . $profil->getPseudo() . '" maxlength="255" class="monoligne_saisie" />';

    // Email
    echo '<img src="../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_parametres" />';
    echo '<input type="email" name="email" placeholder="Adresse mail" value="' . $profil->getEmail() . '" maxlength="255" class="monoligne_saisie" />';

    // Anniversaire
    echo '<img src="../includes/icons/profil/anniversary_grey.png" alt="anniversary_grey" class="logo_parametres" />';
    echo '<input type="text" name="anniversaire" placeholder="Anniversaire (jj/mm/yyyy)" value="' . formatDateForDisplay($profil->getAnniversary()) . '" maxlength="10" id="datepicker_anniversary" class="monoligne_saisie" />';

    echo '<input type="submit" name="saisie_pseudo" value="Mettre à jour" class="bouton_validation" />';
  echo '</form>';
?>
