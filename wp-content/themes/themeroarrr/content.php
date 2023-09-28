<!-- ous avons acces ici aux valeur renvoyer par the post()
nous avons donc acces a tout un tas d'information
(the_title(), the_content(), ...) -->

<h4 class=" titre text_primary blog-post-title"><?php the_title(); ?></h4>
<p>
    <?php the_date(); ?> par <a href="#"> <?php the_author() ?></a>
</p>
<?php the_content(); ?>