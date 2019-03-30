<?php
	echo '<div class="title_gestion">Demandes de suppression de film</div>';

	echo '<table class="table_manage_users" style="	margin-bottom: 0;">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 25%;">';
				echo 'Film';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 30%;">';
				echo 'Suppression du film';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Demande suppression par';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Ajouté par';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Personnes intéressées';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Accepter';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Refuser';
			echo '</td>';
		echo '</tr>';

    if (!empty($listeSuppression))
    {
      foreach ($listeSuppression as $film)
      {
        echo '<tr class="tr_manage_users">';
  				echo '<td class="td_manage_users">';
  					echo $film->getFilm();
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($film->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_films.php?delete_id=' . $film->getId() . '&action=doDeleteFilm">';
    						echo '<input type="submit" name="accepter_suppression_film" value="ACCEPTER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($film->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_films.php?delete_id=' . $film->getId() . '&action=doResetFilm">';
    						echo '<input type="submit" name="annuler_suppression_film" value="REFUSER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

					echo '<td class="td_manage_users">';
						echo $film->getPseudo_del() . ' (' . $film->getIdentifiant_del() . ')';
					echo '</td>';

					echo '<td class="td_manage_users">';
						echo $film->getPseudo_add() . ' (' . $film->getIdentifiant_add() . ')';
					echo '</td>';

					echo '<td class="td_manage_users">';
						echo $film->getNb_users();
					echo '</td>';
  			echo '</tr>';
      }
    }
    else
		{
			echo '<tr>';
				echo '<td colspan="6" class="td_manage_users" style="line-height: 100px;">Pas de films à supprimer !</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="5" class="td_manage_users">';
        if ($alerteFilms == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
