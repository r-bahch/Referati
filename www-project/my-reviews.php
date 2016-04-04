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

$myrefq = mysql_query("select * from refs where user_id=" . $_SESSION['user']);
$myref = mysql_fetch_array($myrefq);


include_once 'templates/page.php';

if (!mysql_num_rows($myrefq) == 0) {
    $myreviewsq = mysql_query("SELECT version, review FROM files where ref_id='" . $myref['id'] . "' and review is not null");
    if (!mysql_num_rows($myreviewsq) == 0) {
        ?>


        <h1>Рецензии за моят реферат</h1>
        <h3>Тема: <?php echo "$myref[title]" ?></h3>
        <table class="deftable">
            <tr>
                <th>Версия</th>
                <th>Рецензия</th>
            </tr>
            <?php while ($row = mysql_fetch_assoc($myreviewsq)) {
                echo "<tr>
<td>" . $row['version'] . "</td>
<td>" . $row['review'] . "</td>
    </tr>";
            }
            ?>
        </table>
    <?php } else { ?>
        <h1 style='text-align: center;'>Все още нямате получени рецензии!</h1>
    <?php }} else {
        echo "<h1 style='text-align: center;'>Все още нямате получени рецензии! Първо изберете тема!</h1>";
    }
?>
</body>
</html>
