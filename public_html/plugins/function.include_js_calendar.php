<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {html_select_date} plugin
 *
 * Type:     function<br>
 * Name:     html_select_date<br>
 * Purpose:  Prints the dropdowns for date selection.
 *
 * ChangeLog:<br>
 *           - 1.0 initial release
 *           - 1.1 added support for +/- N syntax for begin
 *                and end year values. (Monte)
 *           - 1.2 added support for yyyy-mm-dd syntax for
 *                time value. (Jan Rosier)
 *           - 1.3 added support for choosing format for
 *                month values (Gary Loescher)
 *           - 1.3.1 added support for choosing format for
 *                day values (Marcus Bointon)
 * @link http://smarty.php.net/manual/en/language.function.html.select.date.php {html_select_date}
 *      (Smarty online manual)
 * @version 1.3.1
 * @author   Andrei Zmievski
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_include_js_calendar($params, &$smarty)
{
	
	$dir = "http://".$_SERVER['HTTP_HOST']."/js/CalendarPopup.js";
    $html_result .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"".$dir."\"></script>";
    $html_result .= "<script>var cal1 = new CalendarPopup();</script>";
    
 
    
  	return $html_result;
	
}

/* vim: set expandtab: */

?>
