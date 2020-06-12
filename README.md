# P8 Améliorez une application existante de ToDo & Co

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/fc641b2431494395a37d72534df382c1)](https://www.codacy.com/manual/morantg/p8_s4?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=morantg/p8_s4&amp;utm_campaign=Badge_Grade)

# Pré-requis

- Composer doit être installé sur votre ordinateur

- PHP 7.1.3 minimum


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

Mettez à jour votre base de donnée avec la commande suivante :

> php bin/console doctrine:schema:update --force

## Etape 6 (optionnel)    

- Vous pouvez tester l'application avec un jeu de données fictives.
Pour cela charger les fixtures avec la commande suivante : 

> php bin/console doctrine:fixtures:load

Vous pouvez maintenant tester l'application. 1 utilisateur et 1 admin on été créées.

- User => identifiant : user, mot de passe : 123

- Admin => identifiant : admin, mot de passe : 123

