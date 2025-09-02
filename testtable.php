<?php
require('tabledata.php');
$sql = "select * from  idtimename";
$result = mysqli_query($con, $sql);

while($row=mysqli_fetch_assoc($result)){
    echo"รหัสนักศึกษา = ".$row["enrollnumber"]."<br>";
}

?>