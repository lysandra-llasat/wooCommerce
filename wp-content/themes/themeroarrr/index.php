<!-- on appelle notre header avec la fonction native get_hader -->
<?php
get_header();
?>

<div>
    <div>
        <?php
        //si j'ai au mois un "post", je boucle dessus pour recuperer chaque "post"
        if (have_posts()) : while (have_posts()) : the_post();
                //je récupére contente.php auquel je lui donne les infos de "post"
                get_template_part('content', get_post_format());
            //on ferme la boucle while
            endwhile;
        //on fere le if
        endif;
        ?>

    </div>
</div>
<?php
get_footer();
?>