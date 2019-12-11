<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "MH";
      $style_head      = "styleMH.css";
      $script_head     = "scriptMH.js";
      $datepicker_head = true;
      $chat_head       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Onglets -->
		<header>
			<?php
        $title= "Movie House";

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
				<?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          /*********************/
          /* Modification film */
          /*********************/
          include('vue/vue_saisie_film.php');

          /*********************/
          /* Affichage détails */
          /*********************/
          if ($filmExistant == true)
          {
            // Bandeau avec poster
            echo '<div class="bandeau_details">';
              // Lien film précédent
              if (!empty($listeNavigation['previous']['id']) AND !empty($listeNavigation['previous']['film']))
              {
                echo '<a href="details.php?id_film=' . $listeNavigation['previous']['id'] . '&action=goConsulter" class="link_prev_movie"><img src="../../includes/icons/moviehouse/left.png" alt="left" class="fleche_detail padding_right" /></a>';
                echo '<span class="titre_prev_movie">' . $listeNavigation['previous']['film'] . '</span>';
              }
              else
                echo '<div class="link_prev_movie_empty"></div>';

              // Lien film suivant
              if (!empty($listeNavigation['next']['id']) AND !empty($listeNavigation['next']['film']))
              {
                echo '<a href="details.php?id_film=' . $listeNavigation['next']['id'] . '&action=goConsulter" class="link_next_movie"><img src="../../includes/icons/moviehouse/right.png" alt="right" class="fleche_detail padding_left" /></a>';
                echo '<span class="titre_next_movie">' . $listeNavigation['next']['film'] . '</span>';
              }
              else
                echo '<div class="link_next_movie_empty"></div>';

              // Fond
              if (!empty($detailsFilm->getPoster()))
                echo '<img src="' . $detailsFilm->getPoster() . '" alt="' . $detailsFilm->getPoster() . '" title="' . $detailsFilm->getFilm() . '" class="bandeau_poster" />';
              else
                echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="bandeau_poster" />';

              // Titre du film
              echo '<div class="titre_film_details">' . $detailsFilm->getFilm() . '</div>';
            echo '</div>';

            /***********/
            /* Détails */
            /***********/
            echo '<div class="zone_details">';
              // Poster, actions & intéressés
              echo '<div class="zone_details_left">';
                // Poster
                echo '<div class="zone_details_poster">';
                  if (!empty($detailsFilm->getPoster()))
                    echo '<img src="' . $detailsFilm->getPoster() . '" alt="' . $detailsFilm->getPoster() . '" title="' . $detailsFilm->getFilm() . '" class="img_details" />';
                  else
                    echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="img_details" />';
                echo '</div>';

                // Actions
                echo '<div class="zone_details_actions">';
                  // Modifier
                  echo '<a id="modifierFilm" class="link_details">';
                    echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" title="Modifier le film" class="icon_details" />';
                  echo '</a>';

                  // Supprimer
                  echo '<form id="delete_film" method="post" action="details.php?action=doSupprimer" class="link_details">';
                    echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                    echo '<input type="submit" name="delete_film" value="" title="Demander la suppression" class="icon_details_delete eventConfirm" />';
                    echo '<input type="hidden" value="Demander la suppression de ce film ?" class="eventMessage" />';
                  echo '</form>';

                  // Mailing (si on participe)
                  if ($detailsFilm->getStars_user() > 0)
                  {
                    echo '<a href="mailing.php?id_film=' . $detailsFilm->getId() . '&action=goConsulter" class="link_details">';
                      echo '<img src="../../includes/icons/moviehouse/mailing_red.png" alt="mailing" title="Envoyer mail" class="icon_details" />';
                    echo '</a>';
                  }

                  // Doodle
                  if (!empty($detailsFilm->getDoodle()))
                  {
                    echo '<a href="' . $detailsFilm->getDoodle() . '" target="_blank" class="link_details">';
                      echo '<img src="../../includes/icons/moviehouse/doodle.png" alt="doodle" title="Doodle" class="icon_details" />';
                    echo '</a>';
                  }
                  else
                  {
                    echo '<a id="doodleFilm" href="https://doodle.com/fr/" target="_blank" class="link_details">';
                      echo '<img src="../../includes/icons/moviehouse/doodle_none.png" alt="doodle_none" title="Doodle" class="icon_details" />';
                    echo '</a>';
                  }

                  // Fiche du film
                  if (!empty($detailsFilm->getLink()))
                  {
                    echo '<a href="' . $detailsFilm->getLink() . '" target="_blank" class="link_details">';
                      echo '<img src="../../includes/icons/moviehouse/pellicule.png" alt="pellicule" title="Fiche du film" class="icon_details" />';
                    echo '</a>';
                  }
                echo '</div>';

                // Vote utilisateur
                echo '<div class="zone_details_vote_user">';
                  echo '<form method="post" action="details.php?action=doVoterFilm" class="form_vote_left">';
                    echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';

                    // Etoiles utilisateur
                    for ($j = 0; $j <= 5; $j++)
                    {
                      if ($j == $detailsFilm->getStars_user())
                      {
                        echo '<img src="../../includes/icons/moviehouse/stars/star' . $j .'.png" alt="star' . $j . '" class="star_vote rounded" />';
                        echo '<input type="submit" name="preference_' . $j . '" value="" class="input_vote" />';
                      }
                      else
                      {
                        echo '<img src="../../includes/icons/moviehouse/stars/star' . $j .'.png" alt="star' . $j . '" class="star_vote" />';
                        echo '<input type="submit" name="preference_' . $j . '" value="" class="input_vote" />';
                      }
                    }
                  echo '</form>';

                  // Si l'utilisateur a des étoiles
                  if ($detailsFilm->getStars_user() > 0)
                  {
                    echo '<form method="post" action="details.php?action=doParticiperFilm" class="form_vote_right">';
                      echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';

                      // Participation
                      if ($detailsFilm->getParticipation() == "P")
                      {
                        echo '<img src="../../includes/icons/moviehouse/participate.png" alt="participate" class="star_vote rounded" />';
                        echo '<input type="submit" name="participate" class="input_vote" />';
                      }
                      else
                      {
                        echo '<img src="../../includes/icons/moviehouse/participate.png" alt="participate" class="star_vote" />';
                        echo '<input type="submit" name="participate" class="input_vote" />';
                      }

                      // Vue
                      if ($detailsFilm->getParticipation() == "S")
                      {
                        echo '<img src="../../includes/icons/moviehouse/seen.png" alt="seen" class="star_vote rounded" />';
                        echo '<input type="submit" name="seen" class="input_vote" />';
                      }
                      else
                      {
                        echo '<img src="../../includes/icons/moviehouse/seen.png" alt="seen" class="star_vote" />';
                        echo '<input type="submit" name="seen" class="input_vote" />';
                      }

                    echo '</form>';
                  }
                echo '</div>';

                // Personnes intéressées
                echo '<div class="zone_details_votes">';
                  // Titre
                  echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/users_grey.png" alt="users_grey" class="logo_titre_section" /><div class="texte_titre_section">Personnes intéressées</div></div>';

                  // Liste des étoiles
                  if (!empty($listeEtoiles))
                  {
                    foreach ($listeEtoiles as $etoiles)
                    {
                      if ($etoiles->getParticipation() == "S")
                        echo '<div class="zone_etoiles_user seen_interested">';
                      elseif ($etoiles->getParticipation() == "P")
                        echo '<div class="zone_etoiles_user participate_interested">';
                      elseif ($etoiles->getIdentifiant() == $_SESSION['user']['identifiant'])
                        echo '<div class="zone_etoiles_user current_user_interested">';
                      else
                        echo '<div class="zone_etoiles_user">';

                        // Avatar
                        $avatarFormatted = formatAvatar($etoiles->getAvatar(), $etoiles->getPseudo(), 2, "avatar");

                        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_interested" />';

                        // Pseudo
                        echo '<div class="pseudo_interested">' . $etoiles->getPseudo() . '</div>';

                        // Etoiles
                        echo '<img src="../../includes/icons/moviehouse/stars/star' . $etoiles->getStars() . '.png" alt="star' . $etoiles->getStars() . '" class="star_interested" />';
                      echo '</div>';
                    }
                  }
                  else
                    echo '<div class="empty">Aucune personne encore intéressée</div>';
                echo '</div>';
              echo '</div>';

              // Détails film
              echo '<div class="zone_details_right">';
                // Vidéo
                if (!empty($detailsFilm->getId_url()))
                {
                  echo '<div class="video_container">';
                    $exp = explode(':_:', $detailsFilm->getId_url());
                    $html = "";

                    switch ($exp[0])
                    {
                      case 'youtube':
                        $html = '<iframe src="http://www.youtube.com/embed/' . $exp[1] . '" allowfullscreen class="video"></iframe>';
                        break;

                      case 'vimeo':
                        $html = '<iframe src="http://player.vimeo.com/video/'.$exp[1].'" allowFullScreen class="video"></iframe>';
                        break;

                      case 'dailymotion':
                        $html = '<iframe src="http://www.dailymotion.com/embed/video/'.$exp[1].'" allowfullscreen class="video"></iframe>';
                        break;

                      default:
                        break;
                    }

                    echo $html;
                  echo '</div>';
                }

                // Synopsis
                if (!empty($detailsFilm->getSynopsis()))
                {
                  // Titre
                  echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" /><div class="texte_titre_section">Synopsis</div></div>';
                  echo '<div class="contenu_synopsis">' . nl2br($detailsFilm->getSynopsis()) . '</div>';
                }

                // Date sortie cinéma
                echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">Sortie au cinéma</div></div>';
                echo '<div class="contenu_detail">';
                  if (isBlankDate($detailsFilm->getDate_theater()))
                    echo 'N.C.';
                  else
                    echo formatDateForDisplay($detailsFilm->getDate_theater());
                echo '</div>';

                // Date sortie DVD / Bluray
                echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">Sortie en DVD / Bluray</div></div>';
                echo '<div class="contenu_detail">';
                  if (!empty($detailsFilm->getDate_release()))
                    echo formatDateForDisplay($detailsFilm->getDate_release());
                  else
                    echo '-';
                echo '</div>';

                // Date Doodle proposée
                echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/doodle_grey.png" alt="doodle_grey" class="logo_titre_section" /><div class="texte_titre_section">Sortie proposée</div></div>';
                echo '<div class="contenu_detail">';
                  if (!empty($detailsFilm->getDate_doodle()))
                  {
                    echo formatDateForDisplay($detailsFilm->getDate_doodle());

                    if (!empty($detailsFilm->getTime_doodle()))
                    {
                      $heure_doodle   = substr($detailsFilm->getTime_doodle(), 0, 2);
                      $minutes_doodle = substr($detailsFilm->getTime_doodle(), 2, 2);

                      echo ' à ' . $heure_doodle . ':' . $minutes_doodle;
                    }
                  }
                  else
                    echo '-';
                echo '</div>';

                // Restaurant
                echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/restaurant.png" alt="restaurant" class="logo_titre_section" /><div class="texte_titre_section">Restaurant</div></div>';
                echo '<div class="contenu_detail">';
                  switch ($detailsFilm->getRestaurant())
                  {
                    case "B":
                      echo 'Avant';
                      if (!empty($detailsFilm->getPlace()))
                        echo ' : ' . $detailsFilm->getPlace();
                      break;

                    case "A":
                      echo 'Après';
                      if (!empty($detailsFilm->getPlace()))
                        echo ' : ' . $detailsFilm->getPlace();
                      break;

                    case "N":
                    default:
                      echo 'Aucun';
                      break;
                  }
                echo '</div>';
              echo '</div>';
            echo '</div>';

            // Commentaires
            echo '<div class="zone_commentaires">';
              // Titre
              echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/comments_grey.png" alt="comments_grey" class="logo_titre_section" /><div class="texte_titre_section">Commentaires</div></div>';

              if (!empty($listeCommentaires))
              {
                echo '<div class="zone_commentaires_users">';
                  // Affichage des commentaires
                  foreach ($listeCommentaires as $comment)
                  {
                    echo '<div class="zone_commentaire_user" id="' . $comment->getId() . '">';
                      // Avatar
                      echo '<div class="zone_avatar_commentaire">';
                        if (!empty($comment->getPseudo()))
                          $avatarFormatted = formatAvatar($comment->getAvatar(), $comment->getPseudo(), 2, "avatar");
                        else
                          $avatarFormatted = formatAvatar($comment->getAvatar(), "Un ancien utilisateur", 2, "avatar");

                        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_comments" />';
                      echo '</div>';

                      echo '<div class="infos_commentaire_user">';
                        // Pseudo
                        if (!empty($comment->getPseudo()))
                          echo '<div class="pseudo_comments_films">' . $comment->getPseudo() . '</div>';
                        else
                          echo '<div class="pseudo_comments_films"><i>Un ancien utilisateur</i></div>';

                        // Date et heure
                        echo '<div class="date_comments_films">Le ' . formatDateForDisplay($comment->getDate()) . ' à ' . formatTimeForDisplay($comment->getTime()) . '</div>';
                      echo '</div>';

                      // Actions sur commentaires seulement si l'auteur correspond à l'utilisateur connecté
                      if ($comment->getAuthor() == $_SESSION['user']['identifiant'])
                      {
                        /***************************************************/
                        /* Ligne visualisation normale (sans modification) */
                        /***************************************************/
                        // Boutons
                        echo '<div id="actions_comment_' . $comment->getId() . '" class="actions_commentaires">';
                          // Modification commentaire
                          echo '<span class="link_actions_commentaires">';
                            echo '<a id="modifier_commentaire_' . $comment->getId() . '" title="Modifier le commentaire" class="icone_modifier_comment modifierCommentaire"></a>';
                          echo '</span>';

                          // Suppression commentaire
                          echo '<form id="delete_comment_' . $comment->getId() . '" method="post" action="details.php?action=doSupprimerCommentaire" class="link_actions_commentaires">';
                            echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                            echo '<input type="hidden" name="id_comment" value="' . $comment->getId() . '" />';
                            echo '<input type="submit" name="delete_comment" value="" title="Supprimer le commentaire" class="icone_supprimer_comment eventConfirm" />';
                            echo '<input type="hidden" value="Supprimer ce commentaire ?" class="eventMessage" />';
                          echo '</form>';
                        echo '</div>';

                        // Commentaire
                        echo '<div id="visualiser_comment_' . $comment->getId() . '">';
                          // On cherche les smileys dans les commentaires
                          $commentaire = extract_smiley($comment->getComment());

                          // On cherche les liens dans les commentaires
                          $commentaire = extract_link(nl2br($commentaire));

                          // Commentaire
                          echo '<div class="texte_commentaire">' . $commentaire . '</div>';
                        echo '</div>';

                        /**********************************/
                        /* Ligne cachée pour modification */
                        /**********************************/
                        echo '<form method="post" action="details.php?action=doModifierCommentaire" id="modifier_comment_' . $comment->getId() . '" style="display: none;">';
                          // Boutons
                          echo '<div class="actions_commentaires">';
                            // Validation modification
                            echo '<span class="link_actions_commentaires">';
                              echo '<input type="submit" name="modify_comment" value="" title="Valider la modification" class="icone_valider_comment" />';
                            echo '</span>';

                            // Annulation modification
                            echo '<span class="link_actions_commentaires">';
                              echo '<a id="annuler_commentaire_' . $comment->getId() . '" title="Annuler la modification" class="icone_annuler_comment annulerCommentaire"></a>';
                            echo '</span>';
                          echo '</div>';

                          // Formulaire
                          echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                          echo '<input type="hidden" name="id_comment" value="' . $comment->getId() . '" />';

                          echo '<textarea placeholder="Votre commentaire ici..." name="comment" id="textarea_comment_' . $comment->getId() . '" class="zone_modification_commentaire" required>' . $comment->getComment() . '</textarea>';

                          echo '<div class="zone_saisie_smileys">';
                            echo '<a id="modifier_smiley_1_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/1.png" alt="smile" title=":)" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_2_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/2.png" alt="smile" title=";)" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_3_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/3.png" alt="smile" title=":(" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_4_' . $comment->getId() . '" class="modifierSmiley""><img src="../../includes/icons/common/smileys/4.png" alt="smile" title=":|" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_5_' . $comment->getId() . '" class="modifierSmiley""><img src="../../includes/icons/common/smileys/5.png" alt="smile" title=":D" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_6_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/6.png" alt="smile" title=":O" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_7_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/7.png" alt="smile" title=":P" class="smiley_2" /></a>';
                            echo '<a id="modifier_smiley_8_' . $comment->getId() . '" class="modifierSmiley"><img src="../../includes/icons/common/smileys/8.png" alt="smile" title=":facepalm:" class="smiley_2" /></a>';
                          echo '</div>';
                        echo '</form>';
                      }
                      // Affichage commentaire normal
                      else
                      {
                        // On cherche les smileys dans les commentaires
                        $commentaire = extract_smiley($comment->getComment());

                        // On cherche les liens dans les commentaires
                        $commentaire = extract_link(nl2br($commentaire));

                        // Commentaire
                        echo '<div class="texte_commentaire">' . $commentaire . '</div>';
                      }
                    echo '</div>';
                  }
                echo '</div>';
              }

              // Saisie commentaire
  						echo '<form method="post" action="details.php?action=doCommenter" id="comments" class="saisie_commentaires_films">';
  							echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';

                echo '<textarea placeholder="Votre commentaire ici..." name="comment" id="textarea_comment_0" class="zone_saisie_comment" required></textarea>';
  							echo '<input type="submit" name="submit_comment" value="Envoyer" class="bouton_commentaires" />';

                echo '<div class="zone_saisie_smileys">';
                	echo '<a id="modifier_smiley_1_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/1.png" alt="smile" title=":)" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_2_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/2.png" alt="smile" title=";)" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_3_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/3.png" alt="smile" title=":(" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_4_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/4.png" alt="smile" title=":|" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_5_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/5.png" alt="smile" title=":D" class="smiley_2" /></a>';
                  echo '<a id="modifier_smiley_6_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/6.png" alt="smile" title=":O" class="smiley_2" /></a>';
                  echo '<a id="modifier_smiley_7_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/7.png" alt="smile" title=":P" class="smiley_2" /></a>';
                	echo '<a id="modifier_smiley_8_0" class="ajouterSmiley"><img src="../../includes/icons/common/smileys/8.png" alt="smile" title=":facepalm:" class="smiley_2" /></a>';
                echo '</div>';
  						echo '</form>';
            echo '</div>';
          }
				?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération liste dépenses pour le script
      var detailsFilm = <?php echo $detailsFilmJson; ?>;
    </script>
  </body>
</html>
