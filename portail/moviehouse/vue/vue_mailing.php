<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "MH";
      $style_head  = "styleMH.css";
      $script_head = "";

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php
        $title= "Movie House";

        include('../../includes/header.php');
        include('../../includes/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
          $add_film    = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
        <?php
          $modele_mail = getModeleFilm($detailsFilm, $listeEtoiles);
          echo $modele_mail;

          // Encadré destinataires
          echo '<div class="zone_destinataires_mail">';
            $email_present = false;

            foreach ($listeEtoiles as $participant)
            {
              if (!empty($participant->getEmail()))
              {
                if ($email_present == false)
                {
                  echo 'L\'email sera envoyé aux personnes suivantes :<br />';
                  $email_present = true;
                }
                echo '<p class="destinataires">';
                  if (!empty($participant->getAvatar()))
                    echo '<img src="../../profil/avatars/' . $participant->getAvatar() . '" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest" />';
                  else
                    echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest" />';

                  echo $participant->getPseudo();
                echo '</p>';
              }
            }

            if ($email_present == false)
              echo '<p class="avertissement_mail" style="margin-top: 0;">Aucune personne ne sera avertie car aucun email n\'a été renseigné.</p>';
            else
              echo '<p class="avertissement_mail">N\'oubliez pas d\'avertir les éventuelles personnes n\'ayant pas renseigné d\'adresse mail.</p>';
          echo '</div>';

          // Bouton envoi mail
          if ($email_present == true)
          {
            echo '<form method="post" action="mailing.php?id_film=' . $_GET['id_film'] . '&action=sendMail">';
              echo '<input type="submit" name="send_mail_film" value="Envoyer l\'e-mail" class="send_mail_film" />';
            echo '</form>';
          }
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
