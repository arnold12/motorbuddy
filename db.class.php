<?php

class Db
{

    var $server;
    var $dbname;
    var $user;
    var $password;
	var $debug = false;

	var $on_error = 'report';


	var $connection;
	var $queryresult;

	function Db($oe = 'report')
	{
		$this->on_error = $oe;

		$this->server = DB_HOST;
		$this->dbname = DB_NAME;
		$this->user   = DB_USER;
		$this->password = DB_PASSWD;

		$this->connect();

	}

	function connect($server='', $user='', $password='', $dbname='')
	{
		if ($server)
		{
			$this->server = $server;
			$this->dbname = $user;
			$this->user = $password;
			$this->password = $dbname;
		}

        $this->connection = @mysql_pconnect( $this->server, $this->user, $this->password )
            or $this->error("Could not connect to the database server ($this->server, $this->user)." );

        mysql_select_db( $this->dbname )             or $this->error("Could not select the database ($this->DB)." );
	}

	function &query($q, $print = false)
	{
		if ($this->debug) { echo "<br><b>query: </b>" . htmlentities($q) . "<br>";}

		($this->queryresult = mysql_query($q, $this->connection)) or $this->error("<b>bad SQL query</b>: " . htmlentities($q) . "<br><b>". mysql_error() ."</b>");

		return $this->queryresult;
	}


	function &get_array($sql='')
	{
		if ($sql) { $this->query($sql); }
		return mysql_fetch_array($this->queryresult,MYSQL_ASSOC);
	}

	function free_result()
	{
		return mysql_free_result($this->queryresult);
	}

	/*!
		returns two dimensional assoc array 		frees mysql result
	*/
	function &get_result( $sql = '' )
	{

		if ($sql) { $this->query($sql); }
		$c = 0;
		$res = array();

		while ($row = mysql_fetch_array($this->queryresult,MYSQL_ASSOC))
		{
			$res[$c] = $row;
			$c++;
		}
		mysql_free_result($this->queryresult);
		return $res;
	}

	function get_single_result( $sql = '',$col='')
	{

		if ($sql) { $this->query($sql,$print); }

		if (mysql_num_rows($this->queryresult)){
			$row = mysql_fetch_array($this->queryresult,MYSQL_ASSOC);
			mysql_free_result($this->queryresult);
			return @implode("",$row);
		}
		else{
			return false;
		}


	}

	function &get_result_array( $sql = '' )
	{
		if ($sql) { $this->query($sql); }
		$c = 0;
		while ($row = mysql_fetch_array($this->queryresult))
		{
			$res[$c] = $row;
			$c++;
		}
		mysql_free_result($this->queryresult);
		return $res;
	}

	function &get_double_array( $sql = '' )
        {
                if ($sql) { $this->query($sql); }
                $res = array();

                while ($row = mysql_fetch_array($this->queryresult))
                {
                        $res[$row[0]] = $row[1];
                }
                mysql_free_result($this->queryresult);
                return $res;
	 }


	/*!
		is query result set empty ?
	*/
	function is_empty($sql = '')
	{
		if ($sql) { $this->query($sql); }
		if (0 == mysql_num_rows($this->queryresult))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*!
		is query result set valid ?
	*/
	function not_empty($sql = '')
	{
		if ($sql) { $this->query($sql); }
		if (0 == mysql_num_rows($this->queryresult))
		{
			return false;
		}
		else
		{
			return true;
		}
	}


	function close()
	{
		mysql_close();
	}


	function get_insert_id()
	{
		return mysql_insert_id();
	}



	#	\param $mas assiociative array, keys - column names

	function insert_query($data, $table)
	{
		reset($data);

		$query = 'insert into ' . $table . ' (';
			while (list($columns, ) = each($data)) {
				$query .= $columns . ', ';
			}

		$query = substr($query, 0, -2) . ') values (';
		reset($data);

		while (list(, $value) = each($data)) {

			switch ((string)$value) {
				case 'now()':
				$query .= 'now(), ';
				break;

				case 'null':
				$query .= 'null, ';
				break;

				default:
				$query .= '\'' . addslashes($value) . '\', ';
				break;
			}
		}
		$query = substr($query, 0, -2) . ')';

		$result =& $this->query( $query,true);

		if ($result) { return true; } else { return false; }
	}


	#	\param $mas assiociative array, keys - column names
	function update_query($mas, $table, $id)
	{
		if (is_array($id))
		{
			while(list($idn,$idv)=each($id))
			{
				$where[] = $idn."='$idv'";
			}
		}
		else
		{
			$where[] = "$id";
		}

		while(list($k,$v)=each($mas))
		{
			$to[] = $k."='$v'";
		}

		$sql = "UPDATE $table SET ".implode(',',$to)." WHERE ".implode(" AND ",$where);


		$result =& $this->query( $sql,true);

		return $result;
	}


	/*!
		\param $mas assiociative array, keys - column names
	*/
	function replace_query($mas, $table, $print = false)
	{
		while(list($k,$v)=each($mas))
		{
			$to[] = $k;
			$val[] = $v;
		}

		$sql = "REPLACE INTO $table  (".implode(',',$to).") VALUES ('".implode("','",$val)."')";

		$result =& $this->query( $sql, $print );

		return $result;
	}


    	/*!
    	  Prints the error message.
    	*/
    	function error($errmsg)
    	{
   	    echo  "<br><font color='#CC0066'><b>db</b>: ". $errmsg ."</font><br>";

			if ('halt' == $this->on_error) { exit; }
    	}

	function numrows($sql){
		if ($sql) { $this->query($sql); }
	}


	function getFieldNames(){
		$count = mysql_num_fields($this->queryresult);

		for($i=0; $i < $count; $i++)
			$res[$i] = mysql_field_name($this->queryresult,$i);

		return $res;
	}


	/**
	* Escape string used in sql query
	*/
	function sql_escape($msg)
	{
		if (!$this->connection)
		{
			return @mysql_real_escape_string($msg);
		}

		return @mysql_real_escape_string($msg, $this->connection);
	}


}


?>
