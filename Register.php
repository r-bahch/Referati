<?php
session_start();
if (isset($_SESSION['user']) != "") {
    header("Location: home.php");
}
include_once 'dbconnect.php';
$uname = ""; $email = "";
if (isset($_POST['btn-signup'])) {
    $uname = mysql_real_escape_string($_POST['uname']);
    $email = mysql_real_escape_string($_POST['email']);
    $upass = md5(mysql_real_escape_string($_POST['pass']));
    $upassconf = md5(mysql_real_escape_string($_POST['passconf']));

    if ($upass != $upassconf) {
        ?>
        <script>alert('Паролите в 2те полета трябва да съвпадат!');</script>
        <?php
    }
    else {


        if (mysql_query("INSERT INTO users(username,email,password) VALUES('$uname','$email','$upass')")) {
            ?>
            <script>
                alert('Регистрацията е успешна!');
                window.location.href = 'index.php';
            </script>
<!--            --><?php //sleep(2);
//            header("Location: index.php");
        } else {
            ?>
            <script>alert('Грешка! Вече има регистрирано такова потребителско име.');</script>

            <?php
        }
    }
}

echo "
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Registration</title>
    <link rel='stylesheet' href='css/style.css' type='text/css'/>

</head>
<body>
<div id=\"header\">
    <div id=\"left\">
        <label>Система за работа с реферати</label>
    </div>
</div>
<center>
    <div id='login-form'>
        <form method='post'>
            <table align='center' width='30%' border='0'>
                <tr>
                    <td><input type='text' value='$uname' name='uname' placeholder='потребителско име' required/></td>
                </tr>
                <tr>
                    <td><input type='email' value = '$email' name='email' placeholder='e-mail' required/></td>
                </tr>
                <tr>
                    <td><input type='password' name='pass' placeholder='парола' required/></td>
                </tr>
                <tr>
                    <td><input type='password' name='passconf' placeholder='повторете паролата' required/></td>
                </tr>
                <tr>
                    <td>
                        <button type='submit' name='btn-signup'>Регистрация</button>
                    </td>
                </tr>
                <tr>
                     <td style='text-align:center;'><a href='index.php'>Имате регистация? Влизане</a></td>
                </tr>
            </table>
        </form>
    </div>
</center>
</body>
</html>
";
