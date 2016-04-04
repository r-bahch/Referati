<?php
session_start();
include_once 'dbconnect.php';

if (isset($_SESSION['user']) != "") {
    header("Location: home.php");
}
$username = "";
if (isset($_POST['btn-login'])) {
    $username = mysql_real_escape_string($_POST['username']);
    $upass = mysql_real_escape_string($_POST['pass']);
    $res = mysql_query("SELECT * FROM users WHERE username='$username'");
    $row = mysql_fetch_array($res);
    if ($row['password'] == md5($upass)) {
        $_SESSION['user'] = $row['id'];
        header("Location: home.php");
    } else {
        ?>
        <script>alert('Грешно потребителско име или парола');</script>
        <?php
    }

}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
</head>
<body>
<div id="header">
    <div id="left">
        <label>Система за работа с реферати</label>
    </div>
</div>
<center>

    <div id="login-form">
        <form method="post">
            <table align="center" width="30%" border="0">
                <tr>
                    <td><input type="text" value = "<?php echo $username;?>" name="username" placeholder="потребителско име" required/></td>
                </tr>
                <tr>
                    <td><input type="password" name="pass" placeholder="парола" required/></td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="btn-login">Вход</button>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;"><a href="register.php">Регистрация</a></td>
                </tr>
            </table>
        </form>
    </div>
</center>
</body>
</html>