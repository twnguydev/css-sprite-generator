<?php
/**
 * Génère un fichier HTML
 * 
 * @param array $images             Tableau des images stockées (PNG, JPG, JPEG, GIF)
 * @param string $html_name         Nom du fichier HTML, par défaut "index"
 * @param string $css_name          Nom du fichier CSS, par défaut "style"
 * @param string $sprite_name       Nom du fichier Sprite PNG, par défaut "sprite"
 * @param integer $nb_cols          Nombre de colonnes, par défaut 0
 */

function generateHTML($sprite_images, $html_name, $css_name, $sprite_name, $nb_cols) {
    $html_content = "<!DOCTYPE html>\n";
    $html_content .= "<html lang=\"fr\">\n";
    $html_content .= "    <head>\n";
    $html_content .= "        <meta charset=\"UTF-8\">\n";
    $html_content .= "        <meta name=\"author\" content=\"Tanguy Gibrat\">\n";
    $html_content .= "        <meta name=\"description\" content=\"HTML & CSS Sprite Generator\">\n";
    $html_content .= "        <title>CSS & HTML Generator</title>\n";
    $html_content .= "        <link rel=\"stylesheet\" href=\"" . $css_name . ".css\">\n";
    $html_content .= "    </head>\n";
    $html_content .= "    <body>\n";
    $html_content .= "        <div class=\"" . $sprite_name . "\">\n";

    foreach ($sprite_images as $image) {
        $img_name = pathinfo($image["path"], PATHINFO_FILENAME);
    
        $html_content .= "          <div class=\"" . $img_name . "\"></div>\n";
    }

    $html_content .= "        </div>\n";
    $html_content .= "    </body>\n";
    $html_content .= "</html>";

    file_put_contents($html_name . ".html", $html_content);
}
?>