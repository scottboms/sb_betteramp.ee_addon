<?php
/*
=====================================================
 ExpressionEngine - by EllisLab
-----------------------------------------------------
 http://www.ellislab.com/
=====================================================

INFO ------------------------------------------------
Developed by: Scott Boms, scottboms.com
Created: March 20, 2010
Last Modified: March 20, 2010

This work is licensed under a Creative Commons 
Attribution-ShareAlike 2.5 License.
http://creativecommons.org/licenses/by-sa/2.5/

=====================================================
 File: pi.sb_betteramp.php
-----------------------------------------------------
 Purpose: Improves ampersand rendering by wrapping in
 an <abbr title="and"></abbr> tag.
=====================================================
*/

$plugin_info = array(
  'pi_name'         => "SB BetterAmp",
  'pi_version'      => "1.0",
  'pi_author'       => "Scott Boms",
  'pi_author_url'   => "http://scottboms.com",
  'pi_description'  => "Improves the display of ampersands by wrapping them with "
                      ."<abbr title='and'></abbr> which you can then target using "
                      ."a CSS attribute selector.",
  'pi_usage'        => Sb_betteramp::usage()
);

Class Sb_betteramp {
  var $return_data;

  // ----------------------------------------
  // Replace stuff...
  // ----------------------------------------

  function sb_betteramp($str = '') {
    global $TMPL;

    // Fetch the string
    if($str == '') {
      $str = $TMPL->tagdata;
    }

    // Return the vastly improved string
    $this->return_data = preg_replace("/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w{1,8});)/i","<abbr title='and'>&amp;</abbr>", trim($str));
  }
  // END

// ----------------------------------------
// Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.

function usage() {
ob_start();
?>
One of the guidelines from the seminal book by Robert Bringhurt - "The 
Elements of Typographic Style" states that "In heads and titles, use the
best ampersand available". This plugin attempts to allow you to do just 
that with your text on the web.

To use this plugin, wrap anything you want to be processed by it between the 
following tag pairs. Entry titles and headline text works best.

{exp:sb_betteramp}{title}{/exp:sb_betteramp}

<?php
$buffer = ob_get_contents();
ob_end_clean();
return $buffer;
}
// END
}
// END CLASS
?>