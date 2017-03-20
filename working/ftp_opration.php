<?php

  class ftp_server{

          private $server;
          private $user_name;
          private $password;
          private $ftp_connection;

          function __construct($server,$username,$password){
                $this->server = $server;
                $this->user_name = $username;
                $this->password = $password;
          }

          function ftp_connection_start(){
                    $this->ftp_connection = ftp_connect("$this->server");
                    if(!$this->ftp_connection){
                         return 0;
                     }
                    $conn = ftp_login($this->ftp_connection,"$this->user_name","$this->password");
                    if(!$conn){
                       return 0;
                    }
                    return 1;
          }
          function ftp_nlist($dir){
                    $conn = ftp_server::ftp_connection_start();
                    if(!$conn){
                        return 0;
                    }
                    $result = ftp_nlist($this->ftp_connection,"$dir");
                    ftp_close($this->ftp_connection);
                    return $result;

          }
          function ftp_get($local,$remote){
                    $conn = ftp_server::ftp_connection_start();
                    if(!$conn){
                        return 0;
                    }
                    $result = ftp_get($this->ftp_connection,"$local","$remote",FTP_ASCII);
                    ftp_close($this->ftp_connection);
                    if($result){
                        return 1;
                    }else{
                      return 0;
                    }
          }
          function ftp_put($local,$remote){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                       return 0;
                  }
                  $result = ftp_put($this->ftp_connection,"$remote","$local",FTP_ASCII);
                  ftp_close($this->ftp_connection);
                  if($result){
                       return 1;
                  }else{
                       return 0;
                  }
          }
          function ftp_delete($file){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                       return 0;
                  }
                  $result = ftp_delete($this->ftp_connection,"$file");
                  ftp_close($this->ftp_connection);
                  if($result){
                       return 1;
                  }else{
                       return 0;
                  }
          }
          function ftp_mkdir($dir_name){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                        return 0;
                  }
                  $result = ftp_mkdir($this->ftp_connection,"$dir_name");
                  ftp_close($this->ftp_connection);
                  if($result){
                    return 1;
                  }else{
                    return 0;
                  }
          }
          function ftp_rmdir($dir_name){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                        return 0;
                  }
                  $result = ftp_rmdir($this->ftp_connection,"$dir_name");
                  ftp_close($this->ftp_connection);
                  if($result){
                    return 1;
                  }else{
                    return 0;
                  }
          }
          function ftp_mdtm($file_name){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                        return 0;
                  }
                  $result = ftp_mdtm($this->ftp_connection,"$file_name");
                  ftp_close($this->ftp_connection);
                  if($result){
                    return $result;
                  }else{
                    return 0;
                  }
          }
          function ftp_rename($dir_name){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                        return 0;
                  }
                  $result = ftp_rmdir($this->ftp_connection,"$dir_name");
                  ftp_close($this->ftp_connection);
                  if($result){
                    return 1;
                  }else{
                    return 0;
                  }
          }
          function ftp_size($file_name){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                        return 0;
                  }
                  $result = ftp_size($this->ftp_connection,"$file_name");
                  ftp_close($this->ftp_connection);
                  if($result){
                    return $result;
                  }else{
                    return 0;
                  }
          }
          function ftp_chmod($file_name,$permission){
                  $conn = ftp_server::ftp_connection_start();
                  if(!$conn){
                        return 0;
                  }
                  $result = ftp_chmod($this->ftp_connection,"$permission","$file_name");
                  ftp_close($this->ftp_connection);
                  if($result){
                    return 1;
                  }else{
                    return 0;
                  }
          }
  }

//   $ftp_ob = new ftp_server("localhost","chand","chandpass");
//  $confm = $ftp_ob->ftp_mkdir("/dir/di");


?>
