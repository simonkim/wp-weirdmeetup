=== WP-Markdown-SyntaxHighlighter ===
Contributors: mattshelton
Donate link: http://www.mattshelton.net
Tags: markdown, SyntaxHighlighter, syntax, code, pre, highlight
Requires at least: 3.1
Tested up to: 3.4.1
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP-Markdown-SyntaxHighlighter works in conjunction with Markdown-formatted code blocks and SyntaxHighlighter to properly format code.


== Description ==

WP-Markdown-SyntaxHighlighter is intended to work with the [wp-markdown](http://wordpress.org/extend/plugins/wp-markdown/) and [SyntaxHighlighter Evolved](http://www.viper007bond.com/wordpress-plugins/syntaxhighlighter/) plugins as follows:

* WP-Markdown can automatically use [Prettify.js](http://code.google.com/p/google-code-prettify/) to format code, but if you prefer [Alex Gorbatchev's SyntaxHighlighter](http://alexgorbatchev.com/SyntaxHighlighter/), this plugin will re-format the Markdown-formatted code blocks to be properly styled by SyntaxHighlighter directly or SyntaxHighlighter Evolved via plugin.
* SyntaxHighlighter Evolved uses SyntaxHighlighter and additional extended functionality to display code blocks in an easily readable manner.

There are two methods of use:

1. Add a `#!` line to your code example and specify the language (brush) only
2. Add a `#!!` line to your code example and specify any/all supported parameters via a JSON object

= Simple Method =

To use, add a `#!` line as the first line of your code example with the language you are using:

    #!ruby
    class Foo < Bar
      def hello
        puts "Hello World!"
      end
    end

The `#!` is removed, and the code is reformatted as:

    <pre class="brush:ruby; notranslate" title="">class One < Two
      def hello
        puts "Hello World!"
      end
    end</pre>


= Full Method =

To use, add a `#!!` line as the first line of your code example, with any of the supported SyntaxHighlighter parameters as a JSON object

	#!!{"brush":"ruby","toolbar":"true","highlight":"[2,3,4]"}
	class Foo < Bar
	  def hello
	    puts "Hello World!"
	  end
	end
	
The `#!!` is removed, and the parameters are interpreted into a CSS class string as:

	<pre class="brush: ruby; toolbar: true; highlight: [2,3,4]; notranslate">class Foo < Bar
      def hello
        puts "Hello World!"
      end
    end</pre>


== Installation ==

1. Upload the `wp-markdown-syntaxhighlighter` folder (and its contents) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Modify improperly formatted code blocks with your chosen `#!` or `#!!` formatting.


== Changelog ==

= 0.4 =

* Switched from `preg_replace()` to `preg_replace_callback` for ()
* Removed some potentially harmful formatting code
* *Hat tip to Richard Cyrus for suggesting these changes*

= 0.3.1 =

* Updated licensing to GPL 2 in order to post in WP plugin directory
* Cleaned up some comments, code formatting

= 0.3 = 

* Fixed case where both syntaxes could not be used in a single post
* Added support for the title parameter (default: empty)


= 0.2.1 =

* Refactored strings to constants
* Fixed a typo


= 0.2 =

* Added `#!!` syntax to support extended parameters from JSON string
* Added support for the following parameters:
  * auto-links (default: true)
  * class-name (default: '')
  * collapse (default: false)
  * first-line (default: 1)
  * gutter (default: true)
  * highlight (default: null, format is a number or array of numbers)
  * html-script (default: false)
  * ruler (default: false)
  * smart-tabs (default: true)
  * title (default: null) **NOTE**: This does not set the title attribute on the `<pre>` tag yet.
  * tab-size (default: 4)
  * toolbar (default: true)


= 0.1 =

* Initial release


== TODO ==

* Consider adding support to toggle 'notranslate'
