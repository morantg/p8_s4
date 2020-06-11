# Guide de contribution au projet

## Etape 1

Réaliser un fork du repository.

## Etape 2

Cloner le fork sur votre machine locale.

> git clone https://github.com/morantg/p8_s4.git

## Etape 3

Installer le projet en suivant les instructions du fichier readme.

## Etape 4

Créez une branche et positionné vous dessus pour réaliser votre contribution.

> git checkout -b ma-branche

## Etape 5

Pusher la branche sur votre fork

> git push origin ma-branche

## Etape 6

Une fois votre contribution terminée réalisez un pull request sur github.


# Procesus de qualité

## Standard PSR12

Votre contribution doit être aux norme PSR12. Vous pouvez vous aidez de php codeSniffer pour vous en assurez.
Pour cela utilisez la commande suivante pour identifier les erreurs :

> phpcs --standard=PSR12 /path/to/code-directory

Et pour corriger automatiquement les erreurs vous pouvez utiliser la commande suivante.

> phpcbf --standard=PSR2 /path/to/code-directory

## Test

Chaque ajout de code que vous réalisé doit être couvert par des tests unitaire ou fonctionnel.
Vous pouvez voir le tau de couverture de votre code avec la commande suivante :

> php bin/phpunit --coverage-html documentation/code-coverage

Ensuite ouvrez le fichier qui ce trouve dans documentation/code-coverage/index.html pour plus de détail.

Attention xdebug doit être installé.

