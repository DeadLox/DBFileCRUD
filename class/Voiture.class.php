<?php
class Voiture extends BasicObject {
	public $nom;
	public $modele;
	public $couleur;
	public $nb_roue;

	public function __construct(){
		parent::__construct();
	}
}