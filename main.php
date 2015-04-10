<?php
include('controller/SiteController.php');

$site = new SiteController();

$site->readFile();
// family
$site->sortArrayFamilies();

// species
$site->uniqueArraySpecies();

// family
$site->addIndiceArrayUniqueFamily();
$site->findRelationSpecies();


//dabatabase insert family
$site->insertFamilies();

// database insert final_species.
$site->insertSpeciesRelations();

// show column fields
$site->filterArrayFeatureFields();

// feature
$site->uniqueArrayFeatureFields();

// database insert feature.
$site->insertFeatureFields();

// final species feature
$site->addIndiceArrayUniqueSpecies();

// relations final_species with feature
$site->findRelationFinalSpeciesFeature();

// database insert final species feature
$site->insertFinalSpeciesFeature();