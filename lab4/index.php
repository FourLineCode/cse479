<?php

$name_err = $email_err = $pass_err = $ip_err = $web_err = $mobile_err = "";

$name = $email = $password = $ip_address = $web = $dob = $gender = $mobile = $bio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"] ?? "";
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";
  $ip_address = $_POST["ip"] ?? "";
  $web = $_POST["web"] ?? "";
  $dob = $_POST["dob"] ?? "";
  $gender = $_POST["gender"] ?? "";
  $mobile = $_POST["mobile"] ?? "";
  $bio = $_POST["bio"] ?? "";

  $bio = preg_replace("/(bloody)/i", "******", $bio);
  $bio = preg_replace("/(cow)/i", "***", $bio);
  $bio = preg_replace("/(damn)/i", "****", $bio);
  $bio = preg_replace("/(ginger)/i", "******", $bio);

  if (preg_match("/[a-z]+ ([a-z]+)?( )?[a-z]+/i", $name) && strlen($name) >= 5) $name_err = "";
  else $name_err = "* Invalid name";

  if (filter_var($email, FILTER_VALIDATE_EMAIL)) $email_err = "";
  else $email_err = "* Invalid email address";

  if (preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) $pass_err = "";
  else $pass_err = "* Password must have uppercase, lowercase letters and atleast one number";

  if (filter_var($ip_address, FILTER_VALIDATE_IP)) $ip_err = "";
  else $ip_err = "* Invalid ip address";

  if (filter_var($web, FILTER_VALIDATE_URL)) $web_err = "";
  else $web_err = "* Invalid web address";

  if (preg_match("/^[0-9]{11}$/", $mobile)) $mobile_err = "";
  else $mobile_err = "* Invalid mobile number";
}

$form_is_valid = $_SERVER["REQUEST_METHOD"] == "POST" && $name_err == "" && $email_err == "" && $pass_err == "" && $ip_err == "" && $web_err == "" && $mobile_err == "";

?>

<html data-theme="light">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
  <title>Form Validation</title>
  <style>
    .text-red {
      color: red;
    }
  </style>
</head>

<body>
  <?php if ($form_is_valid) : ?>
    <div class="container">
      <table>
        <tbody>
          <tr>
            <th>Name</th>
            <td><?php echo $name ?></td>
          </tr>
          <tr>
            <th>Email</th>
            <td><?php echo $email ?></td>
          </tr>
          <tr>
            <th>Password</th>
            <td><?php echo $password ?></td>
          </tr>
          <tr>
            <th>IP Address</th>
            <td><?php echo $ip_address ?></td>
          </tr>
          <tr>
            <th>Web URL</th>
            <td><?php echo $web ?></td>
          </tr>
          <tr>
            <th>Age</th>
            <td><?php echo $dob ?></td>
          </tr>
          <tr>
            <th>Gender</th>
            <td><?php echo $gender ?></td>
          </tr>
          <tr>
            <th>Mobile</th>
            <td><?php echo $mobile ?></td>
          </tr>
          <tr>
            <th>Bio</th>
            <td>
              <pre>
              <?php echo $bio ?>
              </pre>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php else : ?>
    <form action="/" method="POST" class="container">
      <label for="name">
        Name:
        <input value="<?php echo $name ?>" name="name" type="text" placeholder="Name...">
        <small class="text-red"><?php echo $name_err ?></small>
      </label>
      <label for="email">
        Email:
        <input value="<?php echo $email ?>" name="email" type="email" placeholder="Email...">
        <small class="text-red"><?php echo $email_err ?></small>
      </label>
      <label for="password">
        Password:
        <input value="<?php echo $password ?>" name="password" type="password" placeholder="********">
        <small class="text-red"><?php echo $pass_err ?></small>
      </label>
      <label for="ip">
        IP Address:
        <input value="<?php if ($ip_address != "") echo $ip_address;
                      else echo "192.168.0.1"; ?>" name="ip" type="text" placeholder="192.168.0.1">
        <small class="text-red"><?php echo $ip_err ?></small>
      </label>
      <label for="web">
        Personal web url:
        <input value="<?php echo $web ?>" name="web" type="url" placeholder="https://example.com">
        <small class="text-red"><?php echo $web_err ?></small>
      </label>
      <label for="dob">
        Date of birth:
        <input value="<?php echo $dob ?>" name="dob" type="date">
      </label>
      <label for="gender">
        Gender:
        <label for="gender">
          <input type="radio" name="gender" value="male" checked>
          Male
        </label>
        <label for="gender">
          <input type="radio" name="gender" value="female">
          Female
        </label>
      </label>
      <label for="mobile">
        Mobile:
        <input value="<?php echo $mobile ?>" name="mobile" type="number" placeholder="0123456789">
        <small class="text-red"><?php echo $mobile_err ?></small>
      </label>
      <label for="bio">
        Bio:
        <textarea value="<?php echo $bio ?>" name="bio" placeholder="Brief info about yourself..." rows="20" cols="35"></textarea>
      </label>
      <input type="submit" value="Register">
    </form>
  <?php endif; ?>
</body>

</html>