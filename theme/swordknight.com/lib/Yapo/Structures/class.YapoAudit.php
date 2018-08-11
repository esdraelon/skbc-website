<?php

class YapoAudit extends Yapo {

	var $__AUDIT_FIELDS;
	var $__HISTORY_YAPO;
	var $__HISTORY_INSERT_ID;

	function __construct(& $database, $table, $history_table, $audit_fields) {
		parent::__construct($database, $table);
		$this->__AUDIT_FIELDS = $audit_fields;
		$this->__HISTORY_YAPO = new Yapo($database, $history_table);
	}

	function __set($field, $value) {
		if (in_array($field, $this->__AUDIT_FIELDS)) {
			$this->__HISTORY_YAPO->$field = $value;
			if ($this->__Core->HasField($field)) {
				parent::__set($field, $value);
			}
		} else {
			parent::__set($field, $value);
		}
	}
	
	public function clear() {
		$this->__HISTORY_YAPO->clear();
		parent::clear();
	}
	
	public function save($all = false) {
		$pk = $this->primarykey();
		foreach ($this->__Core->__field_values as $field_name => $value) {
			if ($field_name == $pk) continue;
			$this->__HISTORY_YAPO->$field_name = $value;
		}
		parent::save();
		$id = $this->$pk;
		$this->__HISTORY_YAPO->$pk = $id;
		$this->__HISTORY_INSERT_ID = $this->__HISTORY_YAPO->save();
		return $id;
	}
	
	public function HistoryId() {
		return $this->__HISTORY_INSERT_ID;
	}
}

?>