<?php
  if (!empty($listeBugs))
  {
    foreach ($listeBugs as $bug)
    {
      // Libellé type
      if ($bug->getType() == "B")
        $type_bug = "Bug";
      elseif ($bug->getType() == "E")
        $type_bug = "Evolution";;

      // Libellé état
      if ($bug->getResolved() == "Y")
        $etat_bug = '<span style="color: green;">Résolu</span>';
      else
        $etat_bug = '<span style="color: red;">En cours</span>';

      // Formatage date
      $date_bug = formatDateForDisplay($bug->getDate());

      // Affichage des idées
  		echo '<table class="table_bugs">';
  			echo '<tr id="' . $bug->getId() . '">';
  				// Titre idée
  				echo '<td class="td_ideas_title">';
						echo $type_bug;
  				echo '</td>';

  				echo '<td class="td_ideas_content">';
  					echo $bug->getSubject();
  				echo '</td>';

  				// Date
  				echo '<td class="td_ideas_title">';
  					echo 'Date';
  				echo '</td>';

  				echo '<td class="td_ideas_content">';
  					echo $date_bug;
  				echo '</td>';

  				// Boutons de prise en charge
  				echo '<td rowspan="100%" class="td_ideas_actions">';
  					echo '<form method="post" action="reports.php?view=' . $_GET['view'] . '&id=' . $bug->getId() . '&action=doChangerStatut">';
  						if ($bug->getResolved() == "N")
  							echo '<input type="submit" name="resolve_bug" value="Résoudre" class="button_idea" />';
  						else
  							echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="button_idea" />';
  					echo '</form>';
  				echo '</td>';
  			echo '</tr>';

  			echo '<tr>';
  				// Remontée par
  				echo '<td class="td_ideas_title">';
  					if ($bug->getType() == "B")
  						echo 'Remonté par';
  					elseif ($bug->getType() == "E")
  						echo 'Proposée par';
  				echo '</td>';

  				echo '<td class="td_ideas_content">';
  					echo $bug->getName_a();
  				echo '</td>';

  				// Statut
  				echo '<td class="td_ideas_title">';
  					echo 'Statut';
  				echo '</td>';

  				echo '<td class="td_ideas_content">';
  					echo $etat_bug;
  				echo '</td>';
  			echo '</tr>';

  			// Description bug
  			echo '<tr class="tr_ideas_idea">';
  				echo '<td colspan="4">';
  					echo '<p>' . nl2br($bug->getContent()) . '</p>';
  				echo '</td>';
  			echo '</tr>';
  		echo '</table>';
    }
  }
  else
  {
    if ($_GET['view'] == "resolved")
      echo '<p class="no_bugs">Aucun(e) bug/évolution résolu(e)</p>';
    elseif ($_GET['view'] == "unresolved")
      echo '<p class="no_bugs">Aucun(e) bug/évolution non résolu(e)</p>';
    else
      echo '<p class="no_bugs">Aucun(e) bug/évolution</p>';
  }
?>