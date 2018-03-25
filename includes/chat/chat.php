<?php
  // Zone de chat à intégrer
  echo '<div class="zone_chat">';
    // Titre et repli
    echo '<div class="zone_titre_chat">';
      echo '<div class="titre_chat">INSIDE Room [Bêta]</div>';
      echo '<a id="hide_chat" class="reduire_chat">-</a>';
    echo '</div>';

    // Messages
    echo '<div id="scroll_conversation" class="zone_conversation">';
      echo '<div id="conversation" class="contenu_chat"></div>';
    echo '</div>';

    // Saisie
    echo '<form action="#" method="post" id="form_chat" class="form_saisie_chat">';
      echo '<input type="hidden" id="identifiant_chat" value="' . $_SESSION['user']['identifiant'] . '" />';
      echo '<input type="text" id="message_chat" name="message_chat" placeholder="Saisir un message..." autocomplete="off" class="saisie_chat" />';
      echo '<button type="button" id="send_message_chat" title="Envoyer" class="bouton_chat"></button>';
    echo '</form>';
  echo '</div>';

  // Recherche des utilisateurs si pas déjà faite
  if (empty($_COOKIE['chat']['users']))
    $_COOKIE['chat']['users']   = getUsersChat();

  $_COOKIE['chat']['current'] = $_SESSION['user']['identifiant'];

  // On transforme les objets en tableau pour envoyer au Javascript
  $listUsers = array();

  foreach ($_COOKIE['chat']['users'] as $user)
  {
    $user_chat = array('identifiant' => $user->getIdentifiant(),
                       'pseudo'      => $user->getPseudo(),
                       'avatar'      => $user->getAvatar()
                      );
    array_push($listUsers, $user_chat);
  }

  // On formate le tableau au format JSON
  $listUsersJson   = json_encode($listUsers);
  $currentUserJson = json_encode($_COOKIE['chat']['current']);
?>

<!-- Script affichage / ajout messages -->
<script>
  $(function()
  {
    /***************************/
    /***   Initialisations   ***/
    /***************************/
    var showChat = initCookieChat();
    setInterval(rafraichirConversation, 3000, false);

    /******************/
    /***   Appels   ***/
    /******************/
    rafraichirConversation(false);

    /*******************/
    /***   Actions   ***/
    /*******************/
    // Afficher/masquer la fenêtre de chat au clic
    $('#hide_chat').click(afficherMasquerChat);

    // Envoi de message au clic sur le bouton
    $('#send_message_chat').click(envoyerMessage);

    // Envoi de message sur appui de la touche "Entrée"
    $('#message_chat').keypress(function(e)
    {
      if (e.which == 13)
      {
        envoyerMessage();
        return false;
      }
    });

    /*********************/
    /***   Fonctions   ***/
    /*********************/
    // Fonction initialisation cookie
    function initCookieChat()
    {
      cookie = getCookie("showChat");

      // Initialisation cookie état Chat
      if (cookie == null)
      {
        setCookie("showChat", true);
        cookie = getCookie("showChat");
      }

      // Initialisation affichage en fonction du cookie
      if (cookie == "true")
      {
        document.getElementById('hide_chat').innerHTML = '-';
        document.getElementById('scroll_conversation').style.display = "block";
        document.getElementById('form_chat').style.display = "block";
      }
      else
      {
        document.getElementById('hide_chat').innerHTML = '+';
        document.getElementById('scroll_conversation').style.display = "none";
        document.getElementById('form_chat').style.display = "none";
      }

      return cookie;
    }

    // Fonction affichage chat
    function afficherMasquerChat()
    {
      //console.log('showChat avant = ' + showChat);

      if (showChat == "true")
      {
        document.getElementById('hide_chat').innerHTML               = '+';
        document.getElementById('scroll_conversation').style.display = "none";
        document.getElementById('form_chat').style.display           = "none";
        setCookie("showChat", false);
      }
      else
      {
        document.getElementById('hide_chat').innerHTML               = '-';
        document.getElementById('scroll_conversation').style.display = "block";
        document.getElementById('form_chat').style.display           = "block";
        setCookie("showChat", true);
      }

      showChat = getCookie("showChat");

      //console.log('showChat après = ' + showChat);
    }

    // Fonction de rafraichissement du contenu & formatage des messages
    function rafraichirConversation(scrollUpdate)
    {
      //$('#conversation').load('/inside/includes/chat/content_chat.xml');
      $.get('/inside/includes/chat/content_chat.xml', function(display)
      {
        $('#conversation').html('');

        // Récupération liste utilisateurs & identifiant
        var listUsers   = <?php echo $listUsersJson; ?>;
        var currentUser = <?php echo $currentUserJson; ?>;

        // Affichage et formatage de tous les messages
        $(display).find('message').each(function()
        {
          var $message    = $(this);
          var identifiant = $message.find('identifiant').text();
          var text        = decodeHtml($message.find('text').text());
          var date        = $message.find('date').text();
          var time        = $message.find('time').text();
          var pseudo = "Un ancien utilisateur";
          var avatar;
          var html;

          // Formatage pseudo à partir du tableau Php récupéré
          listUsers.forEach(function(user)
          {
            if (identifiant == user.identifiant)
            {
              pseudo = user.pseudo;
              avatar = user.avatar;
              return false;
            }
          });

          // Formatage du message complet
          if (currentUser == identifiant)
          {
            html     = '<div class="zone_chat_user">';
            if (avatar != "" && avatar != undefined)
              html  += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_user" />';
            else
              html  += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_user" />';
            html    += '<div class="triangle_chat_user"></div>';
            html    += '<div class="text_chat_user">' + text + '</div>';
            html    += '</div>';
          }
          else
          {
            html     = '<div class="zone_chat_other">';
            if (avatar != "" && avatar != undefined)
              html  += '<img src="/inside/profil/avatars/' + avatar + '" alt="avatar" title="' + pseudo + '" class="avatar_chat_other" />';
            else
              html  += '<img src="/inside/includes/icons/default.png" alt="avatar" title="' + pseudo + '" class="avatar_chat_other" />';
            html    += '<div class="triangle_chat_other"></div>';
            html    += '<div class="text_chat_other">' + text + '</div>';
            html    += '</div>';
          }

          // Insertion dans la zone
          $('#conversation').append($(html));
        });

        // On repositionne le scroll en bas si on a saisi un message ou que la page s'initialise
        var position = $('#scroll_conversation').scrollTop();
        var height   = $('#scroll_conversation')[0].scrollHeight;

        if (position == 0 || scrollUpdate == true)
          $('#scroll_conversation').scrollTop(height);

      });
    }

    // Fonction envoi de message
    function envoyerMessage()
    {
      var identifiant = $('#identifiant_chat').val();
      var message     = escapeHtml($('#message_chat').val());

      // Envoi du message si renseignée et non vide
      if (!$.isEmptyObject($.trim(message)))
        $.post('/inside/includes/chat/submit_chat.php', {'identifiant': identifiant, 'message': message}, afficheConversation);
      else
      {
        $('#message_chat').val('');
        $('#message_chat').focus();
      }
    }

    // Fonction de rafraichissement après saisie message et repositionnement zone de saisie
    function afficheConversation()
    {
      // On met à jour la conversation
      rafraichirConversation(true);

      // On positionne le curseur dans la zone de saisie
      $('#message_chat').val('');
      $('#message_chat').focus();
    }

    // Encodage des caractères spéciaux
    function escapeHtml(str)
    {
        var map =
        {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return str.replace(/[&<>"']/g, function(m) {return map[m];});
    }

    // Décodage des caractères spéciaux
    function decodeHtml(str)
    {
        var map =
        {
            '&amp;': '&',
            '&lt;': '<',
            '&gt;': '>',
            '&quot;': '"',
            '&#039;': "'"
        };
        return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
    }

    /******************/
    /***   Debugg   ***/
    /******************/
    /*deleteCookie("showChat");

    //console.log('cookies : ' + document.cookie);

    // Debugg liste utilisateurs
    var listUsers = <?php //echo $listUsersJson; ?>;
    listUsers.forEach(function(user)
    {
      test = afficherProps(user, "user");
      console.log(test);
    });

    function afficherProps(obj, nomObjet)
    {
      var resultat = "";
      for (var i in obj)
      {
        if (obj.hasOwnProperty(i))
            resultat += nomObjet + "." + i + " = " + obj[i] + "\n";
      }
      return resultat;
    }

    //console.log('cookie : ' + showChat);*/
  });
</script>