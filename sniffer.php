<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$socket = socket_create(AF_INET , SOCK_RAW , SOL_TCP);  
socket_recv ( $socket , &$buf , 65536 , 0 );           //socket created and start receiving
process_packet($buf);

function process_packet($packet)                //function to process the packets
{
	$ip_header_fmt = 'Cip_ver_len/'
	.'Ctos/'
	.'ntot_len/'                                              //unpacking of the ip header
	.'nidentification/'
	.'nfrag_off/'
	.'Cttl/'
	.'Cprotocol/nheader_checksum/Nsource_add/Ndest_add/';
	  
	$ip_header = unpack($ip_header_fmt , $packet);              
	if($ip_header['protocol'] == '6')
  	{
    	tcp_packet($packet);                            //check the protocol field to determine if 
  	}                                                            //the packet is UDP or TCP and process
	if($ip_header['protocol'] == '17')         //accordingly
  	{
    	udp_packet($packet);                           
  	}
}

function tcp_packet($packet)               //if it is a TCP packet this function is executed
{
	$ip_header_fmt = 'Cip_ver_len/'
	.'Ctos/'
	.'ntot_len/';                                         
	
	$p = unpack($ip_header_fmt , $packet);
	$ip_len = ($p['ip_ver_len'] & 0x0F);
	
	if($ip_len == 5)
	{
		$ip_header_fmt = 'Cip_ver_len/'
		.'Ctos/'
		.'ntot_len/'                                        //unpacking of TCP headers and 
		.'nidentification/'                             // payload
		.'nfrag_off/'
		.'Cttl/'
		.'Cprotocol/'
		.'nip_checksum/'
		.'Nsource_add/'
		.'Ndest_add/';
  	}
  	else if ($ip_len == 6)
  	{
  		
		$ip_header_fmt = 'Cip_ver_len/'
		.'Ctos/'
		.'ntot_len/'
		.'nidentification/'
		.'nfrag_off/'
		.'Cttl/'
		.'Cprotocol/'
		.'nip_checksum/'
		.'Nsource_add/'
		.'Ndest_add/'
		.'Noptions_padding/';
  	}
  	
  	$tcp_header_fmt = 'nsource_port/'          // defining the required format in which 
	.'ndest_port/'                                          // the extracted TCP header must be placed
	.'Nsequence_number/'                            
	.'Nack_no/'
	.'Coffset_reserved/';
  	
  	$total_packet = $ip_header_fmt.$tcp_header_fmt.'H*data';
  	$p = unpack($total_packet , $packet);
	$tcp_header_len = ($p['offset_reserved'] >> 4);
	if($tcp_header_len == 5)
	{
		
		$tcp_header_fmt = 'nsource_port/'
		.'ndest_port/'
		.'Nsequence_number/'
		.'Nack_no/'
		.'Coffset_reserved/'
		.'Ctcp_flags/'
		.'nwindow_size/'
		.'nchecksum/'
		.'nurgent_pointer/';
	}                                                                        //this is to check if extra padding is 
  	else if($tcp_header_len == 6)                          //needed
  	{
		$tcp_header_fmt = 'nsource_port/'
		.'ndest_port/'
		.'Nsequence_number/'
		.'Nack_no/'
		.'Coffset_reserved/'
		.'Ctcp_flags/'
		.'nwindow_size/'
		.'nchecksum/'
		.'nurgent_pointer/'
		.'Ntcp_options_padding/';
  	}
  	$total_packet = $ip_header_fmt.$tcp_header_fmt.'H*data';  
	$packet = unpack($total_packet , $packet);
  	$sniffer = array(
	
		'ip_header' => array(
			'ip_ver' => ($packet['ip_ver_len'] >> 4) ,
			'ip_len' => ($packet['ip_ver_len'] & 0x0F) ,
			'tos' => $packet['tos'] ,
			'tot_len' => $packet['tot_len'] ,                            //place all the 
			'identification' => $packet['identification'] ,       //fields from the 
			'frag_off' => $packet['frag_off'] ,                       //header to approp.
			'ttl' => $packet['ttl'] ,                                          //array keys
			'protocol' => $packet['protocol'] ,
			'checksum' => $packet['ip_checksum'] ,
			'source_add' => long2ip($packet['source_add']) ,
			'dest_add' => long2ip($packet['dest_add']) ,
			'pay_load' => $packet['data'] ,
		) ,
  );
  
  function udp_packet($packet)
  {                                                                //unpacking of udp header and placing in
$sniffer = array(                                         //appropriate array keys
		'udp_header' => array(                 
			'source_port' => $packet['source_port'] ,
			'dest_port' => $packet['dest_port'] ,
			'udp_header_length' => ($packet['offset_reserved'] >> 4) ,
			'checksum' => $packet['ip_checksum'] ,
			'H.data' => $packet['data'],
			'checksum' => $packet['checksum'] . ' [0x'.dechex($packet['checksum']).']',
		) ,
  	);
}
	$count_my_packets = ("counter.txt");
	$hits = file($no_packet);
	$hits[0] ++;
	$fp = fopen($no_packet , "w");                //function to count the packets arriving
	fputs($fp , "$hits[0]");                              //and incrementing the counter
	fclose($fp);
	
	$mimes = array(
    'text/plain',                      //the MIME types are loaded for future comparison
    'text/anytext'                   //MIME’s of a text file have been loaded
   );

if (in_array($im, $mimes)) 
{
    echo("data is text/n");           //if the payload format matches with values with the
}                                                //values in the array then it is a text file else it is an 
Else                                          //image
{
	echo("data is image/n");
}
$ip_version = $array['ip_ver'];
if ($ip_version ==4)
{	
	echo "The packet contains IPV4 address/n"; }
	else                                                                      //the version is checked to see if 
	{	                                                                // the packet is IPV4 or IPV6
	echo "The packet contains IPV6 address/n";
	}
$fragment = $array['frag_off'];
if ($fragment == 1)
{
	echo "there are no fragments to this packet/n";
}                                                                                  //checks the fragment flag to
	else                                                                       //see if there are fragments to 
	{                                                                          //the packet
		echo "there are fragments to this packet/n";
	}
	
$check = $array['checksum'];
if ($check == 0)
{
	echo "Checksum validated/n";                   //the checksum validation bit is checked 
	}                                                                //and if it is zero outputs the ‘validated’
	Else                                                          //message
	{
		echo "Something seems wrong!! checksum incorrect/n";
		}
		
echo "Source address is " + $array['source_add'] +"/nDestination address is"+ $array['dest_add']+ "/nSource port is"+ $array['source_port']+ "/nDestination address is"+ $array['dest_port'];    //this outputs the source port, ip addr and destination port and ip addr. 
if($buf !=0)
{
	$time = getdate();
	echo "The time when the packet no." +$hits+ "from the ip address"+ $array['source_add']+" arrived is" + $time;
}                    //the time when the packet is received is got from the system and                                                    logged to a text file and also printed on the screen.
		
	echo $hits[0];
	print_r($sniffer);     //after all the functions are processed, the complete output is   
}                                         //displayed
?>

</body>
</html>