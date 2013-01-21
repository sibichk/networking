<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$host = "127.0.0.1";
set_time_limit(0);
$udpserver = 8211;
$udprouter = 8218;
$buffer = array();
function dest_port($a){
$checksum[1] = 8211;
$checksum[2] = $a;
$checksum[3] = 50;
for($i=1; $i<4; $i++)
{
		$hex[$i] =	dechex($checksum[$i]);
}

for($i=1 ; $i<4 ; $i++)
{
	$hex_sum += $hex[$i];	
}
$full_checksum = ~ $hex_sum;
$word1 = $checksum[1] . $checksum[2];
$word2 = $checksum[3] . $full_checksum;

$width = 100;
$height = 100;

$source = @imagecreatefromjpeg( "source.jpg" );
$source_width = imagesx( $source );
$source_height = imagesy( $source );

for( $col = 0; $col < $source_width / $width; $col++)
{
    for( $row = 0; $row < $source_height / $height; $row++)
    {
        $fn = sprintf( "img%02d_%02d.jpg", $col, $row );

        echo( "$fn\n" );

        $im = @imagecreatetruecolor( $width, $height );
        imagecopyresized( $im, $source, 0, 0,
            $col * $width, $row * $height, $width, $height,
            $width, $height );
        imagejpeg( $im, $fn );
        imagedestroy( $im );
		$word3[] = $fn;
        }
		for($i=0; $i<4; $i++)
{
      array_push($buffer, $word[$i], $word2, $word3[]);
}
$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Unable to create socket\n");
socket_bind($socket, $host, $udpserver) or die("Unable to bind\n");
$result = socket_listen($sock, 4) or die("Unable to listen\n");
socket_write($udprouter, $buffer) or die("Unable to write\n");
socket_close($socket);
    } 

}

dest_port(8219);
dest_port(8220);
dest_port(8221);
dest_port(8222);
dest_port(8223);

?>
</body>
</html>