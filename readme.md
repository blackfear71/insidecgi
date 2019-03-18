![INSIDE](http://77.153.236.140/inside/includes/icons/common/inside_readme.png)

# INSIDE - Plateforme de partage créée par les membres pour les membres.

## Lien
Site accessible depuis le lien suivant : [INSIDE](http://77.153.236.140/inside/)

## Fonctionnalités
- MOVIE HOUSE : base de données de films et organisation de soirées cinéma
- LES ENFANTS ! À TABLE ! : outil de détermination du repas du jour
- EXPENSE CENTER : outil de suivi des dépenses des membres
- COLLECTOR ROOM : collection de phrases cultes
- CALENDARS : calendriers de l'équipe
- LES PETITS PEDESTRES : organisation d'entrainements ou de courses à pied
- MISSIONS : INSIDER : évènements du site
- #THEBOX : boîte à idées
- DEMANDES D'ÉVOLUTION : soumission de bugs et améliorations
- NOTIFICATIONS : centre de notifications générales
- PROFIL : gestion paramètres et succès
- INSIDE ROOM : chat général

## Notes aux développeurs
Ne pas toucher aux fichiers suivants lors de vos développements :
- appel_bdd.php
- appel_mail.php
- export_bdd.php

Si des différences sont constatées, veuillez les annuler.

## Les langages utilisés
Au travers de l'architecture MVC (Modèle-Vue-Contrôleur) utilisée, plusieurs langages sont appliqués afin de correspondre aux différents besoins du site.
### HTML
> Utilisé pour la **structure des pages**. Il est conseillé de recopier la structure d'une page lors des développements afin de partir d'une base propre et ensuite apporter des modifications.
### CSS
> Utilisé pour la **mise en forme** et les **animations** basiques. Chaque section du site possède sa propre feuille de style.
### PHP
> Utilisé pour les **interactions côté serveur**. Dans l'architecture MVC, le Contrôleur et le Modèle sont codés en PHP. Il est conseillé de recopier un Contrôleur lors des développements afin de partir d'une base propre et ensuite apporter des modifications.
### MySQL
> Utilisé pour toutes les **requêtes** aux différentes tables de la base de données. Ces requêtes sont généralement décrites dans le Modèle et encapsulées dans du code PHP.
### Javascript
> Utilisé pour les **interactions côté client**. Chaque section du site possède généralement sa propre feuille de scripts. Des animations plus poussées sont codées en Javascript et permettent de modifier visuellement ce qui s'affiche à l'écran de l'utilisateur.
### jQuery
> Utilisé pour les **interactions côté client**. Le jQuery est une bibliothèque Javascript permettant de gérer également des animations et autres modifications sur l'écran de l'utilisateur. Il repose sur le même fonctionnement que l'Ajax en simplifiant toutefois les instructions à taper.
### XML
> Utilisé pour la **structure de données**. Actuellement utilisé uniquement afin de stocker les conversations du Chat, le formatage des données entre balises permet une extraction simple de chaque propriété d'un noeud.

## Variables utiles
### Les couleurs
Les couleurs RGB sont principalement utilisées pour la transparence. Dans les autres cas, utiliser les codes hexadécimaux. Voici les couleurs principalement représentées sur la plateforme :

| Nom             | Couleur                                                  | Code HEX | Code RGB           | Notes                                  |
| ----------------| :------------------------------------------------------: | -------- | ------------------ | -------------------------------------- |
| Rouge           | ![#ff1937](https://placehold.it/15/ff1937/000000?text=+) | #ff1937  | rgb(255, 25, 55)   | Rouge CGI, couleur principale          |
| Rouge           | ![#c81932](https://placehold.it/15/c81932/000000?text=+) | #c81932  | rgb(200, 25, 50)   | Pour contraste (rift)                  |
| Gris clair      | ![#f3f3f3](https://placehold.it/15/f3f3f3/000000?text=+) | #f3f3f3  |                    |                                        |
| Gris clair      | ![#e3e3e3](https://placehold.it/15/e3e3e3/000000?text=+) | #e3e3e3  | rgb(227, 227, 227) |                                        |
| Gris clair      | ![#d3d3d3](https://placehold.it/15/d3d3d3/000000?text=+) | #d3d3d3  |                    |                                        |
| Gris clair      | ![#c3c3c3](https://placehold.it/15/c3c3c3/000000?text=+) | #c3c3c3  |                    |                                        |
| Gris clair      | ![#b3b3b3](https://placehold.it/15/b3b3b3/000000?text=+) | #b3b3b3  |                    |                                        |
| Gris clair      | ![#a3a3a3](https://placehold.it/15/a3a3a3/000000?text=+) | #a3a3a3  |                    |                                        |
| Gris foncé      | ![#7b8084](https://placehold.it/15/7b8084/000000?text=+) | #7b8084  |                    | Pour ombres                            |
| Gris foncé      | ![#2c3840](https://placehold.it/15/2c3840/000000?text=+) | #2c3840  |                    | Pour ombres & contraste (rift switchs) |
| Gris foncé      | ![#262626](https://placehold.it/15/262626/000000?text=+) | #262626  |                    | Header & footer                        |
| Gris/bleu foncé | ![#374650](https://placehold.it/15/374650/000000?text=+) | #374650  |                    | Lien portail & switchs                 |
| Bleu clair      | ![#74cefb](https://placehold.it/15/74cefb/000000?text=+) | #74cefb  |                    |                                        |
| Bleu clair      | ![#2eb2f4](https://placehold.it/15/2eb2f4/000000?text=+) | #2eb2f4  |                    |                                        |
| Bleu clair      | ![#13a2e9](https://placehold.it/15/13a2e9/000000?text=+) | #13a2e9  |                    |                                        |
| Vert clair      | ![#91d784](https://placehold.it/15/91d784/000000?text=+) | #91d784  |                    |                                        |
| Vert clair      | ![#70d55d](https://placehold.it/15/70d55d/000000?text=+) | #70d55d  |                    | Icône utilisateur connecté (chat)      |
| Jaune clair     | ![#fffde8](https://placehold.it/15/fffde8/000000?text=+) | #fffde8  |                    |                                        |
| Jaune clair     | ![#fffd4c](https://placehold.it/15/fffd4c/000000?text=+) | #fffd4c  |                    |                                        |
| Jaune moyen     | ![#ffad01](https://placehold.it/15/ffad01/000000?text=+) | #ffad01  |                    |                                        |
| Blanc           | ![#ffffff](https://placehold.it/15/ffffff/000000?text=+) | #ffffff  | rgb(255, 255, 255) |                                        |
| Noir            | ![#000000](https://placehold.it/15/000000/000000?text=+) | #000000  | rgb(0, 0, 0)       |                                        |

### Les variables globales
Les variables globales ($_SESSION et $_COOKIE) sont généralement organisées sous forme de tableaux regroupant leur contenu en catégories. Ceci facilite la lecture des données pour les développeurs.

| SESSION  | Description                                              |
| -------- | -------------------------------------------------------- |
| index    | Contient les données de l'écran de connexion             |
| alerts   | Contient les tops de déclenchement des messages d'alerte |
| user     | Contient les données utilisateurs et préférences         |
| missions | Contient les données des missions générées               |
| theme    | Contient les données des thèmes                          |
| chat     | Contient les données du chat (données utilisateurs)      |

| COOKIE     | Description                                         |
| ---------- | --------------------------------------------------- |
| showChat   | Etat de repli de la fenêtre de chat                 |
| windowChat | Choix de la fenêtre de chat                         |
