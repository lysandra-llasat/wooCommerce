<?php
//dans certaines versions de WP il n'arrive pas à etendre la class WP_List_Table
//pour ce cas il faut charger manuellement cette classe
if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
}
//on import notre classe de Service
require_once plugin_dir_path(__FILE__) . "service/Compet_Database_service.php";
class Compet_List extends WP_List_Table
{
    //on va créer une variable en private qui va contenir l'instance de notre service
    private $dal;
    //1er: on déclare le constructeur
    public function __construct() //on déclare leconstructeur
    {
        //on va surcharger le constructeur de la class parente WP_List_Table
        //pour redéfinir le nom de la table (singulier et au pluriel)
        parent::__construct(
            array(
                "singular" => __("Client"),
                "plural" => __("Clients")
            )
        );
        //on instancie notre service
        $this->dal = new Compet_Database_service();
    }
    //2ème: on surcharge la méthode prepare_items()
    public function prepare_items() //fonction du parent pour préparer notre liste
    {
        //on va définir toutes nos variables
        $columns = $this->get_columns(); //on va chercher les colonnes
        $hidden = $this->get_hidden_columns(); //on ajoute cette variable si on veux cacher des colonnes
        $sortable = $this->get_sortable_columns(); //on ajoute cette variable si on veux trier des colonnes
        //PAGINATION
        $perPage = $this->get_items_per_page("clients_per_page", 10); //on va chercher le nombre d'éléments par page
        $currentPage = $this->get_pagenum(); //on va chercher le numéro de la page courante
        //LES DONNEES
        $data = $this->dal->findAllCompetition(); //on va chercher les données dans la base de données
        $totalPage = count($data); //on va compter le nombre de données
        //TRI
        //&$this : pour faire référence à notre classe
        usort($data, array(&$this, "usort_reorder")); //on va trier les données
        //on va découper les données en fonction de la page courante et du nombre d'éléments par page
        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        //on va définir les valeurs de la pagination
        $this->set_pagination_args([
            "total_items" => $totalPage, //on passe le nombre total d'éléments
            "per_page" => $perPage //on passe le nombre d'éléments par page
        ]);
        //on construit les titres des colonnes
        $this->_column_headers = array($columns, $hidden, $sortable); //on construit les entêtes des colonnes
        //on alimente les données
        $this->items = $paginationData; //on alimente les données
    }
    //3ème: on surcharge la méthode get_columns()
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox"/>',
            'id' => 'id',
            'name' => 'Name'
            

        ];
        return $columns;
    }
    //4ème: on surcharge la méthode get_hidden_columns()
    public function get_hidden_columns()
    {
        // return [];
        //exemple si on veux cacher la colonne id
        return ['id' => 'id',];
    }
    //fonction pour le tri
    public function usort_reorder($a, $b)
    {
        //si je passe un paramètre de tri dans l'url
        //sinon on tri par defaut
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        //idem pour l'ordre de tri
        $order = (!empty($_GET["order"])) ? $_GET["order"] : "desc";
        $result = strcmp($a->$orderBy, $b->$orderBy); //on compare les 2 valeurs
        return ($order === "asc") ? $result : -$result; //on retourne le résultat si asc sinon on inverse le resultat
    }
    //5ème: on surcharge la méthode column_default()
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'name':
            

                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }
    //6ème: on surcharge la méthode get_sortable_columns()
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'name' => ['name', true]
            

        ];
        return $sortable;
    }
    //7eme: on surcharge la méthode column_cb()
    public function column_cb($item)
    {
        $item = (array) $item; //on cast l'objeten pableau (pour ppouvoir utiliser la méthode sprintf)

        return sprintf(
            '<input type="checkbox" name="delete-client[]" value="%s"/>',
            $item["id"]
        );
    }

    //8eme: on surcharge la méthode get_bulk_action()
    public function get_bulk_actions()
    {
        $action = [
            "delete-client" => __("delete")
        ];
        return $action;
    }
}
