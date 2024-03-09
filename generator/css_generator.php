<?php
/**
 * Génère un fichier CSS
 * 
 * @param array $sprite_images         Tableau des images spritées (path, width, height, x, y, extension)
 * @param string $css_name             Nom du fichier CSS, par défaut "style"
 * @param string $sprite_name          Nom du fichier Sprite PNG, par défaut "sprite"
 * @param integer $spriteX             Largeur du sprite en px
 * @param integer $img_size            Forcer la taille SIZExSIZE des images spritées en px
 * @param integer $nb_cols             Nombre de colonnes, par défaut 0
 * @param integer $padding             Padding en px
 */

function generateCSS($sprite_images, $css_name, $sprite_name, $spriteX, $img_size, $nb_cols, $padding) {
    $css_content = ".body {\n";
    $css_content .= "    display: block;\n";
    $css_content .= "}\n\n";
    
    $css_content .= '.' . $sprite_name . " {\n";
    $css_content .= "    min-width: " . $spriteX . "px;\n";
    
    if ($nb_cols === 0) {
        $css_content .= "    display: flex;\n";
        $css_content .= "    flex-wrap: nowrap;\n";
    }
    $css_content .= "}\n\n";
    
    foreach ($sprite_images as $image) {
        if ($img_size !== 0) {
            $image["width"] = $img_size;
            $image["height"] = $img_size;
        }
    
        $img_name = pathinfo($image["path"], PATHINFO_FILENAME);
        $css_content .= '.' . $img_name . " {\n";
        $css_content .= "    position: absolute;\n";
        $css_content .= "    float: left;\n";
        $css_content .= "    margin-right: " . $padding . "px;\n";
        $css_content .= "    margin-bottom: " . $padding . "px;\n";
        $css_content .= "    width: " . $image["width"] . "px;\n";
        $css_content .= "    height: " . $image["height"] . "px;\n";
        $css_content .= "    background-image: url('" . $image["path"] . "');\n";
        $css_content .= "    left: " . $image["x"] . "px;\n";
        $css_content .= "    top: " . $image["y"] . "px;\n";
        $css_content .= "}\n\n";
    }
    
    $css_content = substr($css_content, 0, -2);
    file_put_contents($css_name . ".css", $css_content);    
}
?>