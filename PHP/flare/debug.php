<?php
/**
* @package Flare
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* Copyright (C) 2004-2005 Indiana Tech Open Source Committee
* Please direct all questions and comments to TARupp01@indianatech.net
*
* This program is free software; you can redistribute it and/or modify it under the terms of
* the GNU General Public License as published by the Free Software Foundation; either version
* 2 of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with this program;
* if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston,
* MA 02111-1307, USA.
*/

class Debug {
	protected $backtrace;
	protected $zval_dump;
	protected $defined_vars;
	protected $defined_funcs;
	protected $defined_consts;
	protected $start_time;
	protected $end_time;

	public function __construct() {
		session_unset("debug");

		$this->timer_start();
	}

	public function timer_start() {
		$this->start_time = time();
	}

	public function timer_end() {
		$this->end_time = time();
	}

	public function record() {
		
	}

	private function trace() {
		
	}

	public function debug_output() {
		print_r($this->zval_dump());
		print_r($this->defined_funcs());
		print_r($this->defined_consts());
		print_r($this->variable_dump());
		print_r($_SESSION);
	}

	private function zval_dump() {
		return debug_zval_dump();
	}

	private function variable_dump() {
		return var_dump();
	}

	private function defined_funcs() {
		return get_defined_functions();
	}

	private function defined_consts() {
		return get_defined_constants();
	}

	public function __destruct() {
		$this->timer_end();	
	}
}

$debug = new Debug;

echo "<h3>Flare Debug Console</h3>"
. "All available information is dumped to this console for analysis by the designer.<p>";

//echo "<script language='javascript'>"
//. "window.open('debug.php', 'debug', 'toolbar=no,location=no,scrollbars=yes,resizable=yes,width=600,height=400');"
//. "</script>";

$debug->debug_output();
get_defined_vars();
?>
