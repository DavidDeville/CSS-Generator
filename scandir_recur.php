<?php

/*Fonction pour générer le CSS à partir du sprite*/

function generate_css_recur($tab, $sprite, $dir_path, $names)
{
	$posx = 0;
	$handle = fopen("$dir_path/" . $names['style'] . ".css", "a+");
	fwrite($handle, ".sprite {
			background-image: url($dir_path/" . $names['name'] . ".png);
			background-repeat: no-repeat;
			display: inline-block;
		}");
	$i = 1;
	
	foreach($tab as $name_img)
	{
		$img = imagecreatefrompng("./$name_img");
		$image_width = imagesx($img);
		$image_height = imagesy($img);
		fwrite($handle, ".sprite-$i {
			width: $image_width;
			height: $image_height;
			background-position : $posx, 0;
		}");
		$posx = $posx + $image_width;
		$i++;
	}
}

/*Fonction pour merge les images au sprite vide*/

function sprite_merge_recur($tab, $sprite, $dir_path, $names)
{
	$posx = 0;

	foreach ($tab as $name_img) {
		$img = imagecreatefrompng("./$name_img");
		$image_width = imagesx($img);
		$image_height = imagesy($img);
		imagecopymerge($sprite, $img, $posx, 0, 0, 0, $image_width, $image_height, 100);
		$posx = $posx + $image_width;
	}
	imagepng($sprite, "./$dir_path/" .$names['name'] . ".png");
	echo "\n---Sprite créé--\n";
	generate_css_recur($tab, $sprite, $dir_path, $names);
}

/*Fonction pour créer le sprite*/

function create_sprite_recur($dir_path, $names)
{
	global $tab;
	$max_height_array = array();
	$img_max_width = 0;

	foreach ($tab as $name_img)
	{
		$img = imagecreatefrompng("./$name_img");
		$image_width = imagesx($img);
		$image_height = imagesy($img);
		$img_max_width += $image_width;

		array_push($max_height_array, $image_height);
		imagedestroy($img);
	}

	sort($max_height_array, SORT_NUMERIC);
	$img_max_height = array_pop($max_height_array);
	$sprite = imagecreatetruecolor($img_max_width, $img_max_height);
	sprite_merge_recur($tab, $sprite, $dir_path, $names);
}

/* Tentative de reproduction de la méthode scandir en recursif*/

function my_scandir_recur($dir_path)
{
	global $tab;

	if ($handle = opendir("$dir_path/"))
	{
 		while (false !== ($entry = readdir($handle)))
 		{
			if($entry == "." OR $entry == "..")
			{
				continue;
			}
			elseif (strpos($entry, ".png"))
			{
				array_push($tab, "$dir_path/$entry");
			}
			elseif(is_dir($dir_path . "/" . $entry))
			{
				my_scandir_recur("$dir_path/$entry");
			}
		}
		closedir($handle);
	}
}
?>