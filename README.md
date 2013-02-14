NSS-TODO List (v1.1)
=======================

Small PHP web script to allow multiple developers on a project maintain a communal TODO list to help development, with useful features.

Thanks to Andrew Chapman/<a href="http://www.thoughtplay.com/">ThoughtPlay</a> for funding/ideas.

Be sure to check out my cron job addition to this script: https://github.com/amadeuspzs/TODO/tree/NSS-TODO-CJ

Be sure to check out the simplified version of this script: https://github.com/amadeuspzs/TODO

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
</ul>

Demo & Use
-------------
The script is ready to run "out-of-the-box", as long as the $files in settings.inc are writeable.

Visit http://amadeus.maclab.org/_demo/nss-todo-1.1/ to see a live demo (resets hourly).

Tips
----

<ul>
<li>You can change the visibility of all those extra options in settings.inc</li>
<li>You can modify a TODO item once you've added it by clicking to the left of the checkbox for that item</li>
<li>You can remove a category by clicking on it's name on the front page</li>
<li>You can rename/recolour a category by simply adding it again on the front page - you will be asked how you wish to proceed after that</li>
</ul>

Revision History
----------------
<p><b>1.1</b>
Minor bugfix.</p>

<p><b>1.0</b>
First release.</p>
