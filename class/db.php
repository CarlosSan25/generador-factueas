<?php

require_once dirname(__FILE__) . "/../conf/conf.php";

class db extends mysqli
{
	# overwrite parent __construct
	public function __construct($hostname = null, $username = null, $password = null, $database = null, $port = null, $socket = null)
    {
		$hostname = $hostname !== null ? $hostname : ini_get("mysqli.default_host");
		$username = $username !== null ? $username : ini_get("mysqli.default_user");
		$password = $password !== null ? $password : ini_get("mysqli.default_pw");
		$database = $database !== null ? $database : "";
		$port     = $port     !== null ? $port     : ini_get("mysqli.default_port");
		$socket   = $socket   !== null ? $socket   : ini_get("mysqli.default_socket");
		
		parent::__construct($hostname,$username,$password,$database,$port,$socket);

        # check if connect errno is set
		if (mysqli_connect_errno()) 
		{
            throw new RuntimeException('Cannot access database: ' . mysqli_connect_error());
        }
		
    }

	 
	# fetches all result rows as an associative array, a numeric array, or both
	# mysqli_fetch_all (PHP 5 >= 5.3.0)
    public function fetch_all($query) 
    {
        $result = parent::query($query);
		if($result) 
		{
			# check if mysqli_fetch_all function exist or not
			if(function_exists('mysqli_fetch_all')) 
			{
				# NOTE: this below always gets error on certain live server
				# Fatal error: Call to undefined method mysqli_result::fetch_all() in /.../class_database.php on line 28
				return $result->fetch_all(MYSQLI_ASSOC);
			}
			
			# fall back to use while to loop through the result using fetch_assoc
			else
			{
				while($row = $result->fetch_assoc())
				{
					$return_this[] = $row;
				}

				if (isset($return_this))
				{
					return $return_this;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return self::get_error();
		}
    }
	
	# fetch a result row as an associative array
	public function fetch_assoc($query)
	{
		$result = parent::query($query);
		if($result) 
		{
			return $result->fetch_assoc();
		} 
		else
		{
			# call the get_error function
			return self::get_error();
			# or:
			# return $this->get_error();
		}
	}

	# display error
	public function get_error() 
    {
        if($this->errno || $this->error)
        {
            return sprintf("Error (%d): %s",$this->errno,$this->error);
        }
    }
	
    public function __destruct()
    {
       parent::close();
		//echo "Destructor Called";
    }
}

$db = new db(
    _HOSTNAME_,
    _DB_USER_,
    _DB_PASS_,
    _DATABASE_,
    _DB_PORT_
);