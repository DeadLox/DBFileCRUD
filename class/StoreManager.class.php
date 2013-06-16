<?php
class StoreManager {
	public $store;
	public $listModel;

	private static $URID = "cle";
	private static $CURRENT_ID = 0;
	private static $DB_FOLDER = "db";
	private static $SLASH = "/";
	private static $EXT = ".txt";
	private static $RETURN = "\r\n";

	private static $instance;

	private function __construct(){
		$this->initStore();
		BasicObject::$storeMgr = $this;
	}

	public static function getInstance(){
		if (StoreManager::$instance == null) {
			StoreManager::$instance = new StoreManager();
		}
		return StoreManager::$instance;
	}

	/**
	 * Parse le Store pour récupérer les objets
	 */
	private function initStore(){
		$this->scanModel();
		if (sizeof($this->listModel) > 0) {
			foreach ($this->listModel as $model) {
				$handle = fopen(StoreManager::$DB_FOLDER.StoreManager::$SLASH.$model.StoreManager::$EXT, "r");
				if ($handle)
				{
					while (!feof($handle))
					{
						$buffer = fgets($handle);
						if ($buffer != "") {
							$this->addtoStoreList($this->createObject($buffer));
						}
					}
					fclose($handle);
				}
			}
		}
	}

	/**
	 * Scan le dossier des modèles
	 */
	public function scanModel(){
	  $MyDirectory = opendir(StoreManager::$DB_FOLDER) or die('Erreur');
		while($Entry = @readdir($MyDirectory)) {
			if(!is_dir(StoreManager::$DB_FOLDER.StoreManager::$SLASH.$Entry)&& $Entry != '.' && $Entry != '..') {
				$this->listModel[] = str_replace(StoreManager::$EXT, "", $Entry);
			}
		}
	  closedir($MyDirectory);
	}

	/**
	 * Créé un Objet à partir de la ligne du Store
	 */
	private function createObject($line){
		$className = substr($line, 1, strpos($line, " ")-1);
		preg_match_all("| ([a-zA-Z0-9_-]{1,})=\"(.+?)\"|", $line, $attributes);
		$attributesName = $attributes[1];
		$attributesValue = $attributes[2];
		$object = new $className();
		foreach ($attributesName as $key => $attr) {
			if ($attr == "id") {
				$object->$attr = $attributesValue[$key];
				StoreManager::$CURRENT_ID = $attributesValue[$key];
			} else {
				$object->$attr = $attributesValue[$key];
			}
		}
		return $object;
	}

	public function incrementID(){
		StoreManager::$CURRENT_ID++;
	}

	/**
	 * Permet d'ajouter un objet au Store
	 */
	public function add($object){
		$className = get_class($object);
		$handle = fopen(StoreManager::$DB_FOLDER.StoreManager::$SLASH.$className.StoreManager::$EXT, "a+");
		// On incrémente l'ID courant
		$this->incrementID();
		// On set l'id de l'objet
		$object->id = StoreManager::$CURRENT_ID;
		$line  = '<'.get_class($object);
		// Construit la ligne
		$objectAttr = get_object_vars($object);
		foreach ($objectAttr as $attrName => $attrValue) {
			$line .= ' '.$attrName.'="'.$attrValue.'"';
		}
		$line .= '>'.StoreManager::$RETURN;
		// Ecrit la ligne dans le store
		fwrite($handle,  $line);
		fclose($handle);
		$this->store[get_class($object)][] = $object;
	}
	/**
	 * Méthode permettant de rajouter l'objet dans le store sans le créer 
	 */
	private function addtoStoreList($object){
		$this->store[get_class($object)][] = $object;
	}
	/**
	 * Retourne la liste présente dans le Store d'une Classe
	 */
	public function getList($className){
		return $this->store[$className];
	}
	/**
	 * Retourne la liste de toutes les classes présentent dans le Store
	 */
	public function getAllList(){
		return $this->store;
	}
}