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

$haschosenq = mysql_query("SELECT * FROM refs WHERE user_id=" . $_SESSION['user']);

$refsq = mysql_query("SELECT * FROM refs");

if (isset($_POST['btn-subj-smbt'])) {
    $refid = $_POST['subj'];
    $userid = $_SESSION['user'];
    if (mysql_query("UPDATE refs SET user_id = $userid, is_free=0 WHERE id='$refid'")) {
        header("Location: home.php");
    }
}

include_once 'templates/page.php';
?>


<?php if (mysql_num_rows($haschosenq) == 0){?>
<form method='post'>
    <table class="deftable">
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Описание</th>
            <th>Избор</th>
        </tr>
        <?php
        while ($row = mysql_fetch_assoc($refsq)) {
            echo "
    <tr>
        <td>$row[id]</td>
        <td>$row[title]</td>
        <td>$row[description]</td>
        <td>";
            if ($row['is_free']) {
            echo "<input type='radio' name='subj' value='$row[id]'/>";
        }
        echo "</td>
    </tr>
    ";
        }
        ?>
    </table>
    <div class="btnwrap">
        <button class="button" type="submit" name="btn-subj-smbt">Избор</button>
    </div>
    <p><?php echo $_SESSION['user'];?></p>
</form>
<?php } else {
    $selectedtheme = mysql_fetch_array($haschosenq);?>
    <h1 style="text-align: center; margin: 30px;">Вече сте избрали тема: </br> <?php echo $selectedtheme['title'];?></h1>
</body>
</html>
<?php }?>