<?php

/*

NSS-TODO-CJ list

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

// Run this via cron ONCE PER DAY

$category_list = loadCategories();
$todo_list = loadTODO();
@uasort ( $todo_list , "duedate" );

$today=mktime ( 0, 0, 0, date ("m") , date ("d") , date ("Y") );

// Go through TODO list, working out what to do with email-checked items

foreach ( $todo_list as $tid => $data ) {

	if ( $data["emailreminder"] == "Y" && !empty ( $data["duedate"] ) 
		&& !empty ( $email_list[$data["emailto"]] ) && $data["status"] !== "c" ) {

		$recipient = $email_list[$data["emailto"]];

		if ( $data["duedate"] == $today ) { // DUE TODAY

			$due_list[$recipient][]=$tid;

		} elseif ( $data["duedate"] < $today ) { // OVERDUE

			$overdue_list[$recipient][]=$tid;

		} elseif ( !empty ( $data["startemailfrom"] ) 
			&& $data["startemailfrom"] <= $today ) { // MAY NEED REMINDING

			$frequency = ( 60 * 60 * 24 ) * $data["emailfreq"];

			$distance = $today - $data["startemailfrom"];

			if ( ( $distance / $frequency ) == ceil ( $distance / $frequency ) )
				$reminder_list[$recipient][]=$tid;

		}

	}

}

// Start constructing email(s)

$title = "NSS-TODO reminder for ".date("D j M");

foreach ( $due_list as $recipient => $list ) {

	$bodies[$recipient] = "DUE TODAY:\n\n";

	foreach ( $list as $key => $tid ) {
		$bodies[$recipient] .= 
			"\t" . $todo_list[$tid]["text"];

		if ( !empty ($category_list[$todo_list[$tid]["cid"]]["title"]) )
			$bodies[$recipient] .= " (".$category_list[$todo_list[$tid]["cid"]]["title"].")";

		$bodies[$recipient] .= "\n";
	}

}

foreach ( $overdue_list as $recipient => $list ) {

	if ( empty ( $bodies[$recipient] ) )
		$bodies[$recipient] = "OVERDUE:\n\n";
	else
		$bodies[$recipient] .= "\nOVERDUE:\n\n";

	foreach ( $list as $key => $tid ) {
		$bodies[$recipient] .= 
			"\t" . $todo_list[$tid]["text"] . " (". date ( "d/m/y" , $todo_list[$tid]["duedate"] ) .")";

		if ( !empty ($category_list[$todo_list[$tid]["cid"]]["title"]) )
			$bodies[$recipient] .= " (".$category_list[$todo_list[$tid]["cid"]]["title"].")";

		$bodies[$recipient] .= "\n";
	}

}

foreach ( $reminder_list as $recipient => $list ) {

	if ( empty ( $bodies[$recipient] ) )
		$bodies[$recipient] = "REMINDERS:\n\n";
	else
		$bodies[$recipient] .= "\nREMINDERS:\n\n";

	foreach ( $list as $key => $tid ) {
		$bodies[$recipient] .= 
			"\t" . $todo_list[$tid]["text"] . " (". date ( "d/m/y" , $todo_list[$tid]["duedate"] ) .")";

		if ( !empty ($category_list[$todo_list[$tid]["cid"]]["title"]) )
			$bodies[$recipient] .= " (".$category_list[$todo_list[$tid]["cid"]]["title"].")";

		$bodies[$recipient] .= "\n";
	}

}

foreach ( $bodies as $recipient => $message ) {

	$body = "Here are the NSS-TODO-CJ reminders for today (".date("D j M")."):\n\n";
	$body .= $message . "\n";
	$body .= "Remember: visit $url for more\n\n";

	mail ( $recipient, $title, $body , "From: NSS-TODO-CJ <$recipient>\r\n" );
}

?>