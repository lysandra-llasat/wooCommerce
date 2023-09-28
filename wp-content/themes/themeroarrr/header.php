<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>theme custum</title>
    <!-- import de bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- on importe notre style.css -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style.css"; ?>">

</head>

<body>
    <header>
        <div class="black-footer">

            <h2 class="title"><?php echo get_bloginfo('name') ?></h2>

        </div>
        <div class="ligne-white">
            <span></span>
        </div>
        <div class="grey-footer">
            <img class="logo-header" src="<?php echo get_template_directory_uri() . "/img/logo.png"; ?>" alt="">

        </div>
        <?php
        wp_nav_menu(array(
            "theme_location" => "menu-sup", //on indique le menu a afficher
            "container" => "ul", //on indique que le menu seras dans une balise nav 
            "container_class" => "nav nav-tabs", // on ajoute des class bootstrap
            "menu_class" => "nav nav-tabs", //on ajoute des class bootstap
            "menu_id" => "menu-principal", //on ajoute un id 
            "walker" => new Simple_menu() //récupération de notre template du menu 
        ))
        ?>

    </header>
