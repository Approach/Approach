========================================================
====================GETTING STARTED WITH APPROACH==============
========================================================

Rename project directory to yoursite.name and place it in your web server directory along with approach.
/var/www/approach
/var/www/yoursite.com
/var/www/other.com

You may change the directory setup in core.php if you like.


Route all traffic for dynamically generated files to map.php , these are your main end-user URLs
Route all traffic for static files to /static directory , this is for /js, /css, /img files primarily 
Route all traffic for internal web service to the /service directory, this if for directly calling scripts
Route NO TRAFFIC to /support directory, this is only for your application's use internally.

We use the following generalized setup in nginx:

www.root-domain.com and root-domain.com ==> map.php
static.root-domain.com ==> /static/$request_uri
service.root-domain.com ==> /service/$request_uri

chmod 1700 ==> composition (or 750, 770 if needed)
chmod 1700 ==> support (or 750, 770 if needed)
chmod 1704 ==> service (or 754, 774 if needed)
chmod 1704 ==> static (or 754, 774 if needed)

You may be able to make your server such that your application does not need write access.
If this is the case, you may be able to secure Approach further, or if you do not require execute to navigate.


The /composition/ directory will be navigated by RouteFromURL() and ResolveComposition().
There are many ways of editing these two functions, in core.php, to customize your routing pattern.

========================================================
====================URL ROUTING AND COMPOSITIONS===============
========================================================

By default, each row in the "compositions" table has an alias, a parent id, and a type id.
See the "types" table and the "compositions" table for more information.

The final file to be loaded will be:

/composition/TypeOfParent/TypeOfParent/.../TypeOfSelf/compose.php

such that: "http://yourdomain.com/blog/jan 2014/Cool Title"
- could have a row with alias "Cool Title" and type id resolving to 'blogpost'
- whose parent row has alias "jan 2014" and type id resolving to 'grid'
- whose parent's parent row has alias 'blog' and type id resolving to 'grid'

meaning 

/composition/grid/grid/blogpost/compose.php is loaded

While flexible, sometimes it is useful to use a SYMLINK to loopback into the same directory /grid/grid
Alternative, if you can tell ResolveComponent to load directly from the final type. 
This may result in less contextual applications.


such that: "http://yourdomain.com/blog/jan 2014/Cool Title"
loads: /composition/blogpost/compose.php

In either situation, compose.php generally calls require_once on layout.php. 
This allows layouts to be shared across your site easily while being highly malliable. 

Example: Calling unset($Screen); to remove the Screen element of a layout, based on Composition::$Active->context . 

Use Composition:$Active-context to view the database record relating to the current composition and it's parent records. 
Combined with the routing rules, this provides a powerful, typed-url system. Your layout is generally loaded into 
Composition::$Active->DOM . Call Compositin::$Active-prepublish() to resolve current components and handle rendering manually.


========================================================
====================DIRECT ROUTING TO SCRIPTS==================
========================================================

While this is the default project shipped with Approach, it is by no means the primary way of using the Approach classes.
The default project only serves as a springboard for community ideas and a move toward layout and component standards.

You may also begin with standard, direct URL paths, define 

==========================================================
								Layout.php
==========================================================

<?php
require_once('core.php');

$html = new renderable('html'); //$html variable name is optional

//or

Composition::$Active = new Composition();
Composition::$Active->DOM = $html = new renderable('html');

//and continue to fill up your pages..

$html->children[] = $head = new renderable('head');
$html->children[] = $body = new renderable('head');
$body->children[] = $Main = new renderable('div');

?>

==========================================================
								index.php
==========================================================

<?php
require('layout.php');

$Main -> content = "Hi mom!";

//	if you used Compositions in layout.php call..
Composition::$Active->publish();

//	if you used plain renderables call...
print_r( '<!DOCTYPE html>'.$html->render() ); //or echo, for instance.

?>


========================================================
====================COMPOSITION VS RENDERABLE=================
========================================================

The choice of not using a Composition has the following effects.

- Composition will not be able to promote Smart objects to Components for you and ->Load() them.
- To fill up a Smart without a Component, use LoadObjects() and loop through them, putting the $object->data into $yoursmart->data
- If you downloaded a Component from the internet, you may have to find a custom way to initiliaze it.
- Service will not natively know how to reflect upon your script, but it can load the DOM up if you turn rendering off conditionally

So for some situations where you want to simply spit out some XML without much goin on, you may want to be able to 
manually create a renderable tree. For dynamic sites and web apps, you'll most likely benefit from learning to work with
Composition which, along with Component, primarily do the same things you would have had to do anyway.

While they're doing that, they also exist as robust entry points for Service to poke around and provide your site an API automatically.


========================================================
								More Information
========================================================

https://approach.im/
https://github.com/Approach

support@approach.im

========================================================
			Approach is (C) 2002 - 2014 Garet Claborn and Approach Corporation
========================================================

