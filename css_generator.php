<?php
include("scandir.php");
include("scandir_recur.php");
$parametre = $argv;
$tab = array();

function option_s($dir_path, $parametre, $count, $tab)
{
	$name = "sprite";
	$style = "style";
	$names = array('name' => $name, 'style' => $style);
	$recursive = "non";
	array_shift($parametre);

	$i = 0;
	while($i < $count-2){
		if($parametre[$i] == "-i"){
			$names['name'] = $parametre[$i+1];
		}
		elseif($parametre[$i] == "-s"){
			$names['style'] = $parametre[$i+1];
		}
		if($parametre[$i] == "-r"){	
			$recursive = "oui";
		}
		$i++;
	}
	if($recursive === "non"){
		my_scandir($dir_path, $names);
	}
	elseif($recursive == "oui"){
		my_scandir_recur($dir_path);
		create_sprite_recur($dir_path, $names);
	}
}

function option_l($dir_path, $parametre, $count, $tab)
{
	$name = "sprite";
	$style = "style";
	$names = array('name' => "sprite", 'style' => "style");
	$recursive = "non";
	array_shift($parametre);
	$i = 0;
	while($i < $count-2){
		if(strpos($parametre[$i], "--output-image=") === 0){
			echo $parametre[$i].PHP_EOL;
			$names['name'] = substr($parametre[$i],15);
		}
		elseif(strpos($parametre[$i], "--output-style=") === 0){
			$names['style'] = substr($parametre[$i], 15);
		}
		if($parametre[$i] == "--recursive"){	
			$recursive = "oui";
		}
		$i++;
	}
	if($recursive === "non"){
		my_scandir($dir_path, $names);
	}
	elseif($recursive == "oui"){
		my_scandir_recur($dir_path);
		create_sprite_recur($dir_path, $names);
	}
}

function permission($count, $tab)
{
	global $parametre;
	$dir_path = array_pop($parametre);

	if(!file_exists("$dir_path/"))
	{
		echo "Le dossier n'existe pas\n";
		return;
	}
	elseif(file_exists("$dir_path/sprite.png"))
	{
		echo "Le sprite existe déjà\n";
		return;
	}
	elseif(file_exists("$dir_path/style.css"))
	{
		echo "Le fichier CSS existe déjà.\n";
		return;
	}
	else
	{
		option_s($dir_path, $parametre, $count, $tab);
	}
}
permission($argc, $tab)
?>