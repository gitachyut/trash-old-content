<?php
 if(!empty($_POST['day'])){
	trash_post($_POST['day']);
 }

?>

<h1>Remove All Post/Media</h1>
<form method="POST">
Input #Day
<input type="text" name="day">

<input type="Submit" value="Remove All Post">

</form>



