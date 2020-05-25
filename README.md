# Installation

## Etape 1

Téléchargez ou clonez le projet dans le répertoire de votre choix. Pour le cloner utilisez la commande suivante :

> git clone https://github.com/morantg/p8_s4.git

## Etape 2

Configurez vos variables d'environnement dans le fichier .env (variable DATABASE_URL)

## Etape 3

Installez les dépendances avec la commande suivante :

> composer install

## Etape 4

Créez la base de donné :

> php bin/console doctrine:database:create

## Etape 5

Réalisez la migration de votre base de donnée avec la commande suivante :

> php bin/console doctrine:migrations:migrate