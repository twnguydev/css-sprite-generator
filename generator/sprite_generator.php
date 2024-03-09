<?php
/**
 * Génère un sprite PNG
 * 
 * @param array $images             Tableau des images stockées (PNG, JPG, JPEG, GIF)
 * @param string $sprite_name       Nom du fichier Sprite PNG, par défaut "sprite"
 * @param string $css_name          Nom du fichier CSS, par défaut "style"
 * @param string $html_name         Nom du fichier HTML, par défaut "index"
 * @param integer $padding          Padding en px
 * @param integer $img_size         Forcer la taille SIZExSIZE des images spritées en px
 * @param integer $nb_cols          Nombre de colonnes définis en param ARGV, si 0 alors 1 ligne
 * 
 * Appel de fonction generateCSS
 * Appel de fonction generateHTML
 */

function generateSprite($images, $sprite_name, $css_name, $html_name, $padding, $img_size, $nb_cols) {
    $nb_images = count($images);

    $nb_columns = ($nb_cols > 0 && $nb_cols < $nb_images) ? $nb_cols : $nb_images;
    $nb_rows = ceil($nb_images / $nb_columns);
    
    $sprite_images = [];
    $images_height = [];
    $images_width = [];
    
    $total_width = 0;
    $total_height = 0;
    
    foreach ($images as $image) {
        if ($img_size !== 0) {
            $image_sizeX = $img_size;
            $image_sizeY = $img_size;
        }
        else {
            $output = getimagesize($image);
            $image_sizeX = $output[0];
            $image_sizeY = $output[1];
        }

        $total_width += ($image_sizeX + $padding);
        $images_height[] = $image_sizeY;
        $images_width[] = $image_sizeX;
    
        $sprite_images[] = [
            "path" => $image,
            "width" => $image_sizeX,
            "height" => $image_sizeY,
            "x" => 0,
            "y" => 0,
            "ext" => strtolower(pathinfo($image, PATHINFO_EXTENSION))
        ];
    }
    $total_width -= $padding;

    $max_widths = [];
    $max_heights = [];
    for ($row = 0; $row < $nb_rows; $row++) {
        $start = $row * $nb_columns;
        $max_height = 0;
        $max_width = 0;
    
        for ($i = $start; $i < $start + $nb_columns && $i < $nb_images; $i++) {
            $max_height = max($max_height, $images_height[$i]);
            $max_width += ($images_width[$i] + $padding);
        }
        $max_heights[] = $max_height;
        $max_widths[] = $max_width;
    }
    
    $max_width = max($max_widths);
    $max_width -= $padding;

    $spriteX = $max_width;
    $spriteY = array_sum($max_heights) + $padding * ($nb_rows - 1);
    $sprite = imagecreatetruecolor($spriteX, $spriteY);
    
    $pos_x = 0;
    $pos_y = 0;
    for ($row = 0; $row < $nb_rows; $row++) {
        $row_height = 0;
    
        for ($col = 0; $col < $nb_columns; $col++) {
            $i = $row * $nb_columns + $col;
    
            if ($i < $nb_images) {
                $sprite_image = $sprite_images[$i];
                $ext = $sprite_image["ext"];
                $image_sizeX = $sprite_image["width"];
                $image_sizeY = $sprite_image["height"];
                $sprite_image["x"] = $pos_x;
                $sprite_image["y"] = $pos_y;

                $sprite2 = null;
    
                if ($ext === "png") {
                    $sprite2 = imagecreatefrompng($sprite_image["path"]);
                }
                elseif ($ext === "jpg" || $ext === "jpeg") {
                    $sprite2 = imagecreatefromjpeg($sprite_image["path"]);
                }
                elseif ($ext === "gif") {
                    $sprite2 = imagecreatefromgif($sprite_image["path"]);
                }
                else {
                    die("Aucun fichier n'est valide.");
                }
    
                if ($sprite2) {
                    $resize_img = imagescale($sprite2, $image_sizeX, $image_sizeY);
                    imagecopy($sprite, $resize_img, $pos_x, $pos_y, 0, 0, $image_sizeX, $image_sizeY);
                    imagedestroy($sprite2);
                    imagedestroy($resize_img);
                }
    
                $sprite_images[$i]["x"] = $pos_x;
                $sprite_images[$i]["y"] = $pos_y;

                $pos_x += $image_sizeX + $padding;
                $row_height = max($row_height, $image_sizeY);
            }
        }

        $pos_x = 0;
        $pos_y += $row_height + $padding;
    }

    generateCSS($sprite_images, $css_name, $sprite_name, $spriteX, $img_size, $nb_cols, $padding);
    generateHTML($sprite_images, $html_name, $css_name, $sprite_name, $nb_cols);

    imagepng($sprite, $sprite_name . ".png");
    imagedestroy($sprite);
}
?>