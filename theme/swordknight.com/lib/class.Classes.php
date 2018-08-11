<?php

class Classes {

	function __construct() {
		$this->classes = new Yapo(Lib::$Lib->Database, 'skbc_classes');
	
	}

	function GetClassList() {
		$sql = "select c.class_id, c.name, c.day, c.slot, count(*) as enrolled, 
          i1.name as name1, i2.name as name2, i3.name as name3, 
          c.class_limit, c.description,
					i1.instructor_id as instructor1_id, i2.instructor_id as instructor2_id, i3.instructor_id as instructor3_id
				from skbc_registration r
					left join skbc_classes c on r.class_id = c.class_id
						left join skbc_instructors i1 on c.instructor1_id = i1.instructor_id
						left join skbc_instructors i2 on c.instructor2_id = i2.instructor_id
						left join skbc_instructors i3 on c.instructor3_id = i3.instructor_id
					left join skbc_students s on r.student_id = s.student_id
				where r.year = " . __YEAR__ . " and c.class_id is not null and c.visible = 'show'
				group by c.class_id
				order by c.day, c.slot, c.name 
				";

		$result = Lib::$Lib->Database->Query($sql);
		
		$classes = array();
		
		if ($result->Size() > 0) while($result->Next()) {
			$classes[$result->class_id] = array(
					'Day' => $result->day,
					'Slot' => $result->slot,
					'ClassId' => $result->class_id,
					'Name' => $result->name,
					'Instructor1' => $result->name1,
					'Instructor2' => $result->name2,
					'Instructor3' => $result->name3,
					'Instructor1Id' => $result->instructor1_id,
					'Instructor2Id' => $result->instructor2_id,
					'Instructor3Id' => $result->instructor3_id,
					'Enrolled' => $result->enrolled,
					'Limit' => $result->class_limit,
					'Description' => $result->description
				);
		}
		
		return $classes;
	}
	
	function GetInstructorClasses($instructor_id) {
		$sql = "select c.class_id, c.name
					from skbc_classes c
					where 
						(c.instructor1_id = $instructor_id or c.instructor2_id = $instructor_id or c.instructor3_id = $instructor_id)
						and
						c.year = " . __YEAR__;

		$result = Lib::$Lib->Database->Query($sql);
		
		$classes = array();
		
		if ($result->Size() > 0) while($result->Next()) {
			$classes[$result->class_id] = array(
					'ClassId' => $result->class_id,
					'Name' => $result->name
				);
		}
		
		return $classes;
	}
	
	function GetClass($class_id) {
		$sql = 
      "select c.class_id, c.name, c.day, c.slot, 
          i1.name as name1, i2.name as name2, i3.name as name3, 
          c.class_limit, c.description,
					i1.instructor_id as instructor1_id, i2.instructor_id as instructor2_id, i3.instructor_id as instructor3_id
				from skbc_classes c
						left join skbc_instructors i1 on c.instructor1_id = i1.instructor_id
						left join skbc_instructors i2 on c.instructor2_id = i2.instructor_id
						left join skbc_instructors i3 on c.instructor3_id = i3.instructor_id
				where c.year = " . __YEAR__ . " and c.class_id = $class_id and c.visible = 'show'
				";

		$result = Lib::$Lib->Database->Query($sql);
		
		$classes = array();
		
		if ($result->Size() > 0) {
			$result->Next();
			return array(
					'Day' => $result->day,
					'Slot' => $result->slot,
					'Name' => $result->name,
					'ClassId' => $result->class_id,
					'Instructor1' => $result->name1,
					'Instructor2' => $result->name2,
					'Instructor3' => $result->name3,
					'Instructor1Id' => $result->instructor1_id,
					'Instructor2Id' => $result->instructor2_id,
					'Instructor3Id' => $result->instructor3_id,
					'Limit' => $result->class_limit,
					'Description' => $result->description
				);
		}
		
		return array(
			'Day' => 0,
			'Slot' => 0,
			'Name' => 'Class Not Found',
			'Instructor1' => null,
			'Instructor2' => null,
			'Limit' => 0,
			'Description' => null
		);
	}
	
	function GetClassRegistrations($student_id = null) {
	
    $student = null;
    if (isset($student_id)) {
      $student = " and r.student_id = :student_id"; 
    }
    
		$sql = 
      "select r.registration_id, c.class_id, c.name, c.day, c.slot, 
          i1.name as name1, i2.name as name2, i3.name as name3, 
              c.class_limit, c.description,
							s.student_id, s.persona,
    					i1.instructor_id as instructor1_id, i2.instructor_id as instructor2_id, i3.instructor_id as instructor3_id
				from skbc_registration r
					left join skbc_classes c on r.class_id = c.class_id
						left join skbc_instructors i1 on c.instructor1_id = i1.instructor_id
						left join skbc_instructors i2 on c.instructor2_id = i2.instructor_id
						left join skbc_instructors i3 on c.instructor3_id = i3.instructor_id
					left join skbc_students s on r.student_id = s.student_id
				where r.year = " . __YEAR__ . " and c.class_id is not null and c.visible = 'show' $student
				group by c.class_id, s.student_id
				order by c.day, c.slot, c.name, s.persona
				";

    if (isset($student_id)) Lib::$Lib->Database->student_id = $student_id;
		$result = Lib::$Lib->Database->Query($sql);
		
    if ($result->Size() > 0) {
  		$result->Next();
      $classes = array();
      $class_id = $result->class_id;
      $students = array();
      $students[$result->student_id] = array(
          'Student' => $result->persona
        );
      $class = array(
        'ClassId' => $result->class_id,
        'Day' => $result->day,
        'Slot' => $result->slot,
        'Name' => $result->name,
        'Instructor1' => $result->name1,
        'Instructor2' => $result->name2,
        'Instructor3' => $result->name3,
        'Instructor1Id' => $result->instructor1_id,
        'Instructor2Id' => $result->instructor2_id,
        'Instructor3Id' => $result->instructor3_id,
        'Limit' => $result->class_limit,
        'Description' => $result->description,
      );
      do {
        if ($class_id != $result->class_id) {
          $class['Students'] = $students;
          $classes[$class_id] = $class;
          $class = array(
            'ClassId' => $result->class_id,
            'Day' => $result->day,
            'Slot' => $result->slot,
            'Name' => $result->name,
            'Instructor1' => $result->name1,
            'Instructor2' => $result->name2,
            'Instructor3' => $result->name3,
            'Instructor1Id' => $result->instructor1_id,
            'Instructor2Id' => $result->instructor2_id,
            'Instructor3Id' => $result->instructor3_id,
            'Limit' => $result->class_limit,
            'Description' => $result->description,
            'Students' => array(
              'Student' => $result->persona
            )
          );
          $students = array();
          $class_id = $result->class_id;
        }
        $students[$result->student_id] = array(
            'Student' => $result->persona
          );
      } while ($result->Next());

      $classes[$result->class_id] = array(
          'ClassId' => $result->class_id,
          'Day' => $result->day,
          'Slot' => $result->slot,
          'Name' => $result->name,
          'Instructor1' => $result->name1,
          'Instructor2' => $result->name2,
          'Instructor3' => $result->name3,
          'Instructor1Id' => $result->instructor1_id,
          'Instructor2Id' => $result->instructor2_id,
          'Instructor3Id' => $result->instructor3_id,
          'Limit' => $result->class_limit,
          'Description' => $result->description,
          'Students' => $students
        );
    }
		
		return $classes;
	}
	
}

?>