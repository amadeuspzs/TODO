Basic TODO List (v0.1.2)
=======================

Small PHP web script to allow multiple developers on a project maintain a communal TODO list to help development.

Be sure to also check out my Not-So-Simple TODO list: https://github.com/amadeuspzs/NSS-TODO

Features
--------
<ul>
<li>Flatfile database (one file)</li>
<li>Add, Complete, Remove options</li>
<li>Basic file locking mechanism to make sure one developer updates at a time (avoids confusion)</li>
<li>Easy to integrate into site template/design (v0.1.1)</li>
<li>Security available through directory protection (.htaccess) not included.</li>
</ul>

Demo & Use
-------------
The script is ready to run "out-of-the-box", as long as the $file is writeable.

Visit http://amadeus.maclab.org/_demo/todo-0.1.2/ to see a live demo (resets hourly).

Revision History pre-GitHub
---------------------------
0.1.2
Added "Toggle All", improved form control elements (pressing return adds a new entry).

0.1.1
Included easy HTML integration.

0.1
First release.
