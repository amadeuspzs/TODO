Basic TODO List (v0.1.2)
=======================

Small PHP web script to allow multiple developers on a project maintain a communal TODO list to help development.

Be sure to also check out my Not-So-Simple TODO list: https://github.com/amadeuspzs/NSS-TODO

Features
--------
-Flatfile database (one file)
-Add, Complete, Remove options
-Basic file locking mechanism to make sure one developer updates at a time (avoids confusion)
-Easy to integrate into site template/design (v0.1.1)
-Security available through directory protection (.htaccess) not included.


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
