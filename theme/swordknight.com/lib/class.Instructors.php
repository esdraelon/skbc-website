<?php

class Instructors {

	function __construct() {
		$this->instructors = new Yapo(Lib::$Lib->Database, 'skbc_instructors');
	
	}
	
	function GetInstructor($instructor_id) {
		$this->instructors->clear();
		$this->instructors->instructor_id = $instructor_id;
		$this->instructors->year = __YEAR__;
		$this->instructors->visible = 'show';
		if ($this->instructors->find()) {
			return array(
				'InstructorId' => $this->instructors->instructor_id,
				'Name' => $this->instructors->name,
				'Bio' => $this->instructors->bio,
				'ImageUrl' => $this->instructors->image,
				'Classes' => Lib::$Lib->Classes->GetInstructorClasses($instructor_id)
			);
		}
		return array(
			'InstructorId' => 0,
			'Name' => 'Instructor Not Found',
			'Bio' => '',
			'Classes' => array()
		);
	}

}

?>