NSS-TODO List (v1.0)
=======================

Small PHP web script to allow multiple developers on a project maintain a communal TODO list to help development, with useful features.

Thanks to Andrew Chapman/ThoughtPlay for funding/ideas.
Be sure to check out my cron job addition to this script here.
Be sure to check out the simplified version of this script here.

Features
--------
<ul>
<li>Flatfile database (two files)</li>
<li>Add, Complete, Remove, Edit options</li>
<li>Coloured categories</li>
<li>Due dates, visual overdue notification</li>
<li>Basic file locking mechanism to make sure one developer updates at a time (avoids confusion)</li>
<li>Easy to integrate into site template/design</li>
<li>Security available through directory protection (.htaccess) not included</li>
<li>Simplified GUI at click of button, or setting</li>

Demo & Use
-------------
The script is ready to run "out-of-the-box", as long as the $files in settings.inc are writeable.

Visit http://amadeus.maclab.org/_demo/nss-todo-1.0/ to see a live demo (resets hourly).

Revision History
----------------
<p><b>1.0</b>
First release.</p>