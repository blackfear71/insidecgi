<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "FA";
      $style_head   = "styleFA.css";
      $script_head  = "scriptFA.js";
      $chat_head    = true;
      $masonry_head = true;
      $exif_head    = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Les enfants ! À table !";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Saisie
          echo '<div class="zone_liens_saisie">';
            echo '<a onclick="afficherMasquer(\'zone_add_restaurant\');" title="Ajouter un restaurant" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter un restaurant</div>';
            echo '</a>';
          echo '</div>';

          /************************************/
          /*** Zone de saisie de restaurant ***/
          /************************************/
          echo '<div id="zone_add_restaurant" style="display: none;" class="fond_saisie_restaurant">';
            echo '<div class="zone_saisie_restaurant">';
              // Titre
              echo '<div class="titre_saisie_restaurant">Ajouter un restaurant</div>';

              // Bouton fermeture
              echo '<a onclick="afficherMasquer(\'zone_add_restaurant\');" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

              // Saisie restaurant
              echo '<form method="post" action="restaurants.php?action=doAjouter" enctype="multipart/form-data" runat="server" class="form_saisie_restaurant">';
                // Photo & numéro
                echo '<div class="zone_saisie_left">';
                  // Photo
                  echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

                  echo '<span class="zone_parcourir_restaurant_saisie">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image_restaurant" class="bouton_parcourir_restaurant_saisie" onchange="loadFile(event, \'img_restaurant_saisie\')" /></span>';

                  echo '<div class="mask_saisie_restaurant">';
                    echo '<img id="img_restaurant_saisie" class="image_saisie_restaurant" />';
                  echo '</div>';

                  // Jours d'ouverture
                  $semaine = array("Lu" => "lundi",
                                   "Ma" => "mardi",
                                   "Me" => "mercredi",
                                   "Je" => "jeudi",
                                   "Ve" => "vendredi");

                  $i = 0;

                  foreach ($semaine as $j => $jour)
                  {
                    if (empty($_SESSION['save']['ouverture_restaurant']) OR isset($_SESSION['save']['ouverture_restaurant'][$i]))
                    {
                      echo '<input type="checkbox" id="saisie_checkbox_ouverture_' . $jour . '" name="ouverture_restaurant[' . $i . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" checked />';
                      echo '<label for="saisie_checkbox_ouverture_' . $jour . '" id="saisie_label_ouverture_' . $jour . '" onclick="changeCheckedDay(\'saisie_checkbox_ouverture_' . $jour . '\', \'saisie_label_ouverture_' . $jour . '\', \'label_jour_checked\', \'label_jour\');" class="label_jour_checked">' . $j . '</label>';
                    }
                    else
                    {
                      echo '<input type="checkbox" id="saisie_checkbox_ouverture_' . $jour . '" name="ouverture_restaurant[' . $i . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" />';
                      echo '<label for="saisie_checkbox_ouverture_' . $jour . '" id="saisie_label_ouverture_' . $jour . '" onclick="changeCheckedDay(\'saisie_checkbox_ouverture_' . $jour . '\', \'saisie_label_ouverture_' . $jour . '\', \'label_jour_checked\', \'label_jour\');" class="label_jour">' . $j . '</label>';
                    }

                    $i++;
                  }

                  echo '<div class="zone_saisie_prix">';
                    echo '<input type="text" name="prix_min_restaurant" value="' . $_SESSION['save']['prix_min'] . '" maxlength="5" placeholder="Prix min." class="saisie_prix_min_restaurant" />';
                    echo '<input type="text" name="prix_max_restaurant" value="' . $_SESSION['save']['prix_max'] . '" maxlength="5" placeholder="Prix max." class="saisie_prix_max_restaurant" />';
                  echo '</div>';
                echo '</div>';

                // Nom, lieu, action et types
                echo '<div class="zone_saisie_right">';
                  // Nom
                  if ($_SESSION['save']['location'] == "other_location")
                    echo '<input type="text" name="name_restaurant" value="' . $_SESSION['save']['name_restaurant'] . '" placeholder="Nom du restaurant" id="saisie_nom" class="saisie_nom_restaurant" style="width: calc(34% - 77px);" required />';
                  else
                    echo '<input type="text" name="name_restaurant" value="' . $_SESSION['save']['name_restaurant'] . '" placeholder="Nom du restaurant" id="saisie_nom" class="saisie_nom_restaurant" required />';

                  // Lieu
                  if ($_SESSION['save']['location'] == "other_location")
                    echo '<select name="location" id="saisie_location" onchange="afficherOther(\'saisie_location\', \'saisie_other_location\', \'saisie_nom\');" class="saisie_lieu" style="width: calc(33% - 100px);" required>';
                  else
                    echo '<select name="location" id="saisie_location" onchange="afficherOther(\'saisie_location\', \'saisie_other_location\', \'saisie_nom\');" class="saisie_lieu" required>';
                      echo '<option value="" hidden>Choisissez...</option>';

                      foreach ($listeLieux as $lieu)
                      {
                        if ($lieu == $_SESSION['save']['location'])
                          echo '<option value="' . $lieu . '" selected>' . $lieu . '</option>';
                        else
                          echo '<option value="' . $lieu . '">' . $lieu . '</option>';
                      }

                      if ($_SESSION['save']['location'] == "other_location")
                        echo '<option value="other_location" selected>Autre</option>';
                      else
                        echo '<option value="other_location">Autre</option>';
                    echo '</select>';

                  // Lieu "Autre"
                  if ($_SESSION['save']['location'] == "other_location")
                    echo '<input type="text" name="saisie_other_location" value="' . $_SESSION['save']['saisie_other_location'] . '" placeholder="Lieu personnalisé" maxlength="255" id="saisie_other_location" class="saisie_lieu_autre_restaurant" style="width: calc(33% - 100px);" />';
                  else
                    echo '<input type="text" name="saisie_other_location" placeholder="Lieu personnalisé" maxlength="255" id="saisie_other_location" class="saisie_lieu_autre_restaurant" style="display: none;" />';

                  // Bouton d'ajout
                  echo '<input type="submit" name="insert_restaurant" value="Ajouter" class="saisie_bouton" />';

                  // Types
                  echo '<div id="types_restaurants">';
                    echo '<a name="type_other" onclick="addOtherType(\'types_restaurants\');" class="bouton_type_autre"><span class="fond_plus">+</span>Autre</a>';

                    $i = 0;

                    // Types existants
                    foreach ($listeTypes as $type)
                    {
                      $id_type    = "type_" . formatIdRestaurant($type);
                      $label_type = "label_" . formatIdRestaurant($type);
                      $checked    = false;

                      echo '<div class="zone_type">';
                        if (!empty($_SESSION['save']['types_restaurants']))
                        {
                          foreach ($_SESSION['save']['types_restaurants'] as $saved_types)
                          {
                            if ($saved_types == $type)
                            {
                              $checked = true;
                              break;
                            }
                          }
                        }

                        if ($checked == true)
                        {
                          echo '<input type="checkbox" id="' . $id_type . '" name="types_restaurants[' . $i . ']" value="' . $type . '" onchange="changeCheckedColor(\'' . $id_type . '\', \'' . $label_type . '\', \'label_type_checked\', \'label_type\');" class="checkbox_type" checked />';
                          echo '<label for="' . $id_type . '" id="' . $label_type . '" class="label_type_checked">' . $type . '</label>';
                        }
                        else
                        {
                          echo '<input type="checkbox" id="' . $id_type . '" name="types_restaurants[' . $i . ']" value="' . $type . '" onchange="changeCheckedColor(\'' . $id_type . '\', \'' . $label_type . '\', \'label_type_checked\', \'label_type\');" class="checkbox_type" />';
                          echo '<label for="' . $id_type . '" id="' . $label_type . '" class="label_type">' . $type . '</label>';
                        }
                      echo '</div>';

                      $i++;
                    }

                    // Types personnalisés (sauvegardés en cas d'erreur)
                    if (!empty($_SESSION['save']['types_restaurants']))
                    {
                      $j = $i;

                      foreach ($_SESSION['save']['types_restaurants'] as $saved_types)
                      {
                        $custom_type = true;

                        foreach ($listeTypes as $type)
                        {
                          if ($saved_types == $type)
                          {
                            $custom_type = false;
                            break;
                          }
                        }

                        if ($custom_type == true)
                        {
                          $id_custom_type    = "type_" . formatIdRestaurant($saved_types);
                          $label_custom_type = "label_" . formatIdRestaurant($saved_types);

                          echo '<input type="text" placeholder="Type" value="' . $saved_types . '" id="' . $id_custom_type . '" name="types_restaurants[' . $j . ']" oninput="changeTypeColor(\'' . $id_custom_type . '\')" class="type_other" style="background-color: #70d55d; color: white;" />';

                          $j++;
                        }
                      }
                    }
                  echo '</div>';
                echo '</div>';

                // Téléphone, site web, plan et description
                echo '<div class="zone_saisie_under">';
                  // Numéro
                  echo '<input type="text" name="phone_restaurant" value="' . $_SESSION['save']['phone_restaurant'] . '" maxlength="10" placeholder="Téléphone" class="saisie_telephone_restaurant" />';

                  // Site web
                  echo '<input type="text" name="website_restaurant" value="' . $_SESSION['save']['website_restaurant'] . '" placeholder="Site web" class="saisie_lien_restaurant" />';

                  // Plan
                  echo '<input type="text" name="plan_restaurant" value="' . $_SESSION['save']['plan_restaurant'] . '" placeholder="Plan" class="saisie_lien_restaurant" />';

                  // Description
                  echo '<textarea placeholder="Description" name="description_restaurant" class="textarea_saisie_description_restaurant">' . $_SESSION['save']['description_restaurant'] . '</textarea>';
                echo '</div>';
              echo '</form>';
            echo '</div>';
          echo '</div>';

          /******************************/
          /*** Fiches des restaurants ***/
          /******************************/
          echo '<div class="zone_restaurants" style="display: none;">';
            foreach ($listeRestaurants as $lieu => $restaurantsParLieux)
            {
              echo '<div class="titre_section">' . $lieu . '</div>';

              echo '<div class="zone_fiches_restaurants">';
                foreach ($restaurantsParLieux as $restaurant)
                {
                  /*********************************************/
                  /* Visualisation normale (sans modification) */
                  /*********************************************/
                  echo '<div class="fiche_restaurant" id="modifier_restaurant_2[' . $restaurant->getId() . ']">';
                    echo '<div id="zone_shadow_' . $restaurant->getId() . '" class="zone_shadow">';
                      // Image
                      if (!empty($restaurant->getPicture()))
                        echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="restaurant" title="' . $restaurant->getName() . '" class="image_restaurant" />';
                      else
                        echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" title="' . $restaurant->getName() . '" class="image_restaurant" />';

                      // Modification
                      echo '<a onclick="afficherMasquer(\'modifier_restaurant[' . $restaurant->getId() . ']\'); afficherMasquer(\'modifier_restaurant_2[' . $restaurant->getId() . ']\'); initMasonry();" title="Modifier" class="icone_modify_restaurant"></a>';

                      // Suppression
                      echo '<form method="post" action="restaurants.php?delete_id=' . $restaurant->getId() . '&action=doSupprimer" onclick="if(!confirm(\'Supprimer ce restaurant de la liste ?\')) return false;">';
                        echo '<input type="submit" name="delete_restaurant" value="" title="Supprimer le restaurant" class="icon_delete_restaurant" />';
                      echo '</form>';

                      // Nom
                      echo '<div class="nom_restaurant" id="' . $restaurant->getId() . '">' . $restaurant->getName() . '</div>';

                      // Prix
                      if (!empty($restaurant->getMin_price()) AND !empty($restaurant->getMax_price()))
                      {
                        if ($restaurant->getMin_price() == $restaurant->getMax_price())
                          echo '<div class="price">Prix moy. ' . $restaurant->getMin_price() . '€</div>';
                        else
                        {
                          echo '<div class="price">Prix min. ' . $restaurant->getMin_price() . '€</div>';
                          echo '<div class="price">Prix max. ' . $restaurant->getMax_price() . '€</div>';
                        }
                      }

                      // Types
                      echo '<div class="zone_types_fiche">';
                        $explodedTypes = explode(";", $restaurant->getTypes());

                        foreach ($explodedTypes as $exploded)
                        {
                          if (!empty($exploded))
                            echo '<span class="type_restaurant">' . $exploded . '</span>';
                        }
                      echo '</div>';

                      // Jours d'ouverture, site web et plan
                      echo '<div class="zone_icones_fiches">';
                        // Jours d'ouverture
                        $explodedOpened = explode(";", $restaurant->getOpened());
                        $semaine_short  = array("Lu", "Ma", "Me", "Je", "Ve");
                        $k              = 0;

                        foreach ($explodedOpened as $opened)
                        {
                          if (!empty($opened))
                          {
                            if ($opened == "Y")
                              echo '<div class="jour_oui">' . $semaine_short[$k] . '</div>';
                            else
                              echo '<div class="jour_non">' . $semaine_short[$k] . '</div>';
                          }

                          $k++;
                        }

                        // Site web
                        if (!empty($restaurant->getWebsite()))
                        {
                          echo '<a href="' . $restaurant->getWebsite() . '" target="_blank">';
                            echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="icone_fiche" style="margin-left: 2px;"/>';
                          echo '</a>';
                        }

                        // Plan
                        if (!empty($restaurant->getPlan()))
                        {
                          echo '<a href="' . $restaurant->getPlan() . '" target="_blank">';
                            echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Plan" class="icone_fiche" />';
                          echo '</a>';
                        }
                      echo '</div>';

                      // Numéro
                      if (!empty($restaurant->getPhone()))
                      {
                        echo '<div class="numero_restaurant">';
                          echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Téléphone" class="icone_fiche_phone" />';
                          echo '<div class="phone_number">' . formatPhoneNumber($restaurant->getPhone()) . '</div>';
                        echo '</div>';
                      }

                      // Description
                      if (!empty($restaurant->getDescription()))
                      {
                        $longueur_max = 300;

                        echo '<div class="description_restaurant">';
                          if (strlen($restaurant->getDescription()) > $longueur_max)
                          {
                            echo '<div id="long_description_' . $restaurant->getId() . '" style="display: none;">' . nl2br($restaurant->getDescription()) . '</div>';
                            echo '<div class="short_description" id="short_description_' . $restaurant->getId() . '">' . substr(nl2br($restaurant->getDescription()), 0, $longueur_max) . '...</div>';
                            echo '<a onclick="afficherMasquer(\'short_description_' . $restaurant->getId() . '\'); afficherMasquer(\'long_description_' . $restaurant->getId() . '\'); initMasonry();"><img src="../../includes/icons/foodadvisor/expand.png" alt="expand" class="expand_description" /></a>';
                          }
                          else
                            echo '<div>' . nl2br($restaurant->getDescription()) . '</div>';
                        echo '</div>';
                      }
                    echo '</div>';
                  echo '</div>';

                  /***************************/
                  /* Caché pour modification */
                  /***************************/
                  echo '<div class="fiche_restaurant" id="modifier_restaurant[' . $restaurant->getId() . ']" style="display: none; position: relative; z-index: 2;">';
                    echo '<form method="post" action="restaurants.php?action=doModifier&update_id=' . $restaurant->getId() . '" enctype="multipart/form-data" runat="server">';
                      // Image
                      echo '<div class="zone_update_image">';
                        echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

                        echo '<span class="zone_parcourir_restaurant_update">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="update_image_restaurant_' . $restaurant->getId() . '" class="bouton_parcourir_restaurant_update" onchange="loadFile(event, \'img_restaurant[' . $restaurant->getId() . ']\')" /></span>';

                        echo '<div class="mask_update_restaurant">';
                          if (!empty($restaurant->getPicture()))
                            echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" id="img_restaurant[' . $restaurant->getId() . ']" class="update_image_restaurant" />';
                          else
                            echo '<img id="img_restaurant[' . $restaurant->getId() . ']" class="update_image_restaurant" />';
                          echo '</div>';
                      echo '</div>';

                      // Validation modification
                      echo '<input type="submit" name="modify_restaurant_' . $restaurant->getId() . '" value="" title="Valider" class="icon_validate_restaurant" />';

                      // Annulation modification
                      echo '<a onclick="afficherMasquer(\'modifier_restaurant[' . $restaurant->getId() . ']\'); afficherMasquer(\'modifier_restaurant_2[' . $restaurant->getId() . ']\'); initMasonry();" title="Annuler" class="icone_cancel_restaurant"></a>';

                      // Nom
                      echo '<input type="text" name="update_name_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getName() . '" placeholder="Nom du restaurant" class="update_nom_restaurant" required />';

                      // Lieu
                      echo '<select name="update_location_' . $restaurant->getId() . '" id="update_location_' . $restaurant->getId() . '" class="update_lieu_restaurant" onchange="afficherModifierOther(\'update_location_' . $restaurant->getId() . '\', \'other_location_' . $restaurant->getId() . '\');" required>';
                        foreach ($listeLieux as $lieu)
                        {
                          if ($lieu == $restaurant->getLocation())
                            echo '<option value="' . $lieu . '" selected>' . $lieu . '</option>';
                          else
                            echo '<option value="' . $lieu . '">' . $lieu . '</option>';
                        }

                        echo '<option value="other_location">Autre</option>';
                      echo '</select>';

                      // Lieu "Autre"
                      echo '<input type="text" name="update_other_location_' . $restaurant->getId() . '" placeholder="Lieu personnalisé" maxlength="255" id="other_location_' . $restaurant->getId() . '" class="update_lieu_autre_restaurant" style="display: none;" />';

                      // Jours d'ouverture
                      echo '<div class="opened_restaurant_update">';
                        $explodedOpened = explode(";", $restaurant->getOpened());
                        $semaine        = array("Lu" => "lundi",
                                                "Ma" => "mardi",
                                                "Me" => "mercredi",
                                                "Je" => "jeudi",
                                                "Ve" => "vendredi");

                        $l = 0;

                        foreach ($semaine as $j => $jour)
                        {
                          $id_opened    = "checkbox_update_ouverture_" . $jour . "_" . $restaurant->getId();
                          $label_opened = "label_update_ouverture_" . $jour . "_" . $restaurant->getId();

                          if ($explodedOpened[$l] == "Y")
                          {
                            echo '<input type="checkbox" id="' . $id_opened . '" name="update_ouverture_restaurant_' . $restaurant->getId() . '[' . $l . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" checked />';
                            echo '<label for="' . $id_opened . '" id="' . $label_opened . '" onclick="changeCheckedDay(\'' . $id_opened . '\', \'' . $label_opened . '\', \'update_label_jour_checked\', \'update_label_jour\');" class="update_label_jour_checked">' . $j . '</label>';
                          }
                          else
                          {
                            echo '<input type="checkbox" id="' . $id_opened . '" name="update_ouverture_restaurant_' . $restaurant->getId() . '[' . $l . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" />';
                            echo '<label for="' . $id_opened . '" id="' . $label_opened . '" onclick="changeCheckedDay(\'' . $id_opened . '\', \'' . $label_opened . '\', \'update_label_jour_checked\', \'update_label_jour\');" class="update_label_jour">' . $j . '</label>';
                          }

                          $l++;
                        }
                      echo '</div>';

                      // Prix
                      echo '<div class="zone_update_prix">';
                        echo '<input type="text" name="update_prix_min_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getMin_price() . '" maxlength="5" placeholder="Prix min." class="update_prix_min_restaurant" /> €';
                        echo '<input type="text" name="update_prix_max_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getMax_price() . '" maxlength="5" placeholder="Prix max." class="update_prix_max_restaurant" /> €';
                      echo '</div>';

                      // Types
                      echo '<div id="update_types_restaurants_' . $restaurant->getId() . '" class="zone_update_types">';
                        echo '<a name="type_other" onclick="addOtherType(\'update_types_restaurants_' . $restaurant->getId() . '\');" class="bouton_type_autre"><span class="fond_plus">+</span>Autre</a>';

                        $explodedTypes = explode(";", $restaurant->getTypes());
                        $k             = 0;

                        foreach ($listeTypes as $type)
                        {
                          $id_type    = "type_" . formatIdRestaurant($type) . "_" . $restaurant->getId();
                          $label_type = "label_" . formatIdRestaurant($type) . "_" . $restaurant->getId();

                          echo '<div class="zone_type">';
                            $matching = false;

                            foreach ($explodedTypes as $exploded)
                            {
                              if (!empty($exploded) AND $exploded == $type)
                              {
                                $matching = true;
                                break;
                              }
                            }

                            if ($matching == true)
                            {
                              echo '<input type="checkbox" id="' . $id_type . '" name="update_types_restaurants_' . $restaurant->getId() . '[' . $k . ']" value="' . $type . '" onchange="changeCheckedColor(\'' . $id_type . '\', \'' . $label_type . '\', \'label_type_checked\', \'label_type\');" class="checkbox_type" checked />';
                              echo '<label for="' . $id_type . '" id="' . $label_type . '" class="label_type_checked">' . $type . '</label>';
                            }
                            else
                            {
                              echo '<input type="checkbox" id="' . $id_type . '" name="update_types_restaurants_' . $restaurant->getId() . '[' . $k . ']" value="' . $type . '" onchange="changeCheckedColor(\'' . $id_type . '\', \'' . $label_type . '\', \'label_type_checked\', \'label_type\');" class="checkbox_type" />';
                              echo '<label for="' . $id_type . '" id="' . $label_type . '" class="label_type">' . $type . '</label>';
                            }
                          echo '</div>';

                          $k++;
                        }
                      echo '</div>';

                      // Site web
                      echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="update_icone_fiche" />';
                      echo '<input type="text" name="update_website_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getWebsite() . '" placeholder="Site web" class="update_lien_restaurant" />';

                      // Plan
                      echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Plan" class="update_icone_fiche" />';
                      echo '<input type="text" name="update_plan_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getPlan() . '" placeholder="Plan" class="update_lien_restaurant" />';

                      // Téléphone
                      echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Téléphone" class="update_icone_fiche" />';
                      echo '<input type="text" name="update_phone_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getPhone() . '" maxlength="10" placeholder="Téléphone du restaurant" class="update_lien_restaurant" />';

                      // Description
                      echo '<div class="description_restaurant">';
                        echo '<textarea placeholder="Description" name="update_description_restaurant_' . $restaurant->getId() . '" class="textarea_update_description_restaurant">' . $restaurant->getDescription() . '</textarea>';
                      echo '</div>';

                    echo '</form>';
                  echo '</div>';
                }
              echo '</div>';
            }
          echo '</div>';
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
