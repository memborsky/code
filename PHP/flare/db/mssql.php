<?php
/**
* @package DB
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
*
*/

/**
* Prevent direct access to the file
*/
defined( '_FLARE_INC' ) or die( "You can't access this file directly." );

/**
* Require the exception class for MSSQL
*/
require_once('extensions/Error_Handler/class.MSSQL_Exception.php');

/**
* Database access tools
*
* Abstraction layer for accessing MSSQL databases
*
* @package DB
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class DB {
	/**
	* Connection to the database is stored here
	*
	* @access public
	* @var resource
	*/
	public $link;

	/**
	* Holds all the queries that can ever be run by the system.
	*
	* @access public
	* @var array
	*/
	public $sql_queries;

	/**
	* Specifies whether an error has been encountered
	*
	* @access public
	* @var bool
	*/
	public $error;

	/**
	* Username used to connect to the database server
	*
	* @access protected
	* @var string
	*/
	protected $username;

	/**
	* Password of username provided
	*
	* @access protected
	* @var string
	*/
	protected $password;

	/**
	* Server IP address where MSSQL database resides
	*
	* @access protected
	* @var string
	*/
	protected $server;

	/**
	* Database that will be used to select data from
	*
	* @access protected
	* @var string
	*/
	protected $db;

	/**
	* Port to connect to database on
	*
	* @access protected
	* @var integer
	*/
	protected $port;

	/**
	* Creates a default database data type to be used
	*
	* This is a default constructor to override the one otherwise
	* created by PHP. This constructor sets up a connection to the
	* database server so that queries can be prepared and executed
	* immediatly
	*
	* @access public
	* @param string $username Username used to connect to the database server
	* @param string $password Password for username used to connect to the database server
	* @param string $db Database that will be used to select data from
	* @param string $server Server hostname or IP address where database resides
	* @param integer $port Port to connect to database on
	* @see connect()
	*/
	public function __construct ($username, $password, $db, $server = "localhost", $port = 3306) {
		/**
		* Holds the connection to the database
		*/
		$this->__set("link", "");

		/**
		* Username used to connect to the database
		*/
		$this->__set("username", $username);

		/**
		* Password for the username used to connect to the database
		*/
		$this->__set("password", $password);

		/**
		* The name of the database where the system tables are stored
		*/
		$this->__set("db", $db);

		/**
		* The server where the above database resides
		*/
		$this->__set("server", $server);

		/**
		* If the user specified a non-standard port for the MSSQL connection, we need
		* to reformat the server string so that it contains the port to use before we
		* connect to the database
		*/
		if ($port != 3306) {
			$this->__set("port", $port);
			$this->__set("server", $this->__get("server") . ":" . $port);
		}

		/**
		* No errors so far
		*/
		$this->__set("error", FALSE);

		/**
		* Performs the connection to the database
		*/
		$this->connect();
	}
	
	/**
	* Returns the value of a class variable
	*
	* Given a class variable name, this method will return the
	* value associated with the name.
	*
	* @access public
	* @param string $key class variable name
	* @return misc $key value stored in variable
	*/
	public function __get( $key ) {
		return isset( $this->$key ) ? $this->$key : NULL;
	}

	/**
	* Sets the value of a class variable
	*
	* Given a class variable name and the value that you wish to
	* store in that variable, this method will store the supplied
	* value in the named variable
	*
	* @access public
	*/
	public function __set( $key, $value ) {
		$this->$key = $value;
	}

	/**
	* Creates a connection to a MSSQL database
	*
	* Two different types of connections are possible, persistant
	* and non-persistant. The type of connection is determined by
	* the constant variable contained in the config file. During
	* this method, the database that will be used is also selected.
	*
	* @access public
	* @return resource Sets the 'link' class variable to point to the connection resource
	*/
	public function connect() {
		/**
		* Open a connection using the type specified in the config file
		*/
		if (_CONNECT_TYPE == "persist") {
			/**
			* A persistant connection, connections are pooled and reused as needed
			* depending on how many people connect to the system.
			*/
			$this->__set("link", mysql_pconnect(	$this->__get("server"), 
								$this->__get("username"), 
								$this->__get("password")));
		} else {
			/**
			* A normal connection. This results in each request by the user requiring a 
			* new connection to the database.
			*/
			$this->__set("link", mysql_connect(	$this->__get("server"), 
								$this->__get("username"), 
								$this->__get("password")));
		}
		
		/**
		* Make sure the database connection was successful
		*/
		if (!is_resource($this->__get("link"))) {
			$this->__set("error", TRUE);
			throw new Exception(_CONNECT_ERROR);
		}

		/**
		* Select the database
		*/
		if (!mysql_select_db($this->__get("db"), $this->__get("link"))) {
			$this->__set("error", TRUE);
                      		throw new Exception(_DB_SELECT_ERROR);
		}
	}

	/**
	* Readies a query for execution
	*
	* All queries must be prepared before they are executed.
	* This makes code easier to read because all possible
	* queries can be prepared immediatly at the beginning of
	* the script and then executed whenever the user wishes.
	*
	* @access public
	* @return DB_Statement Object containing database connection and query to execute
	*/
	public function prepare($query) {
		/**
		* If the database connection has died, re-establish it
		*/
		if (!$this->__get("link")) {
			$this->connect();
		}

		/**
		* Return an object that is specific to the query you want to run.
		* this object will let you run all the data request functions you need.
		*
		* Because each statement is its own object, you can also create and 
		* execute all your queries at the start of your scripts. This can
		* result in a performance boost at times.
		*/
		return new DB_Statement($this->__get("link"), $query);
	}

	/**
	* Executes a query
	*
	* The query to be executed is not specific to
	* an object. This means that you can theoretically
	* execute a query without preparing it first (although
	* it is not recommended). This method will prepare
	* the query for you automatically, execute it, and then
	* set the class variable 'result' to be the result of
	* the query. It will then return to you an object
	* containing the query so that you can run further
	* functions on it.
	*
	* @access public
	* @param string $query The query to execute.
	* @return DB_Statement Object containing results of the query
	*/
	public function execute($query) {
		/**
		* If we lost the connection, reconnect
		*/
		if (!$this->__get("link")) {
			$this->connect();
		}

		/**
		* Perform the SQL query against the database
		*/
		$result = mysql_query($query, $this->__get("link"));

		/**
		* Check to make sure the query ran and that it returned a resource
		*/
		if(!$result) {
			$this->__set("error", TRUE);
			throw new Exception;
		} else if (!is_resource($result)) {
			return TRUE;
		} else {
			/**
			* Return DB_Statement object that can be used to fetch all results
			* from the query that was executed.
			*/
			$stmt = new DB_Statement($this->__get("link"), $query);
			$stmt->__set("result", $result);
			return $stmt;
		}
	}

	/**
	* Closes connection to database
	*
	* This will destroy the database object and close
	* the connection to the MSSQL database server.
	* This method is only called during garbage cleanup
	* by the PHP interpreter.
	*
	* @access public
	*/
	public function __destruct() {
		if ($this->__get("link")) {
			mysql_close($this->__get("link"));
		}
	}
}

/**
* Database query class
*
* Provides access to individual query results so that
* many queries can be prepared and executed simultaneously
* and all their results will be seperate so that they
* can be operated on individually.
*
* @package DB
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class DB_Statement {
	/**
	* The number of variables that were passed to the execute function for inclusion
	* in the SQL query that wants to be executed.
	*
	* @access public
	* @var integer
	*/
	public $binds;

	/**
	* Holds the query that is waiting to be executed. It will never contain the
	* query with the data values inserted.
	*
	* @access public
	* @var string
	*/
	public $query;

	/**
	* Holds the result from the query that is executed against the database
	*
	* @access protected
	* @var resource
	*/
	protected $result;

	/**
	* Holds the connection to the database
	*
	* @access protected
	* @var resource
	*/
	protected $link;

	/**
	* Creates a default database data type to be used
	*
	* This is a default constructor to override the one otherwise
	* created by PHP. This constructor stores the database connection
	* and query in variables and checks to see if the connection
	* to the database is still valid
	*
	* @param resource $link Connection to the database
	* @param string $query Query to be executed
	*/
	public function __construct($link, $query) {
		$this->__set("query", $query);
		$this->__set("link", $link);

		if (!is_resource($link)) {
			$this->__set("error", TRUE);
			throw new Exception(_INVALID_CONNECTION);
		}
	}
	
	/**
	* Returns the value of a class variable
	*
	* Given a class variable name, this method will return the
	* value associated with the name.
	*
	* @access public
	* @param string $key class variable name
	* @return misc $key value stored in variable
	*/
	public function __get( $key ) {
		return isset( $this->$key ) ? $this->$key : NULL;
	}

	/**
	* Sets the value of a class variable
	*
	* Given a class variable name and the value that you wish to
	* store in that variable, this method will store the supplied
	* value in the named variable
	*
	* @access public
	*/
	public function __set( $key, $value ) {
		$this->$key = $value;
	}

	/**
	* Gets a single row from the result set
	*
	* This will return a single row from the result set
	* indexed by an integer value from 0 to the total
	* number of fields returned. It is identical to the
	* similar PHP function 'mysql_fetch_row'
	*
	* @access public
	* @return array Single row containing data in fields queried from database
	*/
	public function fetch_row() {
		if(!$this->__get("result")) {
			$this->__set("error", true);
			throw new Exception(_QUERY_NOT_EXEC);
		}
		return mysql_fetch_row($this->__get("result"));
	}

	/**
	* Gets a single row from the result set
	*
	* This method is different from fetch_row because in
	* addition to returning an integer indexed array of
	* results, it will also include in the array, data 
	* that is indexed by field name. This is slightly
	* more 'headache free' from a developer poitn of view
	* because in the future if you change the number of 
	* fields that are queried for, you will not also need
	* to change the actual usage of the results later in
	* the script because this array contains an index
	* based on field name as well as integer based.
	* This method is identical to the similar PHP function
	* 'mysql_fetch_array'
	*
	* @access public
	* @return array Single row containing data in fields queried from database
	*/
	public function fetch_array() {
		if(!$this->__get("result")) {
			$this->__set("error", true);
			throw new Exception(_QUERY_NOT_EXEC);
		}
		return mysql_fetch_array($this->__get("result"));
	}
	

	/**
	* Gets a single row from the result set
	*
	* Fetches a single row from the result set and
	* returns it as an associative array with the keys
	* being the field name from where the data was pulled.
	* Note that this method is exactly the same as
	* the fetch_array method. However this method is
	* deprecated in place of the fetch_array method
	* and therefore it is not suggested that you use it.
	*
	* @access public
	* @return array Single row containing data in fields queried from database
	* @deprecated Deprecated. Use fetch_array instead
	*/
	public function fetch_assoc() {
		if(!$this->__get("result")) {
			$this->__set("error", true);
			throw new Exception(_QUERY_NOT_EXEC);
		}
		return mysql_fetch_assoc($this->__get("result"));
	}

	/**
	*
	*/
	public function fetchall_assoc() {
		$return_val = array();

		while ($row = $this->fetch_assoc()) {
			$return_val[] = $row;
		}

		return $return_val;
	}

	/**
	* Executes a query
	*
	* Binds and executes a given query. The results of the
	* query are stored in the class variable 'result' where
	* they can be accessed later by helper methods to retrieve
	* the data returned from the query
	*
	* @access public
	* @param misc This method has no set parameter that it takes, but it
	*		will accept ANY NUMBER of parameters you give it. The
	*		provided parameters will be inserted into the query
	*		to be executed.
	* @return resource Pointer to object containing results of the query
	*/
	public function execute() {
		$binds = func_get_args();

		/**
		* This if is only to remove warnings that may crop up about the $binds
		* var not having at least 1 value to run the foreach loop with.
		* This is perfectly possible because some SQL code may not need values
		* passed into it.
		*/
		if (count($binds) > 0) {
			foreach ($binds as $key => $val) {
				$this->binds[$key + 1] = $val;
			}

			$query 	= $this->__get("query");

			foreach ($this->binds as $key => $val) {
				/**
				* We need to use preg_replace instead of str_replace because str_replace
				* will replace ALL occurances of the string. This can lead to bugs in the
				* SQL code if more than 10 variables are passed to the execute method.
				* With preg_replace, we can limit the number of matches, aka the 1 in the
				* function call.
				*/
				$query = preg_replace("/:$key/", mysql_escape_string($val), $query, 1);
			}
		} else {
			$query 	= $this->__get("query");
		}
		
		$this->__set("result", mysql_query($query, $this->__get("link")));

		if (!$this->__get("result")) {
			$this->__set("error", TRUE);
			throw new MSSQL_Exception;
		}

		return $this;
	}
	
	/**
	* Returns the number of rows queried in the SQL query
	*
	* This method is identical to the similar PHP function
	* 'mysql_num_rows'
	*
	* @access public
	* @return integer Number of rows returned by the query
	*/
	public function num_rows() {
		if (!$this->__get("result")) {
			$this->__set("error", TRUE);
			throw new MSSQL_Exception;
		}

		return mysql_num_rows($this->__get("result"));
	}

	/**
	* Returns the name of the field at the given index
	*
	* This method is identical to the similar PHP function
	* 'mysql_fetch_field'
	*
	* @access public
	* @return string Field name at the given index
	*/
	public function field($index = 0) {
		if (!$this->__get("result")) {
			$this->__set("error", TRUE);
			throw new MSSQL_Exception;
		}

		return mysql_fetch_field($this->__get("result"), $index);
	}

	/**
	* Returns a single result from query
	*
	* Returns a single result from the result set at the
	* specified index. This method is identical to the
	* similar PHP function 'mysql_result'
	*
	* @access public
	* @return misc Data from the particular index of the result set
	*/
	public function result($index = 0) {
		if (!$this->__get("result")) {
			$this->__set("error", TRUE);
			throw new MSSQL_Exception;
		}

		return mysql_result($this->__get("result"), $index);
	}

	/**
	* Displays SQL to be executed - brief
	*
	* This will display the SQL that is about to be
	* executed but it will not replace the placemarker
	* strings with the actual values to be used with
	* the query
	*
	* @access public
	*/
	public function show_sql() {
		echo _EXECUTED_QUERY . $this->__get("query");
	}

	/**
	* Displays SQL to be executed - full
	*
	* If variables are provided (exactly like the execute() method),
	* this method will display the actual SQL that is about to be
	* used to query the database. This is an extremely helpful
	* method that can be used when a query dies and you do not
	* know why it died.
	*
	* @access public
	* @param misc This method has no set parameter that it takes, but it
	*		will accept ANY NUMBER of parameters you give it. The
	*		provided parameters will be inserted into the query
	*		to be executed.
	*/
	public function show_sql_executing() {
		$binds = func_get_args();

		/**
		* This if is only to remove warnings that may crop up about the $binds
		* var not having at least 1 value to run the foreach loop with.
		* This is perfectly possible because some SQL code may not need values
		* passed into it.
		*/
		if (count($binds) > 0) {
			foreach ($binds as $key => $val) {
				$this->binds[$key + 1] = $val;
			}

			$query 	= $this->__get("query");

			foreach ($this->binds as $key => $val) {
				/**
				* We need to use preg_replace instead of str_replace because str_replace
				* will replace ALL occurances of the string. This can lead to bugs in the
				* SQL code if more than 10 variables are passed to the execute method.
				* With preg_replace, we can limit the number of matches, aka the 1 in the
				* function call.
				*/
				$query = preg_replace("/:$key/", mysql_escape_string($val), $query, 1);
			}
		} else {
			$query 	= $this->__get("query");
		}

		echo _EXECUTED_QUERY . $query;
	}
}

?>
