<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include('class/Database.php');

class SiteController {

    private $file;
    private $flag;
    private $flag_item_family;
    private $array_families;
    private $array_unique_family;
    private $array_species;
    private $array_unique_species;
    private $array_relations_species;
    private $array_indice_unique_family;
    private $array_indice_unique_species;
    private $db;
    private $array_fields;
    private $array_feature_fields;
    private $array_unique_feature_fields;
    private $array_final_species_feature;
    private $array_relations_final_species_feature;

    /*
     * Build constructor class.
     */

    public function __construct() {
        // property database
        $this->db = new Database();
        $this->db->connect();
        // property ready file
        $this->file = fopen(name_file, "r");
        // property flag
        $this->flag = false;
        $this->flag_item_family = false;
        // property family
        $this->array_families = array();
        $this->array_unique_family = array();
        // property species
        $this->array_species = array();
        $this->array_unique_species = array();
        $this->array_relations_species = array();
        $this->array_indice_unique_family = array();
        $this->array_indice_unique_species = array();
        // property feature
        $this->array_fields = array();
        $this->array_feature_fields = array();
        $this->array_unique_feature_fields = array();
        $this->array_final_species_feature = array();
        // property final specie feature
        $this->array_relations_final_species_feature = array();
    }

    /**
     * Read row by row file.
     */
    public function readFile() {
        $array_data = array();
        $i = 0;
        try {
            while (!feof($this->file)) {
                $array_data = fgetcsv($this->file);
                $string_species = $array_data[0];
                $string_family = $array_data[1];
                $initial_heigth = $array_data[2];
                $final_heigth = $array_data[3];
                $initial_precipitation_rate = $array_data[4];
                $final_precipitation_rate = $array_data[5];
                if (!$this->flag) {
                    $this->flag = true;
                    $this->array_fields = $array_data;
                } else {

                    if ((strcmp($string_family, "?") == 0)) {
                        $string_family = "Undefined";
                    }
                    $this->array_families[][] = $string_family;
                    $this->array_species[] = array($string_species, $string_family, $initial_heigth, $final_heigth, $initial_precipitation_rate, $final_precipitation_rate);
                    $this->array_final_species_feature[] = array(array_slice($array_data, 6), $i);
                }
                $i++;
            }
            fclose($this->file);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Get the array fields
     * @return array
     */
    public function getArrayFields() {
        return $this->array_fields;
    }

    /**
     * Get the array unique species
     * @return array
     */
    public function getArrayUniqueSpecies() {
        return $this->array_unique_species;
    }

    /**
     * Get array indice unique family.
     * @return array 
     */
    public function getArrayIndiceUniqueFamily() {
        return $this->array_indice_unique_family;
    }

    /**
     * Get array relations species.
     * @return array
     */
    public function getArrayRelationsSpecies() {
        return $this->array_relations_species;
    }

    /**
     * Get array feature fields
     * @return array
     */
    public function getArrayFeatureFields() {
        return $this->array_feature_fields;
    }

    /**
     * Get the unique family.
     * @return array
     */
    public function getArrayUniqueFamily() {
        return $this->array_unique_family;
    }

    /**
     * Get unique feature field.
     * @return array
     */
    public function getArrayUniqueFeatureFields() {
        return $this->array_unique_feature_fields;
    }

    /**
     * Get final species
     * @return array
     */
    public function getArrayFinalSpeciesFeature() {
        return $this->array_final_species_feature;
    }

    /**
     * Get property indice unique species.
     * @return array
     */
    public function getArrayIndiceUniqueSpecies() {
        return $this->array_indice_unique_species;
    }

    /**
     * Get property relations final species features.
     * @return array
     */
    public function getArrayRelationsFinalSpeciesFeature() {
        return $this->array_relations_final_species_feature;
    }

    /**
     * Sort and get unique families.
     */
    public function sortArrayFamilies() {
        foreach ($this->array_families as $family) {
            if (!in_array($family, $this->array_unique_family)) {
                $this->array_unique_family[] = $family;
            }
        }
        sort($this->array_unique_family);
    }

    /**
     * Show each row custom families.
     */
    public function showArrayFamilies() {
        foreach ($this->array_unique_family as $unique_family) {
            echo "<pre>";
            print_r($unique_family);
            echo "</pre>";
        }
    }

    /**
     * 
     */
    public function uniqueArraySpecies() {
        foreach ($this->array_species as $species) {
            if (!in_array($species, $this->array_unique_species)) {
                $this->array_unique_species[] = $species;
            }
        }
    }

    /**
     * Find Relation array_species and array_family
     */
    public function findRelationSpecies() {
        foreach ($this->array_unique_species as $unique_species) {
            foreach ($this->array_indice_unique_family as $unique_family) {
                if (strcmp($unique_species[1], $unique_family[0]) == 0) {
                    $this->array_relations_species[] = array($unique_species[0], $unique_family[1], $unique_species[2], $unique_species[3], $unique_species[4], $unique_species[5]);
                }
            }
        }
    }

    /**
     * Show array_species and array_family use foreach
     */
    public function showArrayRelationSpeciesUseForEach() {
        foreach ($this->array_relations_species as $relation_species) {
            echo "<pre>";
            print_r($relation_species);
            echo "</pre>";
        }
    }

    /**
     * Add column for primary key.
     */
    public function addIndiceArrayUniqueFamily() {
        $i = 0;
        foreach ($this->array_unique_family as $unique_family) {
            $this->array_indice_unique_family[] = array($unique_family[0], $i);
            $i++;
        }
    }

    /**
     * insert table families.
     */
    public function insertFamilies() {
        foreach ($this->array_unique_family as $array_unique) {
            $this->db->select('family', 'id_family', NULL, 'name_family=' . $array_unique[0]);
            if ((count($this->db->getResult()) >= 0)) {
                $this->db->insert('family', array('name_family' => $array_unique[0]));
            }
        }
    }

    /**
     * insert table final species.
     */
    public function insertSpeciesRelations() {
        foreach ($this->array_relations_species as $relation_specie) {
            $this->db->select('final_species', 'id_final_species', NULL, 'name_final_species="' . $relation_specie[0] . '" and family_id_family="' . $relation_specie[1] . '" and initial_heigth="' . $relation_specie[2] . '" and final_heigth="' . $relation_specie[3] . '" and initial_precipitation_rate="' . $relation_specie[4] . '" and final_precipitation_rate="' . $relation_specie[5] . '"');
            if ((count($this->db->getResult() >= 0))) {
                $this->db->insert('final_species', array('name_final_species' => $relation_specie[0], 'family_id_family' => $relation_specie[1], 'initial_heigth' => $relation_specie[2], 'final_heigth' => $relation_specie[3], 'initial_precipitation_rate' => $relation_specie[4], 'final_precipitation_rate' => $relation_specie[5]));
            }
        }
    }

    /**
     * insert table feature.
     */
    public function insertFeatureFields() {
        foreach ($this->array_feature_fields as $feature_fields) {
            $this->db->select('feature', 'id_feature', NULL, 'name_feature=' . $feature_fields[0]);
            if ((count($this->db->getResult() >= 0))) {
                $this->db->insert('feature', array('name_feature' => $feature_fields[0]));
            }
        }
    }

    /**
     * insert table final species feature.
     */
    public function insertFinalSpeciesFeature() {
        foreach ($this->array_relations_final_species_feature as $final_specie_feature) {
            echo "<pre>";
            print_r($final_specie_feature);
            echo "</pre>";
            //$this->db->select('final_species', 'id_final_species', NULL, 'name_final_species="' . $relation_specie[0] . '" and family_id_family="' . $relation_specie[1] . '" and initial_heigth="' . $relation_specie[2] . '" and final_heigth="' . $relation_specie[3] . '" and initial_precipitation_rate="' . $relation_specie[4] . '" and final_precipitation_rate="' . $relation_specie[5] . '"');
            
            $this->db->select('final_species_feature', 'id_final_species_feature', NULL, 'final_species_id_final_species="' . $final_specie_feature[0] . '" and feature_id_feature="' . $final_specie_feature[1] . '"');
            if ((count($this->db->getResult() >= 0))) {
                $this->db->insert('final_species_feature', array('final_species_id_final_species' => $final_specie_feature[0], 'feature_id_feature' => $final_specie_feature[1]));
            }
        }
    }

    /**
     * Filter feature necesary.
     */
    public function filterArrayFeatureFields() {
        $i = 0;
        $array_aux_features = array_slice($this->array_fields, 6);
        foreach ($array_aux_features as $feature_field) {
            $this->array_feature_fields[] = array($feature_field, $i);
            $i++;
        }
    }

    /**
     * Show each item.
     * @param array $array
     */
    public function showArray($array) {
        foreach ($array as $item) {
            echo "<pre>";
            var_dump($item);
            echo "</pre>";
        }
    }

    /**
     * Filter unique feature field.
     */
    public function uniqueArrayFeatureFields() {
        foreach ($this->array_feature_fields as $feature_field) {
            if (!in_array($feature_field, $this->array_unique_feature_fields)) {
                $this->array_unique_feature_fields[] = $feature_field;
            }
        }
    }

    /**
     * Add indice array unique species.
     */
    public function addIndiceArrayUniqueSpecies() {
        $i = 0;
        foreach ($this->array_unique_species as $species) {
            $this->array_indice_unique_species[] = array($species[0], $i);
            $i++;
        }
    }

    /**
     * Find relation betwen array final species and array features
     */
    public function findRelationFinalSpeciesFeature() {
        foreach ($this->array_final_species_feature as $final_specie_feature) {
            foreach ($this->array_unique_feature_fields as $feature_field) {
                if (strcmp($final_specie_feature[1], ++$feature_field[1]) == 0) {
                    $i = 1;
                    foreach ($final_specie_feature[0] as $specie_feature) {
                        if (strcmp($specie_feature, "x") == 0) {
                            $this->array_relations_final_species_feature[] = array($final_specie_feature[1], $i);
                        }
                        $i++;
                    }
                }
            }
        }
    }

}
