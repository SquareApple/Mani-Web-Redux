<?php
 class rcon {
  /* Do NOT modify (RCon Settings */
  private $packetSize     = '1400';
  private $packets        = 0; 
  private $queryInfo      = "\xFF\xFF\xFF\xFFTSource Engine Query";
  private $replyInfo      = "\x49";
  private $getChallange   = "\xFF\xFF\xFF\xFF\x57";
  private $replyChallange = "\x41";
  private $serverAuth     = 3;
  private $execCommand    = 2;
  private $timeout        = 2;

  /* Server Information, will be dynamicly set later */
  private $serverIP   = '';
  private $serverPort = '';
  private $serverPass = '';
  private $socket;
  private $reqID      = 1;

  function __construct() {
  }
  
  /* Convert newlines to <br/> */
  private function parse(&$s) {
   $junk = substr($s,0,1);
   $s    = substr($s,1);
   if ($r = preg_replace("/(\r\n|\n)/","<br/><-",$s)) return $r;
   else return 0;
  }
  
  /* Actual Section to send and process RCon Information */
  public function send($server,$command, $silent = 1) {
   /* Initalize misc variables */
   $s    = '';
   $data = '';
   $id   = 0;
   
   /*Connect to mysql database and pull rcon info */
   require_once('inc/mysql.class.php');
   $sql        = new sql;
   $serverInfo = array();
   $serverInfo = $sql->getRConInfo($server);
   
   /* Server Information For RCon is put into variables here */
   $this->serverIP = $serverInfo['ip'];
   $this->serverPort = $serverInfo['port'];
   $this->serverPass = $serverInfo['pass'];
   
   /* Open socket and connect */
   if (!$this->socket = fsockopen('tcp://'.$this->serverIP,$this->serverPort,$errno,$errstr, 30)) {
    if (!$silent == 0) echo "Failed to open socket! Error Message: ". $errstr;
    return 0;
   }

   $data = pack("VV",$this->reqID,$this->serverAuth).$this->serverPass.chr(0).$s.chr(0);
   $data = pack("V",strlen($data)).$data;
   
   /* Write the first part of the data that was read */
   if(!fwrite($this->socket,$data,strlen($data))) {
    if (!$silent == 0) echo "Failed to write data (1)!";
    return 0;
   }
   
   $this->reqID++;
   
   /* Read the first part of the data and discard */
   $j      = fread($this->socket,$this->packetSize);
   
   /* Handle the second part of the data to see if auth was reject */
   if (!$string = fread($this->socket,$this->packetSize)) {
    if (!$silent == 0) echo "Failed to read the auth response! (2)";
    return 0;
   }
   $data   = substr($string,0,4); 
   $datas = unpack("Vvalue",$data);
   $id     = $datas['value'];
   if ($id == -1) {
    if (!$silent == 0) echo "Auth failed!";
    return 0;
   }
   
   /* Prepare data to send */
   $data  = pack("VV",$this->reqID,$this->execCommand).$command.chr(0).''.chr(0);
   $data  = pack("V",strlen($data)).$data;
   $this->reqID++;
   $i     = 0;
   
   /* Send data */
   fwrite($this->socket,$data,  strlen($data));
   
   
   $i = 0;
   
   if (!$silent == 0) echo "->".$command."<br/>";
   
   /* Read RCon Feedback */
   while ($string = fread($this->socket,$this->packetSize)) {
    if ($str = $this->parse($string)) $string = $str;
    if (!$silent == 0) echo '<-'.$string;
    return 1;
    
   }
  } 
 }
?>
