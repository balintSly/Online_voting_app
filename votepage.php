<?php
include("login.php");
$conn=new mysqli($host, $user, $password);
$sql="create database if not exists VoteDB";
$conn->query($sql);
$conn->select_db("VoteDB");
$sql="create table if not exists votes(
    id int(6) unsigned auto_increment primary key,
    person varchar(30) not null ,
    votes int(6)
)";
$conn->query($sql);
$table=$conn->query("select * from votes");
//var_dump($table);
//$conn->query("Insert into votes (person, votes) values ('Anna', 0)");
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<form action="" method="post">
<?php
if ($table->num_rows>0)
{
    echo"<table border='3'>
    <tr>
        <td>Name</td>
        <td>Vote counter</td>
        <td>Vote</td>
        <td>Remove</td>
    </tr>";
    while ($row=$table->fetch_assoc())
    {
        echo '
        <tr>
        <td>'.$row["person"].'</td>
        <td align="center">'.$row["votes"].'</td>
        <td><a href="votepage.php?vote='.$row["id"].'">Vote to '.$row["person"].'</a></td>
        <td><a href="votepage.php?remove='.$row["id"].'">Remove '.$row["person"].'</a></td>
</tr>
        ';
    }
    echo "
    </table>";
}?>
    <br>
    <label>Add person</label><br>
    <input type="text" name="personToAdd"><br>
    <input type="submit" value="Add">
</form>
</body>
</html>
<?php
if (isset($_POST["personToAdd"]))
{
    $toadd=$_POST["personToAdd"];
    $conn->query("Insert into votes (person, votes) values ('$toadd', 0)");
    header('Location: votepage.php');
}
if (isset($_GET["vote"]))
{
    $id=$_GET["vote"];
    $votes=$conn->query("select votes from votes where id=$id")->fetch_assoc()["votes"]+1;
    $conn->query("update votes set votes=$votes where id=$id");
    header('Location: votepage.php');
}
if (isset($_GET["remove"]))
{
    $id=$_GET["remove"];
    $conn->query("delete from votes where id=$id");
    header('Location: votepage.php');
}

