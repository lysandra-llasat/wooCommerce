<?php

/*
Plugin Name: plugin competition geekroarrr

Description: Plugin pour le classement des competitions

Autor: lysandra llasat

Version: 1.0
*/

//on import notre fichier Ern_Database_Service.php

use MailPoetVendor\Doctrine\DBAL\Types\VarDateTimeType;

require_once plugin_dir_path(__FILE__) . "service/Compet_Database_service.php";
require_once plugin_dir_path(__FILE__) . "Joueur_List.php";
require_once plugin_dir_path(__FILE__) . "Compet_List.php";
require_once plugin_dir_path(__FILE__) . "Poule_List.php";
require_once plugin_dir_path(__FILE__) . "Match_List.php";

class Compet
{

    //appell du constructeur
    public function __construct()
    {
        register_activation_hook(__FILE__, array("Compet_Database_service", "create_db"));
        register_deactivation_hook(__FILE__, array("Compet_Database_service", "empty_db"));
        add_action('admin_menu', array($this, "add_menu_compet"));
    }

    public function add_menu_compet()
    {
        add_menu_page("les joueurs", "joueurs", "manage_options", "joueurCompetition", array($this, "mes_joueurs"), "dashicons-buddicons-replies", 40);
        add_submenu_page('joueurCompetition', 'Ajouter un client', 'ajouter', 'manage_options', 'addJoueur', [$this, 'mes_joueurs']);

        add_menu_page("les competitions", "competition", "manage_options", "Competition", array($this, "mes_competitions"), "dashicons-buddicons-forums", 50);
        add_submenu_page('Competition', 'Ajouter une competition', 'ajouter', 'manage_options', 'addcompet', [$this, 'mes_competitions']);

        add_menu_page("les poules", "poules", "manage_options", "poules", array($this, "mes_poules"), "dashicons-buddicons-topics", 51);
        add_submenu_page('poules', 'Ajouter un joueur dans une poule', 'ajouter', 'manage_options', 'addpoule', [$this, 'mes_poules']);

        add_menu_page("les matchs", "matchs", "manage_options", "matchs", array($this, "mes_matchs"), "dashicons-buddicons-activity", 52);
        add_submenu_page('matchs', 'crées les matchs', 'crées', 'manage_options', 'addmatch', [$this, 'mes_matchs']);
    }

    //element a afficher dans le menu client
    public function mes_joueurs()
    {

        //on va instancier la classe compet_Database_Service
        $db = new Compet_Database_service();
        // on récupère le titre de la page 
        echo "<h2>" . get_admin_page_title() . "</h2>";
        //on commence a construire la table avec les titre des colonnes
        //si la page dans laquelle on est est == ern-client (slug d ela page) on affiche la liste 
        if ($_GET["page"] == "joueurCompetition" || $_POST["send"] == "ok" || $_POST['action'] == 'delete-joueur') {
            //on va mettre une seconde condition 
            //si les données du formulaire sont présentes on execute la requete
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                //on execute la méthode save_client
                $db->save_joueur();
            }
            if (isset($_POST['action']) && $_POST['action'] == 'delete-joueur') {
                //on execute la méthode save_client
                $db->delete_joueur($_POST['delete-joueur']);
            }


            //on instancie la classe Joueur_List
            $table = new Joueur_List();
            //on appelle la méthde repare_items
            $table->prepare_items();
            //on genere le rendu html de la table grace a la méthode display
            //que l'on imbrique dans un formulaire 
            echo "<form method='post'>";
            $table->display();
            echo "</form>";
        } else {
            //on crée le formulaire d'ajout de client
            echo "<form method='post'>";
            //on ajoute un input de type hidden pour envoyer "ok" lorsqu'on poste le formulaire 
            //cette valeur "ok" nous sert de flag pour faire du traitement au dessus 
            echo "<input type='hidden' name='send' value='ok'>";
            //input nom
            echo "<div>" .
                "<label for='nom'>pseudo</label>" .
                "<input type='text' name='pseudo' class='widefat' required" .
                "</div>";

            //input email
            echo "<div>" .
                "<label for='email'>email</label>" .
                "<input type='text' name='email' class='widefat' required" .
                "</div>";

            // liste déroulante depuis la base de données

            $results = $db->findAllCompetition();
            if ($results) {
                echo "<select name='competition_id' >";
                // Boucle foreach pour itérer à travers les résultats
                foreach ($results as $row) {
                    echo " <option value='$row->id'> $row->name </option>";
                }
                echo "</select>";
            } else {
                echo "Aucun résultat trouvé.";
            }


            //input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        };
    }

    //element a afficher dans le menu competition
    public function mes_competitions()
    {

        //on va instancier la classe compet_Database_Service
        $db = new Compet_Database_service();
        // on récupère le titre de la page 
        echo "<h2>" . get_admin_page_title() . "</h2>";
        //on commence a construire la table avec les titre des colonnes
        //si la page dans laquelle on est est == ern-client (slug d ela page) on affiche la liste 
        if ($_GET["page"] == "Competition" || $_POST["send"] == "ok" || $_POST['action'] == 'delete-competition') {
            //on va mettre une seconde condition 
            //si les données du formulaire sont présentes on execute la requete
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                //on execute la méthode save_client
                $db->save_competition();
            }
            if (isset($_POST['action']) && $_POST['action'] == 'delete-competition') {
                //on execute la méthode save_client
                $db->delete_competition($_POST['delete-competition']);
            }


            //on instancie la classe Joueur_List
            $table = new Compet_List();
            //on appelle la méthde repare_items
            $table->prepare_items();
            //on genere le rendu html de la table grace a la méthode display
            //que l'on imbrique dans un formulaire 
            echo "<form method='post'>";
            $table->display();
            echo "</form>";
        } else {
            //on crée le formulaire d'ajout de client
            echo "<form method='post'>";
            //on ajoute un input de type hidden pour envoyer "ok" lorsqu'on poste le formulaire 
            //cette valeur "ok" nous sert de flag pour faire du traitement au dessus 
            echo "<input type='hidden' name='send' value='ok'>";
            //input nom
            echo "<div>" .
                "<label for='nom'>name</label>" .
                "<input type='text' name='name' class='widefat' required" .
                "</div>";


            //input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        };
    }

    public function mes_poules()
    {

        //on va instancier la classe compet_Database_Service
        $db = new Compet_Database_service();
        // on récupère le titre de la page 
        echo "<h2>" . get_admin_page_title() . "</h2>";
        //on commence a construire la table avec les titre des colonnes
        //si la page dans laquelle on est est == ern-client (slug d ela page) on affiche la liste 
        if ($_GET["page"] == "poules" || $_POST["send"] == "ok" || $_POST['action'] == 'delete-joueur') {
            //on va mettre une seconde condition 
            //si les données du formulaire sont présentes on execute la requete
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                //on execute la méthode save_client
                $db->save_poule();
            }
            if (isset($_POST['action']) && $_POST['action'] == 'delete-competition') {
                //on execute la méthode save_client
                $db->delete_competition($_POST['delete-competition']);
            }


            //on instancie la classe Poule_List
            $table = new Poule_List();
            //on appelle la méthde repare_items
            $table->prepare_items();
            //on genere le rendu html de la table grace a la méthode display
            //que l'on imbrique dans un formulaire 
            echo "<form method='post'>";
            $table->display();
            echo "</form>";
        } else {
            //on crée le formulaire d'ajout de client
            echo "<form method='post'>";
            //on ajoute un input de type hidden pour envoyer "ok" lorsqu'on poste le formulaire 
            //cette valeur "ok" nous sert de flag pour faire du traitement au dessus 
            echo "<input type='hidden' name='send2' value='continuer'>";
            //input nom

            $results = $db->findAllCompetition();
            if ($results) {

                // liste déroulante depuis la base de données
                echo "<span> veuillez choisir la compétition </span>";
                echo "<select name='competition_id' >";
                // Boucle foreach pour itérer à travers les résultats
                foreach ($results as $row) {
                    echo " <option value='$row->id'> $row->name </option>";
                }
                echo "</select>";

                //input submit
                echo "<div>" .
                    "<input type='submit' value='continuer'class='button button-primary'>" .
                    "</div>";
                echo "</form>";
            }
            if (isset($_POST['send2']) && $_POST['send2'] == 'continuer') {
                var_dump($_POST);


                echo "<form method='post'>";
                echo "<input type='hidden' name='competition_id' value='" . $_POST["competition_id"] . "'>";
                echo "<input type='hidden' name='send' value='ok'>";
                //on recupere le nom des poules
                echo "<span> veuillez choisir la poule du joueur </span>";
                $poule = $db->findAllGroupe();

                echo "<select name='goupe_id' >";

                foreach ($poule as $row) {
                    var_dump($row->id);
                    echo " <option value=" . $row->id .  "  > " . $row->label  . " </option>";
                }
                echo "</select>";

                //on recupere le nom des joueur filtre par l'id des competitions.
                $player = $db->get_player_by_compet($_POST["competition_id"]);

                echo "<span> veuillez choisir le joueur </span>";
                echo "<select name='joueur_id' >";

                foreach ($player as $rowplayer) {
                    echo " <option value='$rowplayer->id'> $rowplayer->pseudo </option>";
                }
                echo "</select>";

                echo "<div>" .
                    "<input type='submit' value='Ajouter' class='button button-primary'>" .
                    "</div>";
                echo "</form>";
            }
        }
    }

    public function mes_matchs()
    {

        //on va instancier la classe compet_Database_Service
        $db = new Compet_Database_service();
        // on récupère le titre de la page 
        echo "<h2>" . get_admin_page_title() . "</h2>";
        //on commence a construire la table avec les titre des colonnes
        //si la page dans laquelle on est est == ern-client (slug d ela page) on affiche la liste 
        if ($_GET["page"] == "match" || $_POST["send"] == "ok" || $_POST['action'] == 'delete-match') {
            //on va mettre une seconde condition 
            //si les données du formulaire sont présentes on execute la requete
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                //on execute la méthode save_client
                $db->save_match();
            }

            //on instancie la classe Poule_List
            $table = new Match_List();
            //on appelle la méthde repare_items
            $table->prepare_items();
            //on genere le rendu html de la table grace a la méthode display
            //que l'on imbrique dans un formulaire 
            echo "<form method='post'>";
            $table->display();
            echo "</form>";
        } else {
            //on crée le formulaire d'ajout de client
            echo "<form method='post'>";
            //on ajoute un input de type hidden pour envoyer "ok" lorsqu'on poste le formulaire 
            //cette valeur "ok" nous sert de flag pour faire du traitement au dessus 
            echo "<input type='hidden' name='send2' value='continuer'>";
            //input nom

            $results = $db->findAllCompetition();
            if ($results) {

                // liste déroulante depuis la base de données
                echo "<span> veuillez choisir la compétition </span>";
                echo "<select name='competition_id' >";
                // Boucle foreach pour itérer à travers les résultats
                foreach ($results as $row) {
                    echo " <option value='$row->id'> $row->name </option>";
                }
                echo "</select>";

                //input submit
                echo "<div>" .
                    "<input type='submit' value='continuer'class='button button-primary'>" .
                    "</div>";
                echo "</form>";
            }
            if (isset($_POST['send2']) && $_POST['send2'] == 'continuer' || $_POST['send3'] == 'ok') {

                echo "<form method='post'>";
                echo "<input type='hidden' name='competition_id' value='" . $_POST["competition_id"] . "'>";
                echo "<input type='hidden' name='send3' value='ok'>";
                //on recupere le nom des poules
                echo "<span> veuillez choisir la poule du joueur </span>";
                $poule = $db->findAllGroupe();

                echo "<select name='groupe_id' >";

                foreach ($poule as $row) {

                    echo " <option value=" . $row->id .  "  > " . $row->label  . " </option>";
                }
                echo "</select>";

                //input submit
                echo "<div>" .
                    "<input type='submit' value='continuer'class='button button-primary'>" .
                    "</div>";
                echo "</form>";
            }
            if (isset($_POST['send3']) && $_POST['send3'] == 'ok') {

                //on recupere le nom des joueurs
                echo "<form method='post'>";
                echo "<input type='hidden' name='competition_id' value='" . $_POST["competition_id"] . "'>";
                echo "<input type='hidden' name='groupe_id' value='" . $_POST["groupe_id"] . "'>";
                echo "<span> veuillez choisir la poule du joueur </span>";
                $poule = $db->get_player_by_groupe($_POST['competition_id'], $_POST['groupe_id']);

                echo "<select name='joueur_1' >";

                foreach ($poule as $row) {

                    echo " <option value=" . $row->id .  "  > " . $row->pseudo  . " </option>";
                }
                echo "</select>";

                echo "<span> veuillez choisir la poule du joueur </span>";
                $poule = $db->get_player_by_groupe($_POST['competition_id'], $_POST['groupe_id']);

                echo "<select name='joueur_2' >";

                foreach ($poule as $row) {

                    echo " <option value=" . $row->id .  "  > " . $row->pseudo  . " </option>";
                }
                echo "</select>";



                // Boutons radio pour le choix de la poule
                echo "<div>";
                echo "<br>";
                echo "<div>";
                echo "<span> ce match fait-t-il partie de la poule ? </span>";
                echo "<br>";
                echo "<input type='radio' id='pouleYes' name='poule' value='1' />";
                echo "<label for='pouleYes'>Oui</label>";
                echo "</div>";

                echo "<div>";
                echo "<input type='radio' id='pouleNo' name='poule' value='0' />";
                echo "<label for='pouleNo'>Non</label>";

                //pour recupere la date 
                echo "<br>";
                echo "<br>";
                echo "<label for='date'>Date :</label>";
                echo "<input type='date' name='date' id='date' />";
                echo "<br>";
                echo "<br>";
                echo "<div>" .
                    "<input type='hidden' name='send' value='ok'>" .
                    "<input type='submit' value='continuer'class='button button-primary'>" .
                    "</div>";

                echo "</form>";
            }
        }
    }
}


new Compet();
