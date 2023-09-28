<?php

class Compet_Database_service
{
    public function __construct()
    {
    }
    //fonction qui va créer une nouvelle table dan sle db
    public static function create_db()
    {
        //on appelle la variable global $wpdb
        global $wpdb;
        //creation de la table en BDD
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compet_groupe(
        id INT AUTO_INCREMENT PRIMARY KEY,
        `label` VARCHAR(150) NOT NULL)");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compet_competition(
        id INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(150) NOT NULL)");


        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compet_joueur(
        id INT AUTO_INCREMENT PRIMARY KEY,
        `pseudo` VARCHAR(150) NOT NULL,
        `email` VARCHAR(150) NOT NULL,
        `competition_id` INT NOT NULL,
        FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}compet_competition(id))");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compet_poule(
        id INT AUTO_INCREMENT PRIMARY KEY,
        `joueur_id` INT NOT NULL,
        `groupe_id` INT NOT NULL,
        `competition_id` INT NOT NULL,
        FOREIGN KEY (joueur_id) REFERENCES {$wpdb->prefix}compet_joueur(id),
        FOREIGN KEY (groupe_id) REFERENCES {$wpdb->prefix}compet_groupe(id),
        FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}compet_competition(id))");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compet_match(
        id INT AUTO_INCREMENT PRIMARY KEY,
        `date` INT NOT NULL,
        `is_poule` BOOLEAN DEFAULT false,
        `joueur1_id` INT NOT NULL,
        `joueur2_id` INT NOT NULL,
        FOREIGN KEY (joueur1_id) REFERENCES {$wpdb->prefix}compet_joueur(id),
        FOREIGN KEY (joueur2_id) REFERENCES {$wpdb->prefix}compet_joueur(id)
        )");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compet_resultat(
            id INT AUTO_INCREMENT PRIMARY KEY,
            `points` INT NOT NULL,
            `score` INT NOT NULL,
            `joueur_id` INT NOT NULL,
            `match_id` INT NOT NULL,
            FOREIGN KEY (joueur_id) REFERENCES {$wpdb->prefix}compet_joueur(id),
            FOREIGN KEY (match_id) REFERENCES {$wpdb->prefix}compet_match(id)
            )");
    }

    //requete pour recupere la liste des joueur
    public function findAll()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT joueur.id, joueur.pseudo, joueur.email, compet.name 
                                    FROM {$wpdb->prefix}compet_joueur AS joueur
                                    INNER JOIN {$wpdb->prefix}compet_competition AS compet
                                    ON joueur.competition_id = compet.id");
        return $res;
    }

    public function findAllMatch()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT joueur.pseudo,joueur2.pseudo AS pseudo2, joueur.id ,joueur2.id  matchs.date, matchs.is_poule
                                    FROM {$wpdb->prefix}compet_match AS matchs
                                    INNER JOIN {$wpdb->prefix}compet_joueur AS joueur
                                    ON matchs.joueur1_id = joueur.id
                                    INNER JOIN {$wpdb->prefix}compet_joueur AS joueur2
                                    ON matchs.joueur2_id = joueur2.id
                                     ");
        return $res;
    }

    //requete pour recupere la liste des competitions
    public function findAllCompetition()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}compet_competition");
        return $res;
    }

    //requete pour recupere la liste des poules
    public function findAllpoule()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT joueur.pseudo, joueur.email, compet.name, groupe.label, poule.id
                                        FROM {$wpdb->prefix}compet_poule AS poule
                                        Inner JOIN {$wpdb->prefix}compet_groupe AS groupe
                                        ON poule.groupe_id = groupe.id
                                        Inner JOIN {$wpdb->prefix}compet_joueur AS joueur
                                        ON poule.joueur_id = joueur.id
                                        Inner JOIN {$wpdb->prefix}compet_competition AS compet
                                        ON poule.competition_id = compet.id
                                        ");
        return $res;
    }

    public function findAllGroupe()
    {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}compet_groupe");
        return $res;
    }

    public function get_player_by_compet($groupeID)
    {

        global $wpdb;
        $res = $wpdb->get_results("SELECT *
                                    FROM {$wpdb->prefix}compet_joueur
                                    WHERE competition_id = $groupeID");

        return $res;
    }

    public function get_player_by_groupe($competitionID, $groupeID)
    {

        global $wpdb;
        $res = $wpdb->get_results("SELECT joueur.id, joueur.pseudo, poule.competition_id, poule.groupe_id
                                    FROM {$wpdb->prefix}compet_poule AS poule
                                    Inner JOIN {$wpdb->prefix}compet_groupe AS groupe
                                    ON poule.groupe_id = groupe.id
                                    Inner JOIN {$wpdb->prefix}compet_joueur AS joueur
                                    ON poule.joueur_id = joueur.id
                                    Inner JOIN {$wpdb->prefix}compet_competition AS compet
                                    ON poule.competition_id = compet.id
                                    WHERE poule.competition_id = $competitionID AND groupe_id = $groupeID");

        return $res;
    }




    //fonction pour ajouter un joueur
    public function save_joueur()
    {
        global $wpdb;
        //dans une variable on va récupere les donnée du fomulaire
        $data = [
            "pseudo" => $_POST["pseudo"],
            "email" => $_POST["email"],
            "competition_id" => $_POST["competition_id"]

        ];
        //on vérifie que le client n'existe pas deja
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}compet_joueur WHERE email = '" . $data["email"] . "'");
        if (is_null($row)) {
            //si le cient n'existe pas on l'insere dans la table
            //méthode insert: 1er parametre le nom de la table, 2eme parametre les donné à insérer(array)
            $wpdb->insert("{$wpdb->prefix}compet_joueur", $data);
        } else {
        }
    }

    // fonction pour supprimer un joueur 
    public function delete_joueur($ids)
    {
        global $wpdb;
        //condition pour verifier que $ids existe dans la base de donne 
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        //on fait une requette de suppresion
        $wpdb->query("DELETE FROM {$wpdb->prefix}compet_joueur where id IN (" . implode(",", $ids) . ")");
    }



    //fonction pour ajouter une competition
    public function save_competition()
    {
        global $wpdb;
        //dans une variable on va récupere les donnée du fomulaire
        $data = [
            "name" => $_POST["name"]

        ];
        //on vérifie que le client n'existe pas deja
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}compet_competition WHERE name = '" . $data["name"] . "'");
        if (is_null($row)) {
            //si le cient n'existe pas on l'insere dans la table
            //méthode insert: 1er parametre le nom de la table, 2eme parametre les donné à insérer(array)
            $wpdb->insert("{$wpdb->prefix}compet_competition", $data);
        } else {
        }
    }

    // fonction pour supprimer une competition
    public function delete_competition($ids)
    {
        global $wpdb;
        //condition pour verifier que $ids existe dans la base de donne 
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        //on fait une requette de suppresion
        $wpdb->query("DELETE FROM {$wpdb->prefix}compet_competition where id IN (" . implode(",", $ids) . ")");
    }

    //fonction pour ajouter un joueur a une poule
    public function save_poule()
    {
        global $wpdb;

        //dans une variable on va récupere les donnée du fomulaire
        $data = [
            "joueur_id" => intval($_POST["joueur_id"]),
            "groupe_id" => $_POST["goupe_id"],
            "competition_id" => intval($_POST["competition_id"])
        ];
        var_dump($data);
        //on vérifie que le joueur n'existe pas deja
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}compet_poule WHERE joueur_id = " . $data["joueur_id"]);
        if (is_null($row)) {
            //si le cient n'existe pas on l'insere dans la table
            //méthode insert: 1er parametre le nom de la table, 2eme parametre les donné à insérer(array)
            $wpdb->insert("{$wpdb->prefix}compet_poule", $data);
        } else {
        }
    }

    public function save_match()
    {
        global $wpdb;
        var_dump($_POST);
        //dans une variable on va récupere les donnée du fomulaire
        $data = [
            "competition_id" => $_POST["competition_id"],
            "groupe_id" => $_POST["groupe_id"],
            "joueur_1" => $_POST["joueur_1"],
            "joueur_2" => $_POST["joueur_2"],
            "poule" => $_POST["poule"],
            "date" => $_POST["date"]
        ];
        //on vérifie que le client n'existe pas deja
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}compet_joueur WHERE email = '" . $data["email"] . "'");
        if (is_null($row)) {
            //si le cient n'existe pas on l'insere dans la table
            //méthode insert: 1er parametre le nom de la table, 2eme parametre les donné à insérer(array)
            $wpdb->insert("{$wpdb->prefix}compet_joueur", $data);
        } else {
        }
    }
}
