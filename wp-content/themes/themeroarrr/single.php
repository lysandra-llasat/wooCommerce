<?php get_header() ?>
<main class="p_3">
    <div class="row">
        <!-- on ajouute un titre pour boen voir qu'on ne passe plus par index.php
    pour afficher l'article par single.php -->
        <h2> c'est mon article</h2>
        <div class="col-sm-8 bloc-main">
            <?php
            if (have_posts()) : while (have_posts()) : the_post();
                    get_template_part('content', get_post_format());
                endwhile;
            endif
            ?>

        </div>
        <?php get_sidebar() ?>
    </div>
</main>