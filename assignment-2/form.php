<style>
  table,
  th,
  td {
    border: 1px solid black;
    border-collapse: collapse;
  }

  th,
  td {
    padding: 0.5rem;
  }
</style>

<?php

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$ip_address = $_POST['ip'];
$web_url = $_POST['url'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$mobile = $_POST['phone'];
$brief_info = $_POST['brief'];

$dob_d = new DateTime($dob);
$now = new DateTime();
$age = $now->diff($dob_d);

if (empty($name) || empty($email) || empty($password) || empty($ip_address) || empty($web_url) || empty($dob) || empty($gender) || empty($mobile) || empty($brief_info)) {
  echo '<p style="color:red;">Please fill out all fields in the form.</p>';
  return;
}

// Validate name
if (!preg_match('/^[A-Za-z]+(?: [A-Za-z]+){0,2}$/', $name)) {
  echo '<p style="color:red;">Please enter a valid name.</p>';
  return;
}

// Validate email
if (!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
  echo '<p style="color:red;">Please enter a valid email address.</p>';
  return;
}

// Validate password
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,20})/', $password)) {
  echo '<p style="color:red;">Please enter a valid password.</p>';
  return;
}

// Validate IP address
if (!preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $ip_address)) {
  echo '<p style="color:red;">Please enter a valid IP address.</p>';
  return;
}

// Validate personal web URL
if (!preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $web_url)) {
  echo '<p style="color:red;">Please enter a valid personal web URL.</p>';
  return;
}

// Validate mobile number
if (!preg_match('/^(?:\+?880|0)\d{9,10}$/', $mobile)) {
  echo '<p style="color:red;">Please enter a valid mobile number.</p>';
  return;
}

$brief_info = preg_replace('/\b(damn|kill|death|liar)\b/i', '*****', $brief_info);
$num_words = str_word_count($brief_info);
if ($num_words > 50) {
  echo '<p style="color:red;">Your brief info must not contain more than 50 words.</p>';
  return;
}

$mysqli = new mysqli('localhost', 'root', '', 'spring2022');
$query = "INSERT INTO user (name, email, password, ip_address, web_url, dob, gender, mobile, brief_info) VALUES ('$name', '$email', '$password', '$ip_address', '$web_url', '$dob', '$gender', '$mobile', '$brief_info')";
$result = $mysqli->query($query);
if (!$result) {
  echo '<p style="color:red;">Error: ' . $mysqli->error . '</p>';
} else {
  echo '<p style="color:green;">Success: stored information in database</p>';
}

$query = "SELECT * FROM user ORDER BY mobile DESC";
$result = $mysqli->query($query);

echo '<table>';
echo '<tr>';
echo '<th>Name</th>';
echo '<th>Email</th>';
echo '<th>Password</th>';
echo '<th>IP Address</th>';
echo '<th>Web URL</th>';
echo '<th>Date of Birth</th>';
echo '<th>Gender</th>';
echo '<th>Mobile</th>';
echo '<th>Brief Info</th>';
echo '</tr>';

while ($row = $result->fetch_assoc()) {
  echo '<tr>';
  echo '<td>' . $row['name'] . '</td>';
  echo '<td>' . $row['email'] . '</td>';
  echo '<td>' . $row['password'] . '</td>';
  echo '<td>' . $row['ip_address'] . '</td>';
  echo '<td>' . $row['web_url'] . '</td>';
  echo '<td>' . $row['dob'] . '</td>';
  echo '<td>' . $row['gender'] . '</td>';
  echo '<td>' . $row['mobile'] . '</td>';
  echo '<td>' . $row['brief_info'] . '</td>';
  echo '</tr>';
}

echo "</table>";
