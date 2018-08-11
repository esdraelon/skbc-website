<?php

class Instructors {

	function __construct() {
		$this->instructors = new Yapo(Lib::$Lib->Database, 'skbc_instructors');
	
	}
	
  function GetInstructors() {
    $sql =
      "select i.instructor_id, i.name 
        from skbc_instructors i 
          left join skbc_classes c on i.instructor_id in (c.instructor1_id, c.instructor2_id, c.instructor3_id) 
        where i.year = :year and c.class_id is not null 
        group by i.instructor_id";
    
    Lib::$Lib->Database->Clear();
    Lib::$Lib->Database->year = __YEAR__;
    $results = Lib::$Lib->Database->Query($sql);
    $instructors = array();
    if ($results->Size() > 0) do {
      $instructors[] = array(
          'InstructorId' => $results->instructor_id,
          'Name' => $results->name
        );
    } while ($results->Next());
    return $instructors;
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