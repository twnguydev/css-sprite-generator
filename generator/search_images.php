<?php
/**
 * Stockage des images valides à spriter
 * 
 * @param string $folder            Nom du dossier à spriter
 * @param boolean $recursive        Option : récursivité sous-dossiers, par défaut false
 * 
 * @return array $images
 */

function searchImages($folder, $recursive = false) {
    $images = [];
    $valid_types = ["png", "jpg", "jpeg", "gif"];

    $files = glob($folder . "/*");

    foreach ($files as $file) {
        if (is_dir($file) && $recursive) {
            $images = array_merge($images, searchImages($file, $recursive));
        }
        else {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $valid_types)) {
                $images[] = $file;
            }
        }
    }

    return $images;
}
?>