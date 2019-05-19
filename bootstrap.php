<?php

header("Content-type: image/png");

function my_merge_image($first_img_path, $second_img_path)
{
	$first_img_path = imagecreatefrompng($first_img_path);
	$first_img_width = imagesx($first_img_path);
	$first_img_height = imagesy($first_img_path);
	
	$second_img_path = imagecreatefrompng($second_img_path);
	$second_img_path = imagescale($second_img_path, $first_img_width);
	$second_img_width = imagesx($second_img_path);
	$second_img_height = imagesy($second_img_path);

	$third_image = imagecreate($first_img_width, $first_img_height + $second_img_height);
	
// Copie et fusion de l'image

	imagecopymerge($third_image, $first_img_path, 0, 0, 0, 0, $first_img_width, $first_img_height, 100);
	imagecopymerge($third_image, $second_img_path, 0, $first_img_height, 0, 0, $second_img_width, $second_img_height, 100);
	
	return $third_image;
}

$img = my_merge_image("image1.png", "image2.png");
imagepng($img);
?>