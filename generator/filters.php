<?php
/**
 * Gère les paramètres
 * 
 * @param array $argv       Paramètres CLI
 * 
 * Appel de fonction generateSprite
 */

function filterSprite($argv) {
    $recursive = false;
    $three = false;
    $sprite_name = "sprite";
    $css_name = "style";
    $html_name = "index";
    $padding = 0;
    $img_size = 0;
    $nb_cols = 0;
    $folder = "";

    $options = PHP_EOL . PHP_EOL . "\033[39mDétail des paramètres pris en charge par le programme :" . PHP_EOL;
    $options .= "-r, --recursive : active la recherche dans les sous-dossiers" . PHP_EOL;
    $options .= "-t, --three : génère une animation ThreeJS" . PHP_EOL;
    $options .= "-i : définir le nom du fichier sprite PNG généré" . PHP_EOL;
    $options .= "-s : définir le nom du fichier CSS généré" . PHP_EOL;
    $options .= "-h : définir le nom du fichier HTML généré" . PHP_EOL;
    $options .= "-p : définir la taille du padding en px" . PHP_EOL;
    $options .= "-o : définir la taille des images en px" . PHP_EOL;
    $options .= "-c : définir le nombre d'images par ligne";
     
    try {

        for ($i = 1; $i < count($argv); $i++) {
            $arg = $argv[$i];
            switch ($arg) {
                case "-r":
                case "--recursive":
                    $recursive = true;
                    break;
                case "-t":
                case "--three":
                    $three = true;
                    break;
                case "-i":
                    if (!isset($argv[$i + 1]) || strpos($argv[$i + 1], "-") === 0) {
                        $sprite_name = "sprite";
                    } else {
                        $sprite_name = $argv[$i + 1];
                    }
                    $i++;
                    break;
                case "-s":
                    if (!isset($argv[$i + 1]) || strpos($argv[$i + 1], "-") === 0) {
                        $css_name = "style";
                    } else {
                        $css_name = $argv[$i + 1];
                    }
                    $i++;
                    break;
                case "-h":
                    if (!isset($argv[$i + 1]) || strpos($argv[$i + 1], "-") === 0) {
                        $html_name = "index";
                    } else {
                        $html_name = $argv[$i + 1];
                    }
                    $i++;
                    break;
                case "-p":
                    if ($i + 1 < count($argv) && is_numeric($argv[$i + 1])) {
                        $padding = $argv[$i + 1];
                    } else {
                        $padding = 0;
                    }
                    $i++;
                    break;
                case "-o":
                    if (isset($argv[$i + 1])) {
                        $img_size = $argv[$i + 1];
                        if (!is_numeric($img_size) || $img_size <= 0) {
                            throw new Exception("$arg : attend une valeur numérique strictement supérieure à 0 pour la taille des images." . $options);
                        }
                    } else {
                        $img_size = 0;
                    }
                    $i++;
                    break;            
                case "-c":
                    if (isset($argv[$i + 1])) {
                        $nb_cols = $argv[$i + 1];
                        if (!is_numeric($nb_cols) || $nb_cols <= 0) {
                            throw new Exception("$arg : prend une valeur numérique strictement supérieure à 0 pour le nombre de colonnes." . $options);
                        }
                    } else {
                        throw new Exception("$arg : utilisation incorrecte." . $options);
                    }
                    $i++;
                    break;
                default:
                    if (strpos($arg, "-") === 0) {
                        throw new Exception("$arg : paramètre non reconnu par le programme." . $options);
                    } else {
                        $folder = $arg;
                    }
                    break;
            }
        }        

        $images = searchImages($folder, $recursive);

        if (empty($images)) {
            throw new Exception("Dossier inexistant ou aucune image n'est valide.");
        }

        generateSprite($images, $sprite_name, $css_name, $html_name, $padding, $img_size, $nb_cols);

        $values = "\033[32m";
        $values .= "Récupération du dossier \"$folder/\" en cours..." . PHP_EOL;
        $values .= count($images) . " images ont été chargées avec succès." . PHP_EOL;

        if ($three) {
            threeAnimation($images);

            $values .= "Chargement de Three.js ..." . PHP_EOL;
            $values .= "Chargement de dataImages.js ..." . PHP_EOL;
            $values .= "Chargement de three.html ..." . PHP_EOL;
        }

        $values .= "\033[39mVos options sont :\033[32m" . PHP_EOL;
        $values .= "Récursive : " . ($recursive === true ? "OUI" : "\033[31mNON\033[32m") . PHP_EOL;
        $values .= "Nom du fichier sprite : " . ($sprite_name === "sprite" ? "\033[31m" : "\033[32m") . $sprite_name . "\033[32m.png" . PHP_EOL;
        $values .= "Nom du fichier CSS : " . ($css_name === "style" ? "\033[31m" : "\033[32m") . $css_name . "\033[32m.css" . PHP_EOL;
        $values .= "Nom du fichier HTML : " . ($html_name === "index" ? "\033[31m" : "\033[32m") . $html_name . "\033[32m.html" . PHP_EOL;
        $values .= "Padding : " . ($padding === 0 ? "\033[31mValeur par défaut.\033[32m" : "\033[32m" . $padding . "px") . PHP_EOL;
        $values .= "Taille des images : " . ($img_size === 0 ? "\033[31mTaille originale des images.\033[32m" : "\033[32m" . $img_size . "x" . $img_size . "px") . PHP_EOL;
        $values .= "Nombres de colonnes : " . ($nb_cols === 0 || $nb_cols > count($images) ? "\033[31mValeur par défaut.\033[32m" : "\033[32m" . $nb_cols);

        $results = explode(PHP_EOL, $values);
        foreach ($results as $i => $result) {
            echo $result . PHP_EOL;
            if ($i === 0) {
                usleep(3000000);
            }
            if ($i === 1) {
                usleep(1000000);
            }
            if ($three) {
                if ($i === 2) {
                    usleep(1000000);
                }
                if ($i === 3) {
                    usleep(1000000);
                }
                if ($i === 4) {
                    usleep(1000000);
                }
                if ($i === 5) {
                    usleep(1000000);
                }
            } else {
                if ($i === 2) {
                    usleep(1000000);
                }
            }
        }
    
    } catch (Exception $e) {
        echo "\033[31m" . $e->getMessage() . PHP_EOL;
    }
}
?>