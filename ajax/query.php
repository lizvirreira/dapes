<?php

header('content-type: application/json; charset=utf-8');
include('../class/Database.php');

//if (isset($_POST['submit'])) {//to run PHP script on submit
if (isset($_POST['check_list'])) {
    $check_list = json_decode($_POST['check_list']);
    print_r($check_list);
    print_r($_POST);
// Loop to store and display values of individual checked checkbox.
    $db = new Database();
    $db->connect();
    foreach ($_POST['check_list'] as $selected) {
        $sql = "select distinct name_final_species, name_family
                    from final_species   
                    inner join final_species_feature	
                    on final_species_id_final_species = id_final_species
                    inner join family
                    on family_id_family = id_family
                    where feature_id_feature =".$selected;

        $db->sql($sql);
    }
    $rows = $db->getResult();
    echo json_encode($res);
}
//}
    