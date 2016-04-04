<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

mysql_query('SET NAMES utf8');
//for logout button
$res = mysql_query("SELECT * FROM users WHERE id=" . $_SESSION['user']);
$userRow = mysql_fetch_array($res); //username

$userid = $_SESSION['user'];
$refq = mysql_query("SELECT id, title FROM refs WHERE user_id = $userid");
if (mysql_num_rows($refq) > 0) {
    $ref = mysql_fetch_array($refq);
    $refid = $ref['id'];

    $fileq = mysql_query("SELECT * FROM files WHERE ref_id = $refid");
    $curfileversion = mysql_num_rows($fileq);
    $newfileversion = $curfileversion + 1;

    if (isset($_FILES['archive-data'])) {
        $success = true;
        $file_tmp = $_FILES['archive-data']['tmp_name'];
        $file_name_tmp = $_FILES['archive-data']['name'];
        $file_ext = pathinfo($file_name_tmp, PATHINFO_EXTENSION);
        $file_name = $userRow['username'] . "v" . $newfileversion . "." . $file_ext;

        //upload
        if (!move_uploaded_file($file_tmp, "uploads/" . $file_name)) {
            $success = false;
        }

        //update in database
        if (!mysql_query("INSERT INTO files (ref_id, filename, version)
          VALUES ($refid, '$file_name', $newfileversion)")
        ) {
            $success = false;
        }

        if ($success) {
            ?>
            <script>
                alert('Файлът е качен успешно!');
                window.location.href = 'submit-file.php';
            </script>
            <?php
        } else {
            ?>
            <script>alert('Възникна грешка при качването! Опитайте отново!');</script>
            <?php
        }
    }
}
include_once 'templates/page.php';

if (mysql_num_rows($refq) > 0) {
?>
<div style="text-align: center">
    <h1>Тема: <?php echo $ref['title']; ?></h1>

    <?php if ($curfileversion > 0) { ?>
        <h2>Предишни версии:</h2>
        <?php
        while ($row = mysql_fetch_assoc($fileq)) {
            $curfilename = $row['filename'];
            echo "
        <form  action='uploads/$curfilename'>
            <input style='width:120px;' type='submit' value='$curfilename'>
        </form>
    ";
        }
    }
    ?>

<h2>Качване на версия <?php echo $newfileversion;?>:</h2>
<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="archive-data" accept="application/zip"/>
    <input type="submit"/>
</form>
<?php } else {
    echo "<h1 style='text-align: center;'>Първо изберете тема!</h1>";
}
?>
</div>

</body>
</html>
