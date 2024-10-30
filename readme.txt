=== HCSB Verse of the Day (Plus) ===
Contributors: danielsummers
Tags: bible, verse, votd
Requires at least: 3.2
Tested up to: 3.4
Stable tag: trunk

This plug-in provides a Verse (or Passage) of the Day, based on the reference
from BibleGateway.com.  Originally written for the Holman Christian Standard
Bible (HCSB), it now also supports the English Standard Version (ESV), New King
James Version (NKJV), New International Version (NIV), and King James Version
(KJV) translations.

== Description ==

HCSB Verse of the Day utilizes the reference given by BibleGateway.com in their
Verse of the Day RSS feed.  For versions they do not provide in their feed, it
parses the content of their website to extract the text of the verse or passage.
The plug-in provides a widget, that will display the verse, a reference, and the
credits for the translation and source (Bible Gateway).  It also provides
several template tags, as well as the ability to utilize a custom set of
template tags in a single tag.  See the documentation at the top of the plug-in
on how to create this.  There is also a settings page, where you can select the
translation to be used.

PHP 5 is required for this plug-in (which is why this requires "at least" 3.2).
I could go back and determine what the earliest version would be where this
would work (I think it's 2.8), but why?  Upgrade and get the security-enhanced
goodness!  :)

Version 3 represents a major refactoring of the code behind this plug-in;
however, if you just used the template tags that were available in version 2,
this should drop in and work seamlessly.  It also eliminates many of the
deprecated WordPress functions that version 2 employed, so you'll want this
to ensure that it doesn't break in an upcoming WordPress upgrade.

As this plug-in parses a web page, please let me know if you find a reference
that does not work.  If BibleGateway changes their HTML, this plug-in will need
to be (and will be) modified.  (The HCSB and NKJV use this parsing.)

NOTE:  BibleGateway.com does not provide its "Verse of the Day" service in the
HCSB, citing copyright restrictions.  However, the copyright does allow for
this content to be used, provided it is not excessive and properly cited.

From the HCSB copyright page...
	"The text of the Holman Christian Standard Bible may be quoted in any form
(written, visual, electronic, or audio) up to an inclusive of two-hundred fifty
(250) verses without the written permission of the publisher, provided that the
verses quoted do not account for more than 20 percent of the work in which they
are quoted, and provided that a complete book of the Bible is not quoted."

So, for BibleGateway, this would account for more than 20% of their Verse of the
Day content; howaver, unless you have nothing else on your blog, it is doubtful
that these verses will account for more than 20% of your content.

== Installation ==

Unzip the archive, and upload the "hcsb-verse-of-the-day" directory to your
wp-content/plugins directory.  Once activated, the widget will be available for
use in your theme, and the template tags will be active.  If you're using
template tags, and want to create your own set of tags (to minimize the
footprint in your theme), modify the file "votd\_hcsb\_custom.php" and upload
it to the same directory.  This custom file will appear as another plug-in in
the WordPress plug-in manager, and will need to be activated separately.
