<?php

  if (logged_in()) {
    header("Location: my-classes"); 
  }
  
  $message = null;
  $fields = array('email','mundane','persona','city','state','game','password');
  $isset = true;
  $anyset = false;
  foreach ($fields as $f) {
    $isset &= isset($_REQUEST[$f]);
    $anyset |= isset($_REQUEST[$f]);
  }
  if (strlen($_REQUEST['email']) == 0 || strlen($_REQUEST['persona']) == 0 || strlen($_REQUEST['password']) == 0) $isset = false;

  if ($anyset) {
    if ($isset) {
      if ($student_id = call_user_func_array(array(Lib::$Lib->Students, "Register"), array_map(function($f) { return $_REQUEST[$f]; }, $fields))) {
        $_SESSION['student_id'] = $student_id;
        header("Location: my-classes");
      } else {
        $message = "Your email or persona name is already taken. Please try with different value, or try logging in or resetting your account with this email address."; 
      }
    } else {
      $message = 'You must fill in all of the fields. Thanks!'; 
    }
  }

?>
<?php include_once('inc.header.php'); ?>
		<div id='wrapper' style='margin-top: 60px;'>
      <section>
        <div id='align-fix' style='width: 300px; margin: 30px auto;' >
        <form method='POST' class='skbc-form'>
          <div><span style='font-weight: bold; font-size: 2em; color: #fff;'>Register</span></div>
          <?=message($message); ?>
          <div><input type='text' placeholder='Email' name='email' /></div>
          <div><input type='text' placeholder='Mundane Name' name='mundane' /></div>
          <div><input type='text' placeholder='Persona Name' name='persona' /></div>
          <div><input type='text' placeholder='City' name='city' /></div>
          <div>
            <select name="state">
              <option value="" selected="selected">Select a State</option> 
              <option value="AL">Alabama</option> 
              <option value="AK">Alaska</option> 
              <option value="AZ">Arizona</option> 
              <option value="AR">Arkansas</option> 
              <option value="CA">California</option> 
              <option value="CO">Colorado</option> 
              <option value="CT">Connecticut</option> 
              <option value="DE">Delaware</option> 
              <option value="DC">District Of Columbia</option> 
              <option value="FL">Florida</option> 
              <option value="GA">Georgia</option> 
              <option value="HI">Hawaii</option> 
              <option value="ID">Idaho</option> 
              <option value="IL">Illinois</option> 
              <option value="IN">Indiana</option> 
              <option value="IA">Iowa</option> 
              <option value="KS">Kansas</option> 
              <option value="KY">Kentucky</option> 
              <option value="LA">Louisiana</option> 
              <option value="ME">Maine</option> 
              <option value="MD">Maryland</option> 
              <option value="MA">Massachusetts</option> 
              <option value="MI">Michigan</option> 
              <option value="MN">Minnesota</option> 
              <option value="MS">Mississippi</option> 
              <option value="MO">Missouri</option> 
              <option value="MT">Montana</option> 
              <option value="NE">Nebraska</option> 
              <option value="NV">Nevada</option> 
              <option value="NH">New Hampshire</option> 
              <option value="NJ">New Jersey</option> 
              <option value="NM">New Mexico</option> 
              <option value="NY">New York</option> 
              <option value="NC">North Carolina</option> 
              <option value="ND">North Dakota</option> 
              <option value="OH">Ohio</option> 
              <option value="OK">Oklahoma</option> 
              <option value="OR">Oregon</option> 
              <option value="PA">Pennsylvania</option> 
              <option value="RI">Rhode Island</option> 
              <option value="SC">South Carolina</option> 
              <option value="SD">South Dakota</option> 
              <option value="TN">Tennessee</option> 
              <option value="TX">Texas</option> 
              <option value="UT">Utah</option> 
              <option value="VT">Vermont</option> 
              <option value="VA">Virginia</option> 
              <option value="WA">Washington</option> 
              <option value="WV">West Virginia</option> 
              <option value="WI">Wisconsin</option> 
              <option value="WY">Wyoming</option>
              <option value="ON">Ontario</option>
              <option value="QC">Quebec</option>
              <option value="NS">Nova Scotia</option>
              <option value="NB">New Brunswick</option>
              <option value="MB">Manitoba</option>
              <option value="BC">British Columbia</option>
              <option value="PE">Prince Edward Island</option>
              <option value="SK">Saskatchewan</option>
              <option value="AB">Alberta</option>
              <option value="NL">Newfoundland and Labrador</option>
              <option value="NT">Northwest Territories</option>
              <option value="YT">Yukon Territory</option>
              <option value="NU">Nunavut</option>
            </select>
          </div>
          <div>
            <select name="game">
              <option value="" selected="selected">Select a Game</option> 
              <option>Amtgard</option>
              <option>Belegarth</option>
              <option>Dagorhir</option>
              <option>Darkon</option>
              <option>High Fantasy Society</option>
              <option>International Fantasy Games Society</option>
              <option>LARP Alliance, Inc.</option>
              <option>MagiQuest</option>
              <option>Melee</option>
              <option>Other</option>
            </select>
          </div>
          <div><input type='password' placeholder='Password' name='password' /></div>
          <div><input type='submit' value='Register' /></div>
        </form>
        </div>
        <div style="clear:both"></div>
      </section>

      <div id='push'></div>
    </div>
<?php include_once('inc.footer.php'); ?>
