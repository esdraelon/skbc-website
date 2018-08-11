<?php

class Students {

	function __construct() {
		$this->students = new Yapo(Lib::$Lib->Database, 'skbc_students');
		$this->registration = new Yapo(Lib::$Lib->Database, 'skbc_registration');
	}
	
  function Register($email, $mundane, $persona, $city, $state, $game, $password) {
    $this->students->clear();
    $this->students->email = $email;
    $this->students->mundane = $mundane;
    $this->students->persona = $persona;
    $this->students->city = $city;
    $this->students->state = $state;
    $this->students->game = $game;
    $this->students->password = password_hash($password, PASSWORD_DEFAULT);
    $this->students->urlkey = md5(microtime() . password_hash($password, PASSWORD_DEFAULT));
    return $this->students->save();
  }
  
  function ResetPassword($urlkey, $password) {
    $this->students->clear();
    $this->students->urlkey = $urlkey;
    if ($this->students->find() == 1) {
      $student_id = $this->students->student_id;
      $this->students->password = password_hash($password, PASSWORD_DEFAULT);
      $this->students->urlkey = md5(password_hash($password . microtime() . $urlkey, PASSWORD_DEFAULT));
      $this->students->save();
      return $student_id;
    }
    return false;
  }
  
  function RecoverPassword($email) {
    $this->students->clear();
    $this->students->like('email', $email);
    if ($this->students->find() == 1) {
      $student_id = $this->students->student_id;
      $urlkey = md5(password_hash($this->students->password . microtime() . $this->students->urlkey, PASSWORD_DEFAULT));
      $this->students->urlkey = $urlkey;
      $this->students->save();
      $headers = "From: password-reset@swordknight.com\r\nReply-to: password-reset@swordknight.com";
      mail($email, "SKBC Password Reset", "Please follow this link to reset your password: https://swordknight.com/reset-password?urlkey=$urlkey", $headers);
      return $student_id;
    }
    return false;
  }
  
  function JoinClass($student_id, $class_id) {
    $class = Lib::$Lib->Classes->GetClass($class_id);
    $sql = "select * from skbc_registration r left join sbkc_classes c on r.class_id = c.class_id where c.day = :day and c.slot = :slot and r.student_id = :student_id and r.year = :year";
    Lib::$Lib->Database->Clear();
    Lib::$Lib->Database->day = $class['Day'];
    Lib::$Lib->Database->slot = $class['Slot'];
    Lib::$Lib->Database->student_id = $student_id;
    Lib::$Lib->Database->year = __YEAR__;
    $response = Lib::$Lib->Database->Query($sql);
    if ($response->Size() > 0) return false;
    
    $this->registration->clear();
    $this->registration->year = __YEAR__;
    $this->registration->class_id = $class_id;
    $this->registration->student_id = $student_id;
    return $this->registration->save();
  }
  
  function DropClass($student_id, $class_id) {
    $this->registration->clear();
    $this->registration->student_id = $student_id;
    $this->registration->class_id = $class_id;
    $this->registration->year = __YEAR__;
    $this->registration->delete();
  }
  
  function Login($email, $password) {
    $this->students->clear();
    $this->students->like('email', $email);
    if ($this->students->find() == 1) {
      $student_id = $this->students->student_id;
      if (($this->students->password === $password) || (trimlen($this->students->password) == 32 && $this->students->password[0] !== '$' && md5($password) === $this->students->password)) {
        $this->students->password = password_hash($password, PASSWORD_DEFAULT);
        $this->students->urlkey = md5(password_hash($password, PASSWORD_DEFAULT) . microtime());
        $this->students->save();
        return $student_id;
      } else if (password_verify($password, $this->students->password)) {
        return $student_id;
      }
    }
    return false;
  }
  
}

?>