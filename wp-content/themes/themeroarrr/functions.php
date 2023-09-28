<?php
//1 on enregistre le menu

function register_menu()
{
    //fonction native de wordpress qui permet d'enregistre le menu 
    register_nav_menus(
        array(
            'menu-sup' => __('Main'), // __()permet de traduire le mot dans les different langage
            'menu-footer' => ('footer menu')
        )
    );
}

//2.on initialise le menu
add_action('init', 'register_menu');
//permet d'executer une fonction a un moment précis

// on design le menu dans le thème
class Simple_menu extends Walker_Nav_Menu
{
    //on appelle et sur-charge la méthode start_el()
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        //$output: ce qui va etre aficher le template 
        //$data_objet: servira a recuperer les infos du menu(titre, lien, ect...)

        //1) on recupere le data du menu dans des variables
        $title = $data_object->title; //recupere les titres 
        $permalink = $data_object->url;

        //2) on construit le template 
        $output .= "<li class='nav-item'>";
        $output .= "<a class='nav-link color-nav' href='$permalink'>"; //o ouvre un a et on lui donne $permalink
        $output .= $title;
        $output .= "<a/>";
    }
    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</li>"; //on ferme la div
    }
}
