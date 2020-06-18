<?php
  // Numéro de version
  $version = '2.2';

  // Version
  echo '<div class="version">v' . $version . '</div>';

  // Zone footer
  echo '<div class="zone_footer_right">';
    // Affichage switch version sur mobile
    if ($_SESSION['index']['plateforme'] == 'mobile')
    {
      echo '<a href="/inside/includes/functions/switch_mobile.php" class="link_footer" title="Basculer vers la version classique">';
        echo '<img src="/inside/includes/icons/common/classic.png" alt="classic" title="Basculer vers la version classique" class="icone_footer" />';
      echo '</a>';
    }

    // Copyright
    echo '<div class="copyright">© 2017-' . date("Y") . ' Inside</div>';
  echo '</div>';
?>
