<?php
/*Fonction pour générer le CSS à partir du sprite*/

function generate_css($tab, $sprite, $dir_path, $names)
{
	$posx = 0;
	$handle = fopen("$dir_path/" . $names['style'] . ".css", "a+");
	fwrite($handle, ".sprite {
			background-image: url($dir_path/" . $names['name'] . ".png);
			background-repeat: no-repeat;
			display: inline-block;
		}");

	foreach($tab as $name_img)
	{
		$img = imagecreatefrompng("./$dir_path/$name_img");
		$image_width = imagesx($img);
		$image_height = imagesy($img);
		fwrite($handle, ".sprite-$name_img {
			width: $image_width;
			height: $image_height;
			background-position : $posx, 0;
		}");
		$posx = $posx + $image_width;
	}
}

/*Fonction pour merge les images au sprite vide*/

function sprite_merge($tab, $sprite, $dir_path, $names)
{
	$posx = 0;

	foreach ($tab as $name_img) {
		$img = imagecreatefrompng("./$dir_path/$name_img");
		$image_width = imagesx($img);
		$image_height = imagesy($img);
		imagecopymerge($sprite, $img, $posx, 0, 0, 0, $image_width, $image_height, 100);
		$posx = $posx + $image_width;
	}
	imagepng($sprite, "./$dir_path/" . $names['name'] . ".png");
	generate_css($tab, $sprite, $dir_path, $names);
}

/*Fonction pour créer le sprite*/

function create_sprite($tab, $dir_path, $names)
{
	$max_height_array = array();
	$img_max_width = 0;

	foreach ($tab as $name_img)
	{
		$img = imagecreatefrompng("./$dir_path/$name_img");
		$image_width = imagesx($img);
		$image_height = imagesy($img);
		$img_max_width += $image_width;
		array_push($max_height_array, $image_height);
		imagedestroy($img);
	}

	sort($max_height_array, SORT_NUMERIC);
	$img_max_height = array_pop($max_height_array);
	echo  $img_max_width . "x" . $img_max_height . PHP_EOL;
	$sprite = imagecreatetruecolor($img_max_width, $img_max_height);
	sprite_merge($tab, $sprite, $dir_path, $names);
}

/* Tentative de reproduction de la méthode scandir */

function my_scandir($dir_path, $names)
{
	if(file_exists("$dir_path/")){
		if ($handle = opendir("$dir_path/")){
			$tab = array();
 	    	while (false !== ($entry = readdir($handle))) {
	    		if($entry == "." OR $entry == ".."){
	        	}
	        	elseif (strpos($entry, ".png")){
	        		array_push($tab, $entry);
	        	}
	        	else{
	        		echo "$entry : format invalide.\n";
	        	}
	    	}
	    	closedir($handle);
	    }
	}
	else{
		echo "Dossier inexistant.\n";
	}
	create_sprite($tab, $dir_path, $names);
}
?>