<footer>
    <div class="foot-orange">
        <div>
            <h3> Adresse</h3>
            <p> 9 des sable d’or
                22240 Plurien
            </p>
        </div>
        <div>
            <h3> Contact</h3>
            <p> <?php echo get_bloginfo('admin_email'); ?> </p>
            <p> numéro: 05.66.32.89 </p>

        </div>


    </div>
    <div class="footer-gris">
        <div>
            <h2 class="title"><?php echo get_bloginfo('name') ?></h2>
         

        </div>
        <div> <?php wp_nav_menu(array(
                    "theme_location" => "menu-footer", //on indique le menu a afficher
                    "container" => "ul", //on indique que le menu seras dans une balise nav 
                    "container_class" => "list-group", // on ajoute des class bootstrap
                    "menu_class" => "list-group-item", //on ajoute des class bootstap
                    "menu_id" => "menu-principal", //on ajoute un id 
                    "walker" => new Simple_menu() //récupération de notre template du menu 
                )) ?>
        </div>
    </div class="foot-noir">
    <div>
        <span>Copyright 2022-2023 - Tout droit réservé - Mentions légale </span>
    </div>
    <?php

    ?>
    ?>
</footer>

</body>