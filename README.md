# Projet Sprite Generator

## Description

Le projet consiste à développer un programme en ligne de commande qui concatène plusieurs images au format PNG présentes dans un dossier en un seul sprite. En outre, l'outil doit également générer un fichier CSS représentant cette concaténation. L'utilisation de cet outil se rapprochera des commandes UNIX, préférant les options courtes.

## Restrictions

Afin de mettre à l'épreuve vos compétences et favoriser votre amélioration, certaines restrictions sont imposées :

- L'utilisation de la fonction `scandir` de PHP est interdite.
- Les classes itératrices de PHP telles que `RecursiveDirectoryIterator` ne doivent pas être utilisées.
Le non-respect de ces restrictions entraînera une note de -42.

## Manuel

### CSS_GENERATOR(1) UserCommands CSS_GENERATOR(1)

**NAME**
css_generator - sprite generator for HTML use

**SYNOPSIS**
css_generator [OPTIONS]... assets_folder

**DESCRIPTION**
Concatène toutes les images à l'intérieur d'un dossier en un seul sprite et génère une feuille de style prête à l'emploi.

**OPTIONS**
- `-r`: Recherche des images dans le dossier `assets_folder` spécifié et tous ses sous-dossiers.
- `-i "name"`: Nom de l'image générée. Si vide, le nom par défaut est "sprite.png".
- `-s "name"`: Nom de la feuille de style générée. Si vide, le nom par défaut est "style.css".

**BONUS OPTIONS**
- `-p number`: Ajoute un espacement entre les images de NUMBER pixels.
- `-o number`: Force chaque image du sprite à avoir une taille de SIZE x SIZE pixels.
- `-c number`: Le nombre maximal d'éléments à générer horizontalement.

**MY PERSONAL BONUS**
- `-t, --three`: Génère une animation Three.js.
- `-h "name"`: Nom du fichier HTML généré. Si vide, le nom par défaut est "index.html".
