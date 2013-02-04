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

require_once 'settings.inc';

/* Load TODO list */

function loadTODO () {

	global $file;

	$data = @file ( $file );

	foreach ( $data as $line ) {
		list ( $tid, $status, $text, $cid, $duedate ) = split ( "\|" , trim ( $line ) );

		$todo_list[$tid]["status"]=$status;
		$todo_list[$tid]["text"]=$text;
		$todo_list[$tid]["cid"]=$cid;
		$todo_list[$tid]["duedate"]=$duedate;

	}

	return @$todo_list;

}

/* Write TODO List */

function writeTODO( $todo_list ) {

	global $file;

	$fp = fopen ( $file , "w" ) or die ("Can't open todo list for writing");

	foreach ( $todo_list as $tid => $data ) {
		fwrite ( $fp, "$tid|{$data['status']}|{$data['text']}|{$data['cid']}|{$data['duedate']}\r\n" );
	}

	fclose ( $fp );
}

/* Load category definitions */

function loadCategories () {

	global $categories;

	$data = @file ( $categories );

	foreach ( $data as $line ) {
		list ( $cid, $code, $category ) = split ( "\|" , trim ( $line ) );
		$category_list[$cid]["code"]=$code;
		$category_list[$cid]["title"]=$category;
	}

	return @$category_list;
}

/* Write Category Definitions */

function writeCategories( $category_list ) {

	global $categories;

	$fp = fopen ( $categories , "w" ) or die ("Can't open category list for writing");

	foreach ( $category_list as $cid => $data ) {
		fwrite ( $fp, "$cid|{$data['code']}|{$data['title']}\r\n" );
	}

	fclose ( $fp );
}

/* Sort List */

function duedate ( $a , $b) {

	if ( empty ( $a["duedate"] ) )
		$a["duedate"] = 1e22;

	if ( empty ( $b["duedate"] ) )
		$b["duedate"] = 1e22;

	if ($a["duedate"] == $b["duedate"])
		return 0;

	return ($a["duedate"] < $b["duedate"]) ? -1 : 1;

}

/* Calculate time since event */

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