/**
 * Topnavi.js script provided by
 * Authors: Jeremy Jared and Nathanial Hesse
 * URL: http://wordpress.stackexchange.com/questions/74633/convert-wp-menu-to-a-drop-down-for-mobile-browser/98257
 * License: Under GPL v2
 */

jQuery(document).ready(function(){jQuery('ul:first').attr("id","MENUID");jQuery("ul#MENUID > li:has(ul)").addClass("hasChildren");jQuery("ul#MENUID > li:has(ul) > ul > li > a").addClass("isChild");});jQuery(function(){jQuery("<select />").appendTo(".nav");jQuery("<option />",{"selected":"selected","value":"","text":"Go to...",}).appendTo(".nav select");jQuery(".nav a").each(function(){var el=jQuery(this);jQuery("<option />",{"value":el.attr("href"),"text":el.text(),"class":el.attr("class")}).appendTo(".nav select");});jQuery(".nav select").change(function(){window.location=jQuery(this).find("option:selected").val();});});jQuery(document).ready(function(){jQuery(".nav select option").each(function(){var el=jQuery(this);if(el.hasClass("isChild")){jQuery(el.prepend("- "))}});});