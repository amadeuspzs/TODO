<?php

/*

Basic TODO list

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

// Where the list is stored:

$file = "todo.list";

/* INTEGRATING THIS PAGE INTO YOUR SITE HTML

   There is one javascript which needs to stay in your <head></head> part if
   you want the "Toggle All" functionality.

   Otherwise replace all the HTML in between the HEADER and FOOTER comments below.

*/

$version = "0.1.2";

$modified = filemtime ( $file );

$hash = sha1_file ( $file );

if ( isset ( $_POST["submit"] ) && $_POST["ohash"] == $hash ) {

	if ( $_POST["submit"] == "Add" && ! empty ( $_POST["data"]) ) {

		$fp = fopen ( $file, "a+" ) or die ("Cannot open $file for writing, check permissions");

		fwrite ( $fp, stripslashes($_POST["data"])."\n" );
		fclose ( $fp ) ;

	} elseif ( $_POST["submit"] == "Remove" ) {

		$data = file ( $file );
		$fp = fopen ( $file , "w+" ) or die ("Cannot open $file for writing, check permissions");
		$n = 0;

		foreach ( $data as $line ) {
				
			if ( empty ( $_POST["line"][$n] ) ) {
				fwrite ( $fp, $line );
			}

			$n++;
				
		}

		fclose ( $fp );

	} elseif ( $_POST["submit"] == "Complete" ) { 

		$data = file ( $file );
		$fp = fopen ( $file , "w+" ) or die ("Cannot open $file for writing, check permissions");
		$n = 0;

		foreach ( $data as $line ) {

			if ( empty ( $_POST["line"][$n] ) ) {
				fwrite ( $fp, $line );
			} else {

				if ( !strstr ( $line , "<strike>") ) {
					fwrite ( $fp, "<strike>" . trim($line) . "</strike>\n" );
				} else {
					$line = str_replace ( "<strike>","",$line );
					$line = str_replace ( "</strike>","",$line );
					fwrite ( $fp, $line );

				}
			}

			$n++;
				
		}

		fclose ( $fp );

	}

	$hash = sha1_file ( "todo.list" );

}

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
</script>
</head>

<body>";

/* HEADER ENDS */

?>
<b>TODO List v <?php echo $version?></b><br />
Updated: <?php echo date ("F d Y H:i:s T", $modified ); ?>, ~ <?php echo timeSince ( $modified );?><br />
<hr />
<form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" name="todo">
<input type="hidden" name="ohash" value="<?php echo $hash ?>" />
<pre>
<?php

	$data = file ( $file );
	$n = 0;

	foreach ( $data as $line ) {

		echo "<input type='checkbox' name='line[$n]' />";
		echo $line ;

		$n++;

	}

?>
</pre>
<hr />
<input type="checkbox" value="on" name="allbox" onclick="checkAll();"/> Toggle all<br />
<input type="text" name="data" size="35" />
<input type="submit" name="submit" value="Add" />
<input type="submit" name="submit" value="Remove" /> 
<input type="submit" name="submit" value="Complete" />
</form>
<?php

/* FOOTER BEGINS */

echo "</body></html>";

/* FOOTER ENDS */

/* FUNCTIONS */

function timeSince ( $timestamp ) {

	$diff = time() - $timestamp;

	if ( $diff < 4000 ) {
		$diff = ceil ( $diff / 60 );
		$unit = "minute";

	} elseif ( $diff < 100000 ) {
		$diff = ceil ( $diff / 3600 );
		$unit = "hour";

	} else {
		$diff = ceil ( $diff / 86400 );
		$unit = "day";
	}

	$end = ( $diff <= 1 ) ? NULL : "s";

	return "$diff $unit$end ago";

}

?>
