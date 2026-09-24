# Explorateur de fichiers PHP

Petit explorateur de fichiers développé en PHP. Il permet de parcourir les dossiers situés dans le répertoire du script et d’ouvrir les fichiers accessibles depuis une interface HTML simple.

## Fonctionnalités

- Navigation dans les dossiers et sous-dossiers.
- Retour vers le dossier parent.
- Affichage séparé des dossiers et des fichiers.
- Tri alphabétique des dossiers et des fichiers.
- Ouverture des fichiers dans un nouvel onglet.
- Gestion des chemins invalides.
- Protection contre la navigation en dehors du dossier racine.
- Encodage sécurisé des noms de fichiers affichés dans la page.
- Message affiché lorsqu’un dossier est vide.

## Prérequis

- PHP 7.4 ou version supérieure.
- Un serveur web compatible PHP :
  - Apache
  - Nginx avec PHP-FPM
  - XAMPP
  - WAMP
  - Laragon

## Installation

1. Téléchargez ou clonez ce dépôt.
2. Placez le fichier PHP dans le dossier que vous souhaitez explorer.
3. Renommez le fichier PHP en `index.php` si nécessaire.
4. Lancez le projet avec un serveur compatible PHP.
5. Ouvrez l’adresse suivante dans votre navigateur :

```text
http://localhost/index.php
```

Le dossier contenant le fichier PHP est automatiquement utilisé comme dossier racine de l’explorateur.

## Utilisation

Pour afficher le dossier racine :

```text
http://localhost/index.php
```

Pour afficher un sous-dossier :

```text
http://localhost/index.php?path=documents
```

Pour afficher un dossier imbriqué :

```text
http://localhost/index.php?path=documents/projets
```

Cliquez sur un dossier pour l’ouvrir. Cliquez sur un fichier pour l’ouvrir dans un nouvel onglet.

## Structure du projet

```text
explorateur-fichiers-php/
├── index.php
├── README.md
├── documents/
│   ├── rapport.pdf
│   └── notes.txt
└── images/
    └── photo.jpg
```

## Fonctionnement

Le script utilise le dossier dans lequel il se trouve comme répertoire racine :

```php
\$baseDir = realpath(__DIR__);
```

Le chemin demandé est récupéré avec le paramètre `path` :

```php
\$relativePath = \$_GET['path'] ?? '';
```

Le contenu du dossier est ensuite lu avec :

```php
\$items = scandir(\$currentDir);
```

Les dossiers et les fichiers sont séparés dans deux listes différentes, puis triés par ordre alphabétique.

## Sécurité

Le script vérifie que le chemin demandé reste à l’intérieur du dossier racine. Cette vérification empêche la navigation vers des répertoires externes avec des chemins tels que :

```text
../../etc
```

Les noms de fichiers sont également protégés avant leur affichage dans le HTML grâce à la fonction :

```php
htmlspecialchars()
```

## Attention

Ce projet est principalement destiné à un usage local ou à un environnement contrôlé.

Avant de publier ce projet sur un serveur accessible depuis Internet, il est recommandé de :

- Ajouter un système d’authentification.
- Ne pas exposer de fichiers contenant des mots de passe.
- Ne pas publier de fichiers `.env`.
- Ne pas exposer de clés API ou de certificats privés.
- Limiter les extensions de fichiers accessibles.
- Empêcher l’exécution de fichiers PHP non autorisés.
- Vérifier les permissions des dossiers.
- Utiliser une connexion HTTPS.

## Personnalisation

Le titre de la page peut être modifié dans le fichier PHP :

```html
<title>Explorateur de fichiers</title>
```

Le style de la page peut également être personnalisé dans la section :

```html
<style>
    ...
</style>
```

## Licence

Ce projet est libre d’utilisation et peut être modifié selon vos besoins.
``` (attention: GitHub interprets this as Markdown code)
```_
