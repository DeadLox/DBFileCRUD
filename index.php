<?php
require_once('include/autoload.php');

$storeMgr = StoreManager::getInstance();

// $test = new Article();
// $test->titre = "Encore un article";
// $test->texte = "Le texte de cet article";
// $test->performCreate();

// $voiture = new Voiture();
// $voiture->nom = "BMW";
// $voiture->modele = "320TD";
// $voiture->couleur = "Rouge";
// $voiture->performCreate();

// Util::dump($storeMgr->getAllList());
Util::dump($storeMgr->getList("Voiture"));
?>