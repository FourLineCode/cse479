<html>

<head>
  <title>Form Validation</title>
</head>

<body>
  <form method="post" action="form.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required placeholder="Enter your name">
    <br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required placeholder="Enter your email">
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required placeholder="Enter a password">
    <br><br>
    <label for="ip">IP address:</label>
    <input type="text" id="ip" name="ip" required placeholder="Enter your router's IP address">
    <br><br>
    <label for="url">Personal web URL:</label>
    <input type="text" id="url" name="url" required placeholder="Enter your personal web URL">
    <br><br>
    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" required>
    <br><br>
    <label for="gender">Gender:</label>
    <input type="radio" id="male" name="gender" value="male" required>
    <label for="male">Male</label>
    <input type="radio" id="female" name="gender" value="female" required>
    <label for="female">Female</label>
    <br><br>
    <label for="phone">Mobile Number:</label>
    <input type="text" id="phone" name="phone" required placeholder="Enter your mobile number">
    <br><br>
    <label for="brief">Brief info:</label>
    <textarea id="brief" name="brief" rows="30" cols="30" required></textarea>
    <br><br>
    <input type="submit" value="Register">
  </form>
</body>

</html>