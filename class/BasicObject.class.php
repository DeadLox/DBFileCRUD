<?php
class BasicObject {
	public $id;
	protected $name;

	public static $storeMgr;

	public function __construct(){
		$this->name = get_class($this);
	}

	public function performCreate() {
		BasicObject::$storeMgr->add($this);
	}
	public function performUpdate() {
		echo "update<br/>";
	}
	public function performDelete() {
		echo "delete<br/>";
	}

	public function __set($attr, $value){
		if(isset($this->$attr)) $this->$attr = $value;
		else throw new Exception('Attribute inconnu: '.$attr);
	}
	public function __get($attr){
		if(isset($this->$attr)) return $this->$attr;
      	else throw new Exception('Unknow inconnu: '.$attr);
	}
}