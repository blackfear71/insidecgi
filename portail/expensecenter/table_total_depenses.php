<?php
  echo '<div class="zone_total_depenses">';
    echo '<table class="table_total_depenses">';
      // Titres
      echo '<tr>';
        echo '<td class="td_init_depenses">Utilisateurs</td>';
        echo '<td class="td_init_depenses">Bilan</td>';
      echo '</tr>';

      // Lignes utilisateurs
      $reponse = $bdd->query('SELECT id, identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

      while($donnees = $reponse->fetch())
      {
        if ($_SESSION['identifiant'] == $donnees['identifiant'])
          echo '<tr style="background-color: #fffde8;">';
        else
          echo '<tr>';

          echo '<td class="td_depenses">';
            // Avatars
            echo '<div class="zone_avatar_total_depenses">';
              if (isset($donnees['avatar']) AND !empty($donnees['avatar']))
                echo '<img src="../profil/avatars/' . $donnees['avatar'] . '" alt="avatar" title="' . $donnees['full_name'] . '" class="avatar_total_depenses" />';
              else
                echo '<img src="../includes/icons/profile.png" alt="avatar" title="' . $donnees['full_name'] . '" class="avatar_total_depenses" />';
            echo '</div>';

            // Pseudos
            echo '<div class="pseudo_total_depenses">' . $donnees['full_name'] . "</div>";
          echo '</td>';

          // Calcul des bilans
          $req1 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

          $bilan = 0;

          while($data1 = $req1->fetch())
          {
            // Prix d'achat
            $prix_achat = $data1['price'];

            // Identifiant de l'acheteur
            $acheteur = $data1['buyer'];

            // echo 'prix achat : ' . $prix_achat . '<br />';
            // echo 'acheteur : ' . $acheteur . '<br />';

            // Nombre de parts et prix par parts
            $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data1['id']);

            $nb_parts_total = 0;
            $nb_parts_user = 0;

            while($data2 = $req2->fetch())
            {
              // Nombre de parts total
              $nb_parts_total = $nb_parts_total + $data2['parts'];

              // Nombre de parts de l'utilisateur
              if ($donnees['identifiant'] == $data2['identifiant'])
                $nb_parts_user = $data2['parts'];
            }

            if ($nb_parts_total != 0)
              $prix_par_part = $prix_achat / $nb_parts_total;
            else
              $prix_par_part = 0;

            // echo 'nb parts total : ' . $nb_parts_total . '<br />';
            // echo 'nb parts user : ' . $nb_parts_user . '<br />';
            // echo 'prix par part : ' . $prix_par_part . '<br />';

            // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
            if ($data1['buyer'] == $donnees['identifiant'])
              $bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_user);
            else
              $bilan = $bilan - ($prix_par_part * $nb_parts_user);

            // echo '<br />';
            // echo '<br />';

            $req2->closeCursor();
          }
          $req1->closeCursor();

          // echo 'BILAN : ' . $bilan . '<br />';
          $bilan_format = str_replace('.', ',', number_format($bilan, 2));

          if ($bilan <= -6)
            echo '<td class="td_depenses" style="background-color: #ee4949">';
          elseif ($bilan <= -3 AND $bilan > -6)
            echo '<td class="td_depenses" style="background-color: #ff9147;">';
          elseif ($bilan < 0 AND $bilan > -3)
            echo '<td class="td_depenses" style="background-color: #fffd4c;">';
          elseif ($bilan > 0 AND $bilan < 5)
            echo '<td class="td_depenses" style="background-color: #b6fc78;">';
          elseif ($bilan > 0 AND $bilan >= 5)
            echo '<td class="td_depenses" style="background-color: #71d058;">';
          else
            echo '<td class="td_depenses">';
              echo '<span class="somme_bilan">' . $bilan_format . ' €</span>';
            echo '</td>';
        echo '</tr>';
      }

      $reponse->closeCursor();
    echo '</table>';
  echo '</div>';
?>