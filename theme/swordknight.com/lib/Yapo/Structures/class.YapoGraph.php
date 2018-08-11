<?php

class YapoGraph extends Yapo {

}

class YapoGraphNode {
	
	protected $__primary_key_field;
	protected $__field_values;
	
	function __construct($primary_key_field) {
		$this->__primary_key_field = $primary_key_field;
	}
	
	function __set($field, $value) {
		if (is_object($value)) die("you cannot insert an object.");
		$this->__field_values[$field] = $value;
		$this->$field = $value;
	}
}

?>