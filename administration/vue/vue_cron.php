<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptAdmin.js"></script>

		<title>Inside - CRON</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Tâches CRON";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
          $back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article>
        <div class="zone_cron_asynchrone">
  				<div class="level_succes" style="margin-top: 0;">Lancement asynchrone des tâches CRON</div>

          <div class="zone_cron" style="float: left;">
            <div class="titre_cron">
              CRON journalier
            </div>
            <div class="contenu_cron">
              Exécute les tâches suivantes :<br />
              <ul>
                <li>Recherche les sorties cinéma du jour et insère une notification</li>
                <li>Mise à jour des succès pour tous les utilisateurs (à venir)</li>
                <li>Notification début et fin de mission</li>
                <li>Génération log journalier</li>
              </ul>
              <u>Fréquence :</u> tous les jours à 7h.
            </div>
            <div class="boutons_cron">
              <form method="post" action="../cron/daily_cron.php">
                <input type="submit" name="daily_cron" value="Lancer" class="bouton_cron" />
              </form>
            </div>
          </div>

          <div class="zone_cron" style="float: right;">
            <div class="titre_cron">
              CRON hebdomadaire
            </div>
            <div class="contenu_cron">
              Exécute les tâches suivantes :<br />
              <ul>
                <li>Remise à plat des bilans des dépenses</li>
                <li>Recherche du plus dépensier et du moins dépensier et insère une notification (à venir)</li>
                <li>Sauvegarde automatique de la base de données (à venir)</li>
                <li>Génération log hebdomadaire</li>
              </ul>
              <u>Fréquence :</u> tous les lundis à 7h.
            </div>
            <div class="boutons_cron">
              <form method="post" action="../cron/weekly_cron.php">
                <input type="submit" name="weekly_cron" value="Lancer" class="bouton_cron" />
              </form>
            </div>
          </div>
        </div>

        <div class="zone_cron_logs">
          <!-- Logs journaliers -->
          <div class="zone_jlog">
            <div class="level_succes">Logs journaliers</div>
            <div class="zone_logs">
              <?php
                if (!empty($files['daily']))
                {
                  $i = 0;

                  foreach ($files['daily'] as $fileJ)
                  {
                    $lines = file('../cron/logs/daily/' . $fileJ);

                    // Tableau statut / titre / flèche
                    echo '<table class="zone_log">';
                      echo '<tr>';
                        if (substr($lines[6], 30, 2) == "OK")
                          echo '<td class="log_ok">OK</td>';
                        else
                          echo '<td class="log_ko">KO</td>';

                        echo '<td class="titre_log">';
                          echo $fileJ;
                        echo '</td>';

                        echo '<td class="voir_log">';
                          echo '<a onclick="afficherMasquer(\'logI' . $i . '\'); rotateIcon(\'rotateI' . $i . '\');">';
                            echo '<img src="../includes/icons/see_log.png" alt="see_log" class="see_log" style="transform: rotate(0deg);" id="rotateI' . $i . '" />';
                          echo '</a>';
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';

                    // Log
                    echo '<div class="log" id="logI' . $i . '" style="display: none;">';
                      foreach ($lines as $line)
                      {
                        echo nl2br($line);
                      }
                    echo '</div>';

                    $i++;
                  }
                }
                else
                {
                  echo '<div class="zone_no_logs">';
                    echo '<div class="titre_no_logs">';
                      echo 'Pas encore de logs journaliers';
                    echo '</div>';
                    echo '<div class="contenu_no_logs">';
                      echo 'Aucun log journalier n\'a encore été généré par les tâches CRON. Veuillez patienter que des tâches soient exécutées automatiquement ou bien lancez-les manuellement.<br />';
                    echo '</div>';
                  echo '</div>';
                }
              ?>
            </div>
          </div>

          <!-- Logs hebdomadaires -->
          <div class="zone_hlog">
            <div class="level_succes">Logs hebdomadaires</div>
            <div class="zone_logs">
              <?php
                if (!empty($files['weekly']))
                {
                  $j = 0;

                  foreach ($files['weekly'] as $fileH)
                  {
                    $lines = file('../cron/logs/weekly/' . $fileH);

                    // Tableau statut / titre / flèche
                    echo '<table class="zone_log">';
                      echo '<tr>';
                        if (substr($lines[6], 30, 2) == "OK")
                          echo '<td class="log_ok">OK</td>';
                        else
                          echo '<td class="log_ko">KO</td>';

                        echo '<td class="titre_log">';
                          echo $fileH;
                        echo '</td>';

                        echo '<td class="voir_log">';
                          echo '<a onclick="afficherMasquer(\'logJ' . $j . '\'); rotateIcon(\'rotateJ' . $j . '\');">';
                            echo '<img src="../includes/icons/see_log.png" alt="see_log" class="see_log" style="transform: rotate(0deg);" id="rotateJ' . $j . '" />';
                          echo '</a>';
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';

                    // Log
                    echo '<div class="log" id="logJ' . $j . '" style="display: none;">';
                      foreach ($lines as $line)
                      {
                        echo nl2br($line);
                      }
                    echo '</div>';

                    $j++;
                  }
                }
                else
                {
                  echo '<div class="zone_no_logs">';
                    echo '<div class="titre_no_logs">';
                      echo 'Pas encore de logs hebdomadaires';
                    echo '</div>';
                    echo '<div class="contenu_no_logs">';
                      echo 'Aucun log hebdomadaire n\'a encore été généré par les tâches CRON. Veuillez patienter que des tâches soient exécutées automatiquement ou bien lancez-les manuellement.<br />';
                    echo '</div>';
                  echo '</div>';
                }
              ?>
            </div>
          </div>
        </div>

        <div class="clear"></div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
