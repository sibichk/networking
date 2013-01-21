<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$host = "168.4.0.29";
$input_port = 8217;
$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Unable to create socket\n");
socket_connect($sock, $host, $port) or die("Unable to connect\n");                       //create a socket, open connection and start listening
socket_listen($sock,6); 																 //a maximum of upto 6 parallel connections are possible
$spawn = socket_accept($sock);
$result = socket_read ($spawn, 1024) or die("Unable to read\n");
$dest_addr = substr("$result",13,7);                                //get the destination ip address and store in $dest_addr
$dest_port = substr("$result", 32, 4);                              //get the destination port number and store in $dest_port
mysql_connect("localhost", "root") or die(mysql_error());  //connect to server
$this->db->select('*');
			$this->db->from('forwarding_table');             //link to table
			$this->db->where('destination_address',$dest_addr);                       //search for a ip address and port number combination 
			$this->db->where('destination_port',$dest_port);                         //that matches in the database
			$query=$this->db->get();          
			if($query->num_rows() > 0)                                          
				{            													//if the search returned a row write the packet to the 
				socket_write($query,$result);                                   //ip address port number combination and close the connection
				socket_close($socket);											//else 
				} else{															// add that combination to the database for future reference.
				$arrayConditions=array(
            'destination_address' => $this->input->post('$dest_addr'),
            'destination_port' => $this->input->post('$dest_port'),
                            );
			}

?>
</body>
</html>