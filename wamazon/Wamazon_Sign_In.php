<!-- Author: Anand Gogoi -->
<!-- This is the login page. The user can log in or make an account. -->

<html>
<title>
Wamazon Log In 
</title>

<body>

<H3> Wamazon Log In Page </H3>

<!-- This form takes in the users password and username and redirects thenm to the main.php page-->
<form action = "main.php" method = "post">
User Name <input type="text" name="User_Name"><br>
Password <input type="text" name="Password"> <h6>If you do not have a password you can leave the password blank or type anything. You can change your password when you login. It is recomened that you do so if you do not have one as any one with your user name can log into your account</h6><br>
<input type="submit" value = "Login">
<input type = "hidden" name = "Sign_Or_Log" value = "Log" />
<!--This takes the user to page where they can sign up if they do not have an account. -->
<a href="signupFrontEnd.php">Click here to make an account</a> 
</form>

</body>
</html> 
