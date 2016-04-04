<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

mysql_query('SET NAMES utf8');
//for logout
$res = mysql_query("SELECT * FROM users WHERE id=" . $_SESSION['user']);
$userRow = mysql_fetch_array($res);

$refswithoutreviewq = mysql_query("SELECT refs.id as refsid, title, version, files.id as filesid FROM refs JOIN files ON refs.id = files.ref_id WHERE files.review IS NULL");


include_once 'templates/page.php';
$selectedfileid;
$displayform = true;
if (isset($_POST['formSubject'])) {
    $displayform = false;
    $selectedfileid = $_POST['formSubject'];
    $selectedfileq = mysql_query("SELECT * FROM files WHERE id=$selectedfileid");
    $selectedfile = mysql_fetch_array($selectedfileq);
}

if (isset($_POST['reviewtext'])) {
    $revtext = $_POST['reviewtext'];
    $fileidfinal = $_POST['fileid'];


    if (!mysql_query("UPDATE files SET review='$revtext', user_rev_id= '" . $_SESSION['user'] . "' WHERE id=$fileidfinal")) {
        ?>
        <script>
            alert(Нещо
            се
            обърка
            !Моля, опитайте
            отново.
            )
            ;
        </script>
        <?php
    }
}
?>

<div style="text-align: center">

    <?php
if ($displayform) {
?>

    <h1>Избор на тема и версия</h1>
    <form method="post">
        <select name="formSubject" onchange="this.form.submit()">
            <option value="">Select...</option>
            <?php while ($option = mysql_fetch_assoc($refswithoutreviewq)) {
                $refid = $option['refsid'];
                $fileid = $option['filesid'];
                echo "<option value = '$fileid'>Тема $refid. $option[title], версия $option[version]</option>";
            } ?>
        </select>
    </form>

    <?php } if (!$displayform){
    $refverq = mysql_query("SELECT refs.id as refsid, title, filename, version FROM refs JOIN files ON refs.id = files.ref_id WHERE files.id=$selectedfileid");
    $refver = mysql_fetch_array($refverq);
    $curfilename = $refver['filename'];
    echo "<h2>Пишете рецензия за тема " . $refver['refsid'] . "." . $refver['title'] . ", версия " . $refver['version'] . "</h2>
    <form  action='uploads/$curfilename'>
            <input style='width:150px;' type='submit' value='Сваляне на реферата'>
        </form>
        ";
    ?>
    <form style="padding-top: 30px;" method="post">
        <input type="text" style="width:500px; height:200px;" name="reviewtext"/>
        <input type="hidden" name="fileid" value="<?php echo $selectedfileid; ?>"/>
        <p style="text-align: center; padding-top: 20px;"><input type="submit" value="Предай рецензия"/></p>
    </form>
</div>
<?php } ?>

</body>
</html>
