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

/* INTEGRATING THIS PAGE INTO YOUR SITE HTML

   There is some javascript which needs to stay in your <head></head> part if
   you want the "Toggle All" functionality and toggle extras visibility.

   Otherwise replace all the HTML in between the HEADER and FOOTER comments below.

*/

$modified = @filemtime ( $file );

/* HEADER BEGINS */

echo "<html>
<head>
<script language=\"javascript\">
function checkAll(){
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		var e=document.forms[0].elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			e.checked=document.forms[0].allbox.checked;
		}
	}
}
function getStyle()
   {
      var temp = document.getElementById(\"extra\").style.visibility;
  
      return temp;
   }

 function switchMain()
  {

      var current = getStyle();

      if( current == \"visible\" )
       {
         document.getElementById(\"extra\").style.visibility = \"hidden\";
       }
       else
       {
         document.getElementById(\"extra\").style.visibility = \"visible\";
       }
  }
</script>
</head>

<body>";

/* HEADER ENDS */

$hash = @sha1_file ( $file );

?>
<b>NSS-TODO List v <?php echo $version?></b><br />
Updated: <?php echo date ("F d Y H:i:s T", $modified ); ?>, ~ <?php echo timeSince ( $modified );?><br />
<hr />
<form action="todo.php" method="POST" name="todo">
<input type="hidden" name="ohash" value="<?php echo $hash ?>" />
<pre>
<?php

	$category_list = loadCategories();
	$todo_list = loadTODO();
	@uasort ( $todo_list , "duedate" );

	foreach ( $todo_list as $tid => $data ) {

		echo "<a title='Edit' style='text-decoration: none' href='edit.php?id=$tid'>&nbsp;</a>";
		echo "<input type='checkbox' name='todo[$tid]' />";

		if ( !empty ( $data["duedate"] ) && $data["duedate"] < time() && $data["status"] !== "c" )
			$overdue = 1;
		else
			$overdue = 0;

		if ( !$overdue && isset ( $data["cid"] ) && !empty ( $category_list[$data['cid']]["code"] ) )
			echo "<span style='color: ".$category_list[$data['cid']]["code"]."'>";
		elseif ( $overdue )
			echo "<span style='color: red'>";

		if ( $data["status"] == "c" )
			echo "<strike>";

		echo $data["text"];

		if ( $data["status"] == "c" )
			echo "</strike>";

		if ( !$overdue && isset ( $data["cid"] ) && !empty ( $category_list[$data['cid']]["code"] ) )
			echo "</span>";


		if ( !empty ( $data["duedate"] ) )
			$date = date ( "d/m/y" , $data["duedate"] );
		else 
			$date = "None";

		echo " ($date)";

		if ( $overdue ) {
			echo "</span>";
			if ( !empty ( $category_list[$data['cid']]["title"] ) )
				echo "(<span style='color: ".$category_list[$data['cid']]["code"]."'>".$category_list[$data['cid']]["title"]."</span>)";

		}

		echo "\n";

	}

?>
</pre>
<hr />
<input type="submit" name="submit" value="Remove" /> 
<input type="submit" name="submit" value="Complete" /> 
<input type="checkbox" value="on" name="allbox" onclick="checkAll();"/> Toggle all 
<a href="#" onclick="switchMain()">[Advanced]</a> Categories: <span style='color: red; padding: 2px'>Overdue</span> <?php foreach ( $category_list as $cid => $data ) {
echo "<a style='text-decoration: none; color: black' title='Remove this category' href='delcat.php?cid=$cid'><span style='color: {$data['code']}; padding: 2px;'>{$data['title']}</span></a> ";
}?><br /><br />

<input type="text" name="data" size="35" />
<input type="submit" name="submit" value="Add" /> 

<span id="extra" style="margin-left: 10px; visibility: <?php echo empty ( $_REQUEST["extra"]) ? $extras_default : $_REQUEST["extra"]?>">

Category: <select name="category"><option>None</option>
<?php
foreach ( $category_list as $cid => $data ) {
echo "<option value='$cid'>{$data['title']}</option>";
}?>
</select>


Due: 
<select name="due_day">
<option>None</option>
<?php for ( $i=1; $i<=31; $i++ ) {
	echo "<option value='$i'>$i</option>";
}
?>
</select>

<select name="due_month">
<option>None</option>
<?php for ( $i=1; $i<=12; $i++ ) {
	$month = date ( "M", mktime ( 0 , 0 , 0 , $i ) );
	echo "<option value='$i'>$month</option>";
}
?>
</select>

<select name="due_year">
<option value='<?php echo date ( "Y" )?>'><?php echo date ( "Y" )?></option>
<option value='<?php echo date ( "Y" ) + 1?>'><?php echo date ( "Y" ) + 1?></option>
</select>

<br /><br />
<input type="text" name="categorydata" size="35" />
<input type="submit" name="submit" value="Add category" />
<select name="colour">
<option>None</option>
<?php
foreach ( $category_colours as $name => $hex ) {
	echo "<option value=\"$hex\">$name</option>";
}
?>
</select>
</form>

</span>
<?php

/* FOOTER BEGINS */

echo "</body></html>";

/* FOOTER ENDS */

?>