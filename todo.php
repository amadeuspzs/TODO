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

if ( $_POST["submit"] == "Add category" || isset ( $_GET["categorydata"] ) ) { // EDIT CATEGORIES

	if ( empty ( $_REQUEST["categorydata"] ) ) {	
		header("Location: index.php"); exit;
	}

	$category_list = loadCategories();

	$code = $_REQUEST["colour"];

	if ( !strstr ( $code , "#" ) )
		$code = "#$code";
	
	if ( $code == "#None" || $code == "#" )
		$code = NULL;

	$category = $_REQUEST["categorydata"];

	foreach ( $category_list as $cid => $data ) {

		if ( $data["title"] == $category )
			$category_exists = $cid;

		if ( $data["code"] == $code && !empty ( $code ) )
			$code_exists = $cid;

	}

	$code_trimmed = substr ( $code , 1 );

	if ( isset ( $_REQUEST["rename"] ) ) {

		$category_list[$code_exists]["title"]=$category;

		writeCategories( $category_list );

		header("Location: index.php");
		exit;

	} elseif ( isset ( $_REQUEST["colourpinch"] ) ) {

		$category_list[$code_exists]["code"]=NULL;
		$category_list[$category_exists]["code"]=$code;

		writeCategories( $category_list );

		header("Location: index.php");
		exit;


	} elseif ( isset ( $_REQUEST["colourpinchadd"] ) ) {

		$category_list[$code_exists]["code"]=NULL;
		$category_list[]["title"]=$category;

		foreach ( $category_list as $wcid => $data ) {
			if ( $data["title"] = $category )
				$cid=$wcid;
		}

		$category_list[$cid]["code"] = $code;

		writeCategories( $category_list );

		header("Location: index.php");
		exit;

	} elseif ( isset ( $_REQUEST["updatecolour"] ) ) {

		$category_list[$category_exists]["code"]=$code;

		writeCategories( $category_list );

		header("Location: index.php");
		exit;

	} elseif ( isset ( $category_exists ) ) {

		echo "The category you want to add already exists: 

		<span style='color: {$category_list[$category_exists]['code']}'>{$category_list[$category_exists]['title']}</span>.";

		if ( $category_list[$category_exists]['code'] !== $code ) {

			if ( !isset ( $code_exists ) ) {

			echo "<br /><br />Do you want to update its colour to  
				<span style='color: $code'>$category</span>?<br /><br />
				<a href='{$_SERVER['PHP_SELF']}?updatecolour=yes&colour=$code_trimmed&categorydata=$category'>Yes</a> or 
				<a href='javascript:history.back();'>Nevermind</a>";

			} else {

			echo "<br /><br />And the colour you picked is in use by another category:
				<span style='color: {$category_list[$code_exists]['code']}'>{$category_list[$code_exists]['title']}</span>";

			echo "<br /><br />Do you want to remove the colour of '{$category_list[$code_exists]['title']}' and change the colour of <span style='color: {$category_list[$category_exists]['code']}'>{$category_list[$category_exists]['title']}</span> to <span style='color: $code'>{$category_list[$category_exists]['title']}</span>?
				<br /><br />
				<a href='{$_SERVER['PHP_SELF']}?colourpinch=yes&colour=$code_trimmed&categorydata=$category'>Yes</a> or 
				<a href='javascript:history.back();'>Nevermind</a>";

			}
		}

	} elseif ( isset ( $code_exists ) ) {

		echo "That colour is already being used by: <span style='color: {$category_list[$code_exists]['code']}'>{$category_list[$code_exists]['title']}</span>.<br /><br />";

		echo "Do you want to rename <span style='color: {$category_list[$code_exists]['code']}'>{$category_list[$code_exists]['title']}</span> to <span style='color: {$category_list[$code_exists]['code']}'>$category</span>?<br /><br />";

		echo "Or do you want to add <span style='color: {$category_list[$code_exists]['code']}'>$category</span> and remove the colour of '{$category_list[$code_exists]['title']}'?";

		echo "<br /><br /><a href='{$_SERVER['PHP_SELF']}?rename=yes&colour=$code_trimmed&categorydata=$category'>Rename the category</a>, <a href='{$_SERVER['PHP_SELF']}?colourpinchadd=yes&colour=$code_trimmed&categorydata=$category'>Add the category with this colour</a>, <a href='javascript:history.back();'>Nevermind</a>";

	} else {	

		$category_list[]["title"] = $category;

		foreach ( $category_list as $wcid => $data ) {
			if ( $data["title"] = $category )
				$cid=$wcid;
		}

		$category_list[$cid]["code"] = $code;

		writeCategories( $category_list );

		header("Location: index.php");
		exit;
	}

	exit;

} else { // ADD TODO ITEM

	$hash = sha1_file ( $file );

	if ( isset ( $_POST["submit"] ) && $_POST["ohash"] == $hash ) {

		$todo_list = loadTODO();

		if ( $_POST["submit"] == "Add" && ! empty ( $_POST["data"]) ) {

			foreach ( $todo_list as $tid => $data ) {
				if ( $data["text"] == stripslashes($_POST["data"]) ) {
					echo "Sorry that TODO item already exists. <a href='javascript:history.back()'>Back</a>";
					exit;
				}
			}

			$todo_list[]["text"] = stripslashes($_POST["data"]);

			foreach ( $todo_list as $tid => $data ) {

				if ( $data["text"] == stripslashes($_POST["data"]) )
					$ntid = $tid;

			}

			if ( $_POST["category"] !== "None" && isset ( $_POST["category"] ) )
				$todo_list[$ntid]["cid"]=$_POST["category"];

			if ( $_POST["due_day"] !== "None" && !empty ( $_POST["due_day"] ) && $_POST["due_month"] !== "None" && !empty ( $_POST["due_month"] ) )
				$todo_list[$ntid]["duedate"] = mktime ( 0 , 0, 0, $_POST["due_month"], $_POST["due_day"] , $_POST["due_year"] );

			writeTODO ( $todo_list );

		} elseif ( $_POST["submit"] == "Remove" ) {

			foreach ( $_POST["todo"] as $tid => $value) {
				$remove[]=$tid;
			}

			foreach ( $todo_list as $ctid => $data ) {
				if ( !in_array ( $ctid , $remove ) )
					$newtodo_list[$ctid] = $todo_list[$ctid];
			}

			writeTODO ( $newtodo_list );

		} elseif ( $_POST["submit"] == "Complete" ) { 

			foreach ( $_POST["todo"] as $tid => $value ) {
				$todo_list[$tid]["status"] = ( $todo_list[$tid]["status"] == "c" ) ? NULL : "c";
			}

			writeTODO ( $todo_list );
		}

	}

}

header("Location: index.php");
exit;

?>