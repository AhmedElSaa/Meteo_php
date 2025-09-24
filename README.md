# Météo - La Reunion

## Architecture
 
Pour ce projet j'ai utiliser l'architecture MVC, le Model lit les fichiers JSON et structure les données, le Controller reçoit la requête et manipule les données et la View présente les données à l'utilisateur. J'ai utilisé WAMP Sever comme serveur web.

## Carte Google Maps et Coords.json

J'ai utiliser l'API Google Maps pour avoir une carte de La Réunion personnalisé, dans le script.js j'initialise la carte et je place des markers dessus. Les emplacements des markers sont définit dans le fichier coords.json, ce fichier à été géneré par une IA.

## Style et Responsivité

J'ai utilisé du css native pour le style avec un media queries pour que l'interface s'adapte bien a tous types d'écran.

## Difficulté rencontré

Lorsque je cliquais sur une ville, les données prenaient un peu de temps à s’afficher. J’ai donc dû mettre les données dans un cache JS pour les avoir au lancement et éviter de répéter les requêtes (prefetchCity + CITY_CACHE).

## Documentation utilisé

Google Maps : https://www.youtube.com/watch?v=CdDXbvBFXLY<br>
Google Maps : https://www.codementor.io/project-solutions/bffpqhwsho<br>
JSON PHP : https://stackoverflow.com/questions/19758954/get-data-from-json-file-with-php<br>
JSON PHP : https://stackoverflow.com/questions/4064444/returning-json-from-a-php-script

## Objet de l'exercice : Réaliser une carte météo

Vous disposez d'un flux météo au format json
Vous devez disposer ces informations sur une carte de l'île de la Réunion de manière simple et intuitive pour la journée du 18 février 2010.
Vous devez également prévoir une visualisation des prévisions pour les 5 jours suivants.

Vous êtes libre de choisir la carte de votre choix. Vous pouvez utiliser une google map (ou équivalent), ou bien une image fixe.

Le code doit être réalisé en php / js sans framework.

Bon courage ;)

