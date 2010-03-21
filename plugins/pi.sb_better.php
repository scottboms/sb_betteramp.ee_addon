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
Last Modified: March 21, 2010

This work is licensed under a Creative Commons 
Attribution-ShareAlike 2.5 License.
http://creativecommons.org/licenses/by-sa/2.5/

=====================================================
 File: pi.sb_better.php
-----------------------------------------------------
 Purpose: Improves ampersand rendering by wrapping in
 an <abbr title="and"></abbr> tag.
=====================================================
*/

$plugin_info = array(
  'pi_name'         => "SB Better",
  'pi_version'      => "1.0",
  'pi_author'       => "Scott Boms",
  'pi_author_url'   => "http://scottboms.com",
  'pi_description'  => "Improves the display of ampersands by wrapping them with "
                      ."<abbr title='and'></abbr> which you can then target using "
                      ."a CSS attribute selector.",
  'pi_usage'        => Sb_better::usage()
);

Class Sb_better {
  var $return_data = '';

  // ----------------------------------------
  // Replace stuff...
  // ----------------------------------------

  function sb_better($str = '') {
    global $TMPL, $FNS;

    // Fetch the string
    if($str == '') {
      $str = $TMPL->tagdata;
      $str = $this->amp($str);
      return $this->return_data;
    }
  }
  // END

  // Return the vastly improved string
  function amp($str = '') {
    return $this->_apply_search_replace($str, '/(\s|&nbsp;)(&|&amp;|&\#38;)(\s|&nbsp;)/','\1<abbr title="and">&amp;</abbr>\3');
  }

  function _apply_search_replace($str = '', $search, $replace) {
    global $TMPL;

    if ($str == '')
      $str = $TMPL->tagdata;

    $tokens = _TokenizeHTML($str);
    $result = '';
    $in_skipped_tag = false;

    foreach ($tokens as $token) {
      if ($token[0] == 'tag') {
        $result .= $token[1];
        if (preg_match('_' . '_', $token[1], $matches))
          $in_skipped_tag = isset($matches[1]) && $matches[1] == '/' ? false : true;
      } else {
        if ($in_skipped_tag)
          $result .= $token[1];
        else
          $result .= preg_replace($search, $replace, $token[1]);
      }
    }
    return $result;
  }
  // END

  function _TokenizeHTML($str) {
  #
  #   Parameter:  String containing HTML markup.
  #   Returns:    An array of the tokens comprising the input
  #               string. Each token is either a tag (possibly with nested,
  #               tags contained therein, such as <a href="<MTFoo>">, or a
  #               run of text between tags. Each element of the array is a
  #               two-element array; the first is either 'tag' or 'text';
  #               the second is the actual value.
  #
  #
  #   Regular expression derived from the _tokenize() subroutine in 
  #   Brad Choate's MTRegex plugin.
  #   <http://www.bradchoate.com/past/mtregex.php>
  #
    $index = 0;
    $tokens = array();

    $match = '(?s:<!(?:--.*?--\s*)+>)|'.  # comment
      '(?s:<\?.*?\?>)|'.				# processing instruction
        # regular tags
      '(?:<[/!$]?[-a-zA-Z0-9:]+\b(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*>)'; 

    $parts = preg_split("{($match)}", $str, -1, PREG_SPLIT_DELIM_CAPTURE);

    foreach ($parts as $part) {
      if (++$index % 2 && $part != '') 
        $tokens[] = array('text', $part);
      else
        $tokens[] = array('tag', $part);
    }
    return $tokens;
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

{exp:sb_better:amp}{title}{/exp:sb_better:amp}

<?php
  $buffer = ob_get_contents();
  ob_end_clean();
  return $buffer;
  }
  // END
}
// END CLASS
?>