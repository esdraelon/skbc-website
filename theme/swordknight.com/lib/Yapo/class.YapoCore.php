<?php

class YapoCore {
	var $__DB;
	var $__table;
	var $__definition;
	var $__record_set;
//	var $__current_record;
	var $__field_actions;
	var $__field_alias;
	var $__left_joins;
	var $__mismatched_set_equals;
	var $__ordering;
    var $__pagination;
    var $__page;
	var $__field_values;
	var $__transaction_started;
	var $__select_only_fields;

    var $__Where;
    var $__Join;
    var $__SubSelect;
	
    var $__Save;
    var $__Find;
    var $__Delete;
	
	var $__ERRORS = array();

	public function __construct(& $database, $table) {
		$this->__DB = & $database;
		$this->__table = $table;
		$this->__definition = $this->__DB->TableDescription($table);
		$this->__left_joins = array();
		$this->__field_alias = array();
    	$this->__pagination = null;
        $this->__page = null;
        
		$this->__field_values = array();
		
		$this->__transaction_started = 0;
		
		$this->clear();	
	}

    public function init() {
		$this->__Where = new YapoWhere($this);	
		$this->__Join = new YapoJoin($this);
		$this->__SubSelect = new YapoSubSelect($this);
		
		$this->__Save = new YapoSave($this, $this->__Where);
		$this->__Find = new YapoFind($this, $this->__Where, $this->__Join, $this->__SubSelect);
		$this->__Delete = new YapoDelete($this, $this->__Where);
    }
	
	public function Debug($debug) {
		$this->__DB->SetDebug($debug);
	}
	
	public function Clear() {
		$this->__field_actions = array();
//		$this->__current_record = null;
		$this->__record_set = null;
		$this->__mismatched_set_equals = false;
		$this->__ordering = array();
        $this->__pagination = null;
        $this->__page = null;
		$this->__DB->Clear();
		$this->__field_values = array();
		$this->__select_only_fields = null;
		$this->__left_joins = array();
	}
	
	function BeginTransaction() {
		$this->__transaction_started++;
		if ($this->__transaction_started > 1)
			return;
		$this->__DB->BeginTransaction();
	}
	
	function Commit() {
		$this->__transaction_started--;
		if ($this->__transaction_started > 0)
			return;
		$this->__DB->Commit();
		$this->__transaction_started = 0;
	}
	
	function RollBack() {
		$this->__transaction_started--;
		if ($this->__transaction_started > 0)
			return;
		$this->__DB->RollBack();
		$this->__transaction_started = 0;
	}
	
	function Execute($sql) {
		$this->__DB->Execute($sql);
		$this->__ERRORS[] = $this->__record_set->__ERRORS;
	}
	
	function DataSet($sql, $Data = null) {
		if (!is_null($Data))
			$this->SetData($Data);
		$this->__record_set = $this->__DB->DataSet($sql);
		$this->__ERRORS[] = $this->__record_set->__ERROR;
	}
	
	function Order($field, $ordering) {
		$this->__ordering[$field] = $ordering;
	}
	
	function Comparator($field, $comparator, $value) {
		if (!isset($this->__field_actions[$field]))
			$this->__field_actions[$field] = null;
		if (!is_array($this->__field_actions[$field]))
			$this->__field_actions[$field] = array();
		$this->__field_actions[$field][$comparator] = $value;
		if (Yapo::SET == $comparator && isset($this->__field_actions[$field][Yapo::EQUALS]) && $this->__field_actions[$field][Yapo::EQUALS] != $this->__field_actions[$field][Yapo::SET]) {
			$this->__mismatched_set_equals = true;
		}
	}
    
    function SubSelect($type, $p, $yapo) {
        
    }
    
    function Join($other_table, $relationships) {
		if (!isset($this->__left_joins[$other_table->table()]))
			$this->__left_joins[$other_table->table()] = array();
        $this->__left_joins[$other_table->table()] = $relationships;
    }
	
	function SelectFields($select_fields = null) {
		if (is_array($select_fields))
			$this->__select_only_fields = $select_fields;
		return $this->__select_only_fields;
	}
    
    function GetLimit() {
        return array($this->__pagination, $this->__page);
    }
    
    function Limit($pagination = 20, $page = 0) {
        $this->__pagination = $pagination;
        $this->__page = $page;
    }
	
	function __set($field, $value) {
		if (is_object($value)) die("you cannot insert an object.");
		$this->__DB->$field = $value;
		$this->__field_values[$field] = $value;
	}
	
	function __isset($field) {
		return $this->__record_set->$field || isset($this->__field_values[$field]);
	}
	
	function __get($field) {
		if (isset($this->__field_values[$field])) {
			return $this->__field_values[$field];
		} else if (is_null($this->__record_set)) {
			throw new Exception("There is no active record set: " . print_r($this->__field_values, true));
		} else {
			if (isset($this->__record_set->$field))
				return $this->__record_set->$field;
			return null;
		}
	}
	
	function HasActiveRecord() {
		return !is_null($this->__record_set);
	}
	
	function PrimaryKeyIsSet() {
		return (isset($this->__field_actions[$this->GetPrimaryKeyField()][Yapo::SET]) || isset($this->__field_actions[$this->GetPrimaryKeyField()][Yapo::EQUALS]));
	}
	
	function GetPrimaryKeyField() {
		return $this->__definition["PrimaryKey"];
	}
	
	function GetLastInsertId() {
		if (is_null($this->__record_set)) {
			throw new Exception("There is no active record set.");
		} else {
			return $this->__DB->GetLastInsertId();
		}
	}
	
	function Next() {
		if (is_null($this->__record_set)) {
			throw new Exception("There is no active record set.");
		} else {
			return $this->__record_set->Next();
		}
	}
	
	function Size() {
		if (is_null($this->__record_set)) {
			throw new Exception("There is no active record set.");
		} else {
			return $this->__record_set->Size();
		}
	}
	
	function SetData($Data) {
		$this->__DB->SetData($Data);
	}

	public function GetRawFields() {
		return $this->__definition['Fields'];
	}
	
	public function HasField($field) {
		return isset($this->__definition['Fields'][$field]);
	}
	
	public function GetSelectFields() {
		$fields = array();
		foreach ($this->__definition['Fields'] as $field_name => $def) {
			if (is_array($this->__select_only_fields) && count($this->__select_only_fields) > 0)
				if (!in_array($field_name, $this->__select_only_fields))
					continue;
			$fields[] = $this->GetQualifiedName($field_name);
		}
		return $fields;
	}
	
	public function GetFieldSelectAlias($field_name) {
		return isset($this->__field_alias[$field_name])?($field_name . ' as ' . $this->__field_alias[$field_name]):$field_name;
	}
	
	public function GetQualifiedName($field_name, $delimiter = ".") {
		return "{$this->__table}$delimiter" . $this->GetFieldSelectAlias($field_name);
	}
	
	public function GetActiveFieldSet() {
	    $fs = $this->__record_set->CurrentFieldSet();
	    $rs = array();
	    if (is_array($fs)) foreach ($fs as $field => $value)
	        $rs[$this->GetQualifiedName($field)] = $value;
	    return $rs;
	}

}

?>