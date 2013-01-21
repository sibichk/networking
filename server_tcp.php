<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$host = "168.4.0.27";       //server ip address
set_time_limit(0);         //connection doesn't time out
$tcpserver = 8210;         // port number of server
$tcprouter = 8217;         //port number of router to which the packet will be transmitted
$buffer = array();
$hex_sum = '';

function dest_port($a)                //function to transfer packets
{
	$word1 = 451220;
	$word2 = 0;
	$checksum[1] = 4512;
	$checksum[2] = 20;
	$checksum[3] = 0;
	$checksum[4] = 0;                            // header fields in ip packet
	$checksum[5] = 54;
	$checksum[6] = 168;
	$checksum[7] = 4027;
	$checksum[8] = 168;
	$checksum[9] = 4028;
	for($i=1 ; $i<10 ; $i++)
	{
	$hex[$i] =	dechex($checksum[$i]);            //checksum calculation
	}
	$hex_sum = '';
	for($i=1 ; $i<10 ; $i++)
	{
	$hex_sum = $hex_sum + $hex[$i];	
	}
	 $full_checksum = ~ $hex_sum;                //final result of checksum
	$word3 = 54 . $full_checksum;
	$word4 = $checksum[6] . $checksum[7];
	$word5 = $checksum[8] . $checksum[9];        //all the fields are placed in their positionn
	$word6 = 0;
	$tcp_checksum[1] = 8210;
    $tcp_checksum[2] =$a;
    $tcp_checksum[3] = 0;
    $tcp_checksum[4] = 1;
    $tcp_checksum[5] = 0;
    $tcp_checksum[6] = 0;                         //tcp header fields
    $tcp_checksum[7] = 20000000011001;
    $tcp_checksum[8] = 50;
    $tcp_checksum[9] = 0;
    $tcp_checksum[10] = 0;
    $tcp_checksum[11] = 0;
	
	for($i=0; $i<12; $i++)
	{
		$tcp_hex[$i] =	dechex($tcp_checksum[$i]);
	}

	for($i=1 ; $i<12 ; $i++)
	{
		$tcp_hex_sum += $tcp_hex[$i];	
	}
	$tcp_full_checksum = ~ $tcp_hex_sum;
	$tcp_word1 = $tcp_checksum[1] . $tcp_checksum[$a];
	$tcp_word2 = $tcp_checksum[3] . $tcp_checksum[4];
	$tcp_word3 = $tcp_checksum[5] . $tcp_checksum[6];
	$tcp_word4 = $tcp_checksum[7] . $tcp_checksum[8];                 //tcp header fields
	$tcp_word5 = $tcp_full_checksum . $tcp_checksum[9];
	$tcp_word6 = $tcp_checksum[10] . $tcp_checksum[11];
	$tcp_word7 = "Test packet";
	$width = 100;                                                   // the image to be fragmented is done so with each part holding dimension of 
	$height = 100; 													// 100*100

$source = @imagecreatefromjpeg( "source.jpg" );                     //this is the payload of the packet. A image is loaded and sent
$source_width = imagesx( $source );									//the height and the width of the image is got so that during fragmentation 
$source_height = imagesy( $source );								//the image can be chopped appropriately and sent

for( $col = 0; $col < $source_width / $width; $col++)
{
    for( $row = 0; $row < $source_height / $height; $row++)
    {
        $fn = sprintf( "img%02d_%02d.jpg", $col, $row );          //the chopped images are saved in the fomat of img00_01 and so on

        echo( "$fn\n" );

        $im = @imagecreatetruecolor( $width, $height );           //create an image with the 100*100 dimension
        imagecopyresized( $im, $source, 0, 0,
            $col * $width, $row * $height, $width, $height,
            $width, $height );
        imagejpeg( $im, $fn );                                   //conver to jpg
        imagedestroy( $im );
		$tcp_word7[] = $fn;                                      //place the image on the payload of the tcp packet
        }}
	for($i=0; $i<6; $i++)                                     //push all the values of tcp/ip packet in buffer to make transfer
	{
    	  array_push($buffer, $word1, $word2, $word3, $word4, $word5, $word6, $tcp_word[$i], $tcp_word2, $tcp_word3, $tcp_word4, $tcp_word5, 												          $tcp_word6, $tcp_word7);                                     
	}
	$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Unable to create socket\n");   //create a socket 
	socket_bind($socket, $host, $tcpserver) or die("Unable to bind\n"); 				  //connect to the socket to which the packet is to be 
	$result = socket_listen($sock, 4) or die("Unable to listen\n");                       //transmitted
	socket_write($tcprouter, $buffer) or die("Unable to write\n");                        //send to the target socket
	socket_close($socket);                                                                //close connection
}

dest_port(8212);
dest_port(8213);
dest_port(8214);                                                  //call function whith the parameter being the socket to which the transfer is to 
dest_port(8215);                                                  //be made. 
dest_port(8216);


?>
</body>
</html>