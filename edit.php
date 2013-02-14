<?php

/*

NSS-TODO list

--------------------------------------------------------------------
Copyright (c) 2005-2013 Amadeus Stevenson, http://amadeus.maclab.org
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. The name of the author may not be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

require_once 'include.inc';

$todo_list = loadTODO();
$category_list = loadCategories();

$tid = $_REQUEST["id"];

$hash = sha1_file ( $file );

if ( isset ( $_POST["submit"] ) && $_POST["ohash"] == $hash ) { // UPDATE TODO ITEM

	if ( empty ( $_POST["data"] ) ) {
		echo "You didn't write anything in the TODO part. Use the \"Remove\" button to delete a todo item.
		<a href='javascript:history.back()'>Back to editing page</a> or <a href='index.php'>Todo list</a>."; exit;
	}

	foreach ( $todo_list as $stid => $data ) {

		if ( $data["text"] == stripslashes($_POST["data"]) && $stid != $tid ) {
			echo "Sorry that TODO item already exists. <a href='javascript:history.back()'>Back</a>";
			exit;
		}
	}

	$todo_list[$tid]["text"] = stripslashes($_POST["data"]);

	if ( $_POST["category"] !== "None" && isset ( $_POST["category"] ) )
		$todo_list[$tid]["cid"]=$_POST["category"];
	else
		$todo_list[$tid]["cid"]=NULL;

	if ( $_POST["due_day"] !== "None" && !empty ( $_POST["due_day"] ) && $_POST["due_month"] !== "None" && !empty ( $_POST["due_month"] ) )
		$todo_list[$tid]["duedate"] = mktime ( 0 , 0, 0, $_POST["due_month"], $_POST["due_day"] , $_POST["due_year"] );
	else
		$todo_list[$tid]["duedate"] = NULL;

	writeTODO ( $todo_list );

	header("Location: index.php"); exit;
}

$tdata = $todo_list[$tid];

if ( empty ( $tdata["text"] ) ) {
	header ("Location: index.php"); exit;
}

if ( !empty ( $tdata["duedate"] ) ) {
	$due_day = date ( "d" , $tdata["duedate"] );
	$due_month = date ( "m" , $tdata["duedate"] );
	$due_year = date ( "Y" , $tdata["duedate"] );
}

?>
<form action="edit.php" method="POST" name="todo">
<input type="hidden" name="ohash" value="<?php echo $hash ?>" />
<input type="hidden" name="id" value="<?php echo $tid?>">

Categories: <span style='color: red; padding: 2px'>Overdue</span> <?php foreach ( $category_list as $cid => $data ) {
echo "<span style='color: {$data['code']}; padding: 2px;'>{$data['title']}</span> ";
}?><br /><br />

<input type="text" name="data" value="<?php echo htmlentities ( $tdata["text"] ) ?>" size="35" />
<input type="submit" name="submit" value="Update" /> 

Category: <select name="category"><option>None</option>
<?php
foreach ( $category_list as $cid => $data ) {
echo "<option value='$cid'";

if ( strlen ( $tdata["cid"] ) > 0 && $tdata["cid"] == $cid )
	echo " selected";

echo ">{$data['title']}</option>";
}?>
</select>


Due: 
<select name="due_day">
<option>None</option>
<?php for ( $i=1; $i<=31; $i++ ) {
	echo "<option value='$i'";

	if ( $i == @$due_day )
		echo " selected";

	echo ">$i</option>";
}
?>
</select>

<select name="due_month">
<option>None</option>
<?php for ( $i=1; $i<=12; $i++ ) {
	$month = date ( "M", mktime ( 0 , 0 , 0 , $i, 1 ) );
	echo "<option value='$i'";

	if ( $i == @$due_month )
		echo " selected";

	echo ">$month</option>";
}
?>
</select>

<select name="due_year">
<option <?php echo ( @$due_year == ( date ( "Y" ) ) ) ? "selected" : NULL?> value='<?php echo date ( "Y" )?>'><?php echo date ( "Y" )?></option>
<option <?php echo ( @$due_year == ( date ( "Y" ) + 1) ) ? "selected" : NULL?> value='<?php echo date ( "Y" ) + 1?>'><?php echo date ( "Y" ) + 1?></option>
</select>

</form>
