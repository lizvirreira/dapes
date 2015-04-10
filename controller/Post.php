                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php

include('../class/Database.php');
$db = new Database();

if (isset($_POST['submit'])) {//to run PHP script on submit
    if (isset($_POST['check_list']) || isset($_POST['text_list'])) {
// Loop to store and display values of individual checked checkbox.
        $db->connect();
        $array_aux = array();
        foreach ($_POST['check_list'] as $selected) {
            
            $sql = "select distinct name_final_species, name_family
                    from final_species   
                    inner join final_species_feature	
                    on final_species_id_final_species = id_final_species
                    inner join family
                    on family_id_family = id_family
                    where feature_id_feature =".$selected. " or ( initial_heigth = " . $_POST['text_list'][0]
                    . " or final_heigth = " . $_POST['text_list'][1] . " or initial_precipitation_rate = " . $_POST['text_list'][2]
                    . " or final_precipitation_rate =" . $_POST['text_list'][3]
                    .")";
            
            $db->sql($sql);
        }
        $rows = $db->getResult();
        $i = 0;
        echo "<table>";
        echo "<th>";
        echo "Fila";
        echo "</th>";
        echo "<th>";
        echo "Especie";
        echo "</th>";
        echo "<th>";
        echo "Familia";
        echo "</th>";
        foreach ($rows as $row):
            echo "<tr>";
            echo "<td>";
            echo ++$i;
            echo "</td>";
            foreach ($row as $item):
                echo "<td>";
                echo $item;
                echo "<br >";
            endforeach;
            echo "</td>";
            echo "</tr>";
        endforeach;
        echo "</table>";
    }
}
?>