<?php
namespace Air;
final class data_base_query{

     private $link_open;
     private $host;
     private $user;
     private $key;

     function __construct($host,$user,$key){
           $this->host = $host;
           $this->user = $user;
           $this->key = $key;
     }   
     function db_conn($data_base){
            $this->link_open = mysqli_connect("$this->host","$this->user","$this->key");
            if(!$this->link_open){ 
                   echo "Failed to connect to MySQL: " . mysqli_connect_error();
                   return 0;
             }
            $dc = mysqli_select_db($this->link_open,"$data_base");
            if(!$dc){
                   mysqli_close($this->link_open);
                   unset($dc);
                   echo"<br>unable to connect database<br>";
                   return 0;
             }
             return 1;
     }
      function insert_value(){
                 $arg_num = func_num_args();
                 $arguments = func_get_args();
                        if($arg_num >=4 && $arg_num%2 == 0){
                              if(!data_base_query::db_conn($arguments[0])){
                                     return 0;
                              }
                              $Q = "INSERT INTO $arguments[1] (";
                              $x = 2;
                              while($x < ($arg_num-2)/2+2){
                                    if($x == 2){
                                           $Q = $Q.$arguments[$x];
                                    }else{
                                           $Q = $Q." , ".$arguments[$x];
                                    }
                                    $x++;
                              }
                             $Q = $Q.") VALUES ("; 
                             while($x < $arg_num){
                                    if($x == ($arg_num-2)/2+2){
                                           $Q = $Q."'".$arguments[$x]."'";
                                    }else{
                                           $Q = $Q.",'".$arguments[$x]."'";
                                    }
                                    $x++;
                              }
                              $Q = $Q." )";
                              if(mysqli_query($this->link_open,$Q)){ 
                                    if(mysqli_affected_rows($this->link_open) > 0){              
                                           mysqli_close($this->link_open);
                                           return 1;
                                     }else{
                                            mysqli_close($this->link_open);
                                            return 0;
                                     }
                              }else{
                                          mysqli_close($this->link_open);      
                                          return 0;
                              }
                       }else{
                              echo"<br>check number of arguments<br>";
                              return 0;
                       }
        }
        function delete(){
                   $arg_num = func_num_args();
                   $arguments = func_get_args();
                   if($arg_num == 4 || $arg_num == 6){
                        if(!data_base_query::db_conn($arguments[0])){
                                return 0;
                         }
                         if($arg_num == 4){
                               if($arguments[3] == "NULL"){
                                         $Q = "DELETE FROM $arguments[1] WHERE $arguments[2] IS $arguments[3]"; 
                                }else{
                                         $Q = "DELETE FROM $arguments[1] WHERE $arguments[2] = '$arguments[3]'"; 
                                }
                         }
                         else if($arg_num == 6){
                                $Q = "DELETE FROM $arguments[1] WHERE $arguments[2] ";
                                if($arguments[3] == "NULL"){
                                         $Q = $Q." IS ".$arguments[3]." AND $arguments[4]";
                                }else{
                                         $Q = $Q." = '".$arguments[3]."' AND $arguments[4]";
                                }
                                if($arguments[5] == "NULL"){
                                         $Q = $Q." IS ".$arguments[5];
                                }else{
                                         $Q = $Q." = '".$arguments[5]."'";
                                }
                         }
                         if(mysqli_query($this->link_open,$Q)){
                                  if($result = mysqli_affected_rows($this->link_open)){  
                                        mysqli_close($this->link_open);
                                        return $result;
                                  }else{
                                         mysqli_close($this->link_open);
                                         return 0 ;
                                  }
                         }else{
                                        mysqli_close($this->link_open);
                                        return 0;
                         } 
                    }else{
                         echo"<br>Check number of arguments<br>";
                         return 0;
                    }
          }
          function search_value(){
                   $arg_num = func_num_args();
                   $arguments = func_get_args();
                     if($arg_num > 4){
                               if(!data_base_query::db_conn($arguments[0])){
                                        return 0;
                                }
                               $Q = "SELECT ";
                               $x = 2;
                               while($x < $arg_num-2){

                                    if($x == 2){
                                         $Q = $Q.$arguments[$x];
                                   }else{
                                         $Q = $Q." , ".$arguments[$x];
                                     }
                                    $x++;
                               }
                              $Q = $Q." FROM ". $arguments[1] . " WHERE ". $arguments[$x] ." LIKE  '". $arguments[++$x]."'";
                              if($runquery = mysqli_query($this->link_open,$Q)){
                                     if(mysqli_num_rows($runquery) > 0){
                                         $result = array();
                                         while($data = mysqli_fetch_array($runquery)){
                                               if($arg_num == 5){
                                                    $result[] =  $data[$arguments[2]];
                                                }else if($arg_num > 3){
                                                    $x = 2;
                                                    while($x < $arg_num-2){
                                                        $result[$x-2][] = $data[$arguments[$x]];
                                                         $x++;
                                                     }
                                                }                                       
                                          }
                                          mysqli_close($this->link_open);
                                          return $result;
                                     }else{
                                          mysqli_close($this->link_open);
                                          return 0 ;
                                      }
                              }else{
                                       echo"<br> make sure your arguments once again<br>";
                                       mysqli_close($this->link_open);
                                       return 0 ;
                               }
                     }else{
                             echo"check number of arguments";
                             return 0;
                     }
             }
      function update_value(){
            $arg_num = func_num_args();
            $arguments = func_get_args();
            if($arg_num >=6 && $arg_num%2 == 0){
                    $arg_num = $arg_num-4;
                    if(!data_base_query::db_conn($arguments[0])){
                            return 0;
                     }
                    $Q = "UPDATE $arguments[1] SET ";
                    $x = 2;
                    while($x < $arg_num+2){
                         if($x == 2){
                                  $Q = $Q." ".$arguments[$x] ." = '". $arguments[++$x]."' ";
                          }else{
                                  $Q = $Q." , ".$arguments[$x] ." = '". $arguments[++$x]."'  ";
                          }
                          $x++;
                     }
                    $Q = $Q." WHERE ". $arguments[$x];
                    if($arguments[++$x] == "NULL"){
                        $Q = $Q." IS ".$arguments[$x];
                    }else{
                        $Q = $Q." = '".$arguments[$x]."'";
                    }
                   if($runquery = mysqli_query($this->link_open,$Q)){
                         if($result = mysqli_affected_rows($this->link_open)){               
                               mysqli_close($this->link_open);
                               return $result;
                          }else{
                               mysqli_close($this->link_open);
                               return 0;
                          }
                    }else{
                            mysqli_close($this->link_open);      
                            return 0;
                    }
             }else{
                    echo"check number of arguments";
                    return 0;
            }
 }
 function d_update_value(){
            $arg_num = func_num_args();
            $arguments = func_get_args();
            if($arg_num >=8 && $arg_num%2 == 0){
                    $arg_num = $arg_num-6;
                    if(!data_base_query::db_conn($arguments[0])){
                            return 0;
                     }
                    $Q = "UPDATE $arguments[1] SET ";
                    $x = 2;
                    while($x < $arg_num+2){
                         if($x == 2){
                                  $Q = $Q." ".$arguments[$x] ." = '". $arguments[++$x]."' ";
                          }else{
                                  $Q = $Q." , ".$arguments[$x] ." = '". $arguments[++$x]."'  ";
                          }
                          $x++;
                     }
                    $Q = $Q."  WHERE ". $arguments[$x];
                     if($arguments[++$x] == "NULL"){
                              $Q = $Q." IS ".$arguments[$x]." AND ".$arguments[++$x];
                     }else{
                              $Q = $Q." = '".$arguments[$x]." AND ".$arguments[++$x];
                     }
                     if($arguments[++$x] == "NULL"){
                              $Q = $Q." IS ".$arguments[$x];
                     }else{
                              $Q = $Q." = '".$arguments[$x];
                     }
                   if($runquery = mysqli_query($this->link_open,$Q)){
                        if($result = mysqli_affected_rows($this->link_open)){               
                               mysqli_close($this->link_open);
                               return $result;
                        }else{
                               mysqli_close($this->link_open);
                               return 0;
                        }
                    }else{
                            mysqli_close($this->link_open);      
                            return 0;
                    }
             }else{
                    echo"check number of arguments";
                    return 0;
             }
 }
 function return_value(){
         $arg_num = func_num_args();
         $arguments = func_get_args();
         if($arg_num > 2){
             if(!data_base_query::db_conn($arguments[0])){
                        return 0;
               }
             $Q = "SELECT ";
             $x = 2;
               while($x < $arg_num){
                    if($x == 2){
                         $Q = $Q." ".$arguments[$x];
                     }else{
                         $Q = $Q." , ".$arguments[$x];
                    }
                    $x++;
               }
               $Q = $Q." FROM ".$arguments[1];
               if($runquery = mysqli_query($this->link_open,$Q)){
                     if(mysqli_num_rows($runquery) >= 1){
                           $result = array();
                           while($data = mysqli_fetch_array($runquery)){
                                if($arg_num == 3){
                                     $result[] =  $data[$arguments[2]];
                                 }else if($arg_num > 3){
                                     $x = 2;
                                     while($x < $arg_num){
                                         $result[$x-2][] = $data[$arguments[$x]];
                                         $x++;
                                     }
                                 }                                       
                            }
                            mysqli_close($this->link_open);
                            return $result;
                     }else{
                            mysqli_close($this->link_open);
                            return 0 ;
                     }
                }else{
                            echo"<br> make sure your arguments once again<br>";
                            mysqli_close($this->link_open);
                            return 0 ;
                }
           }else{
               echo "<br>check number of arguments</br>";
               return 0;
          }
 }
 function return_match_value(){
       $arg_num = func_num_args();
        $arguments = func_get_args();
         if($arg_num > 4){
              if(!data_base_query::db_conn($arguments[0])){
                        return 0;
               }
             $Q = "SELECT ";
             $x = 2;
               while($x < $arg_num-2){
                    if($x == 2){
                         $Q = $Q." ".$arguments[$x];
                     }else{
                         $Q = $Q." , ".$arguments[$x];
                    }
                    $x++;
               }
               $Q = $Q." FROM ".$arguments[1]." WHERE ".$arguments[$x];
                if($arguments[++$x] == "NULL"){
                  $Q = $Q." IS ".$arguments[$x];
                }else{
                  $Q = $Q." = '".$arguments[$x]."'";
                }
              
               if($runquery = mysqli_query($this->link_open,$Q)){
                     if(mysqli_num_rows($runquery) >= 1){
                           $result = array();
                           while($data = mysqli_fetch_array($runquery)){
                                if($arg_num == 5){
                                     $result[] =  $data[$arguments[2]];
                                 }else {
                                     $x = 2;
                                     while($x < $arg_num-2){
                                         $result[$x-2][] = $data[$arguments[$x]];
                                         $x++;
                                     }
                                 }                                       
                            }
                            mysqli_close($this->link_open);
                            return $result;
                     }else{
                            mysqli_close($this->link_open);
                            return 0 ;
                     }
                }else{
                            echo"<br> make sure your arguments once again<br>";
                            mysqli_close($this->link_open);
                            return 0 ;
                }
        }else{
            echo"<br>check number of arguments<br>";
             return 0;
        }
 }
 function return_d_match_value(){
       $arg_num = func_num_args();
        $arguments = func_get_args();
         if($arg_num > 6){
              if(!data_base_query::db_conn($arguments[0])){
                        return 0;
               }
               $Q = "SELECT ";
               $x = 2;
               while($x < $arg_num-4){
                    if($x == 2){
                         $Q = $Q." ".$arguments[$x];
                     }else{
                         $Q = $Q." , ".$arguments[$x];
                    }
                    $x++;
               }
               $Q = $Q." FROM ".$arguments[1]." WHERE ".$arguments[$x];

               if($arguments[++$x] == "NULL"){
                    $Q = $Q." IS ".$arguments[$x]." AND ". $arguments[++$x];
               }else{
                    $Q = $Q." ='".$arguments[$x]."' AND ".$arguments[++$x];
               }
               if($arguments[++$x] == "NULL"){
                    $Q = $Q." IS ".$arguments[$x];
               }else{
                    $Q = $Q." = '".$arguments[$x]."'";
               }

               if($runquery = mysqli_query($this->link_open,$Q)){
                     if(mysqli_num_rows($runquery) >= 1){
                           $result = array();
                           while($data = mysqli_fetch_array($runquery)){
                                if($arg_num == 7){
                                     $result[] =  $data[$arguments[2]];
                                 }else{
                                     $x = 2;
                                     while($x < $arg_num-4){
                                         $result[$x-2][] = $data[$arguments[$x]];
                                         $x++;
                                     }
                                 }                                       
                            }
                            mysqli_close($this->link_open);
                            return $result;
                     }else{
                            mysqli_close($this->link_open);
                            return 0 ;
                     }
                }else{
                            echo"<br> make sure your arguments once again<br>";
                            mysqli_close($this->link_open);
                            return 0 ;
                }
        }else{
          echo"<br>check number of arguments<br>";
          return 0;
        }
 } 
 function drop_table(){
           $args_num = func_num_args();
           $arguments = func_get_args();
           if($args_num == 2){
                  if(!data_base_query::db_conn($arguments[0])){
                        return 0;
                  }
                  $Q = "DROP TABLE $arguments[1]";
                  if(mysqli_query($this->link_open,$Q)){
                        mysqli_close($this->link_open);
                        return 1;
                  }else{
                        mysqli_close($this->link_open);
                        return 0;
                  }
           }else{
                    mysqli_close($this->link_open);
                    return 0;
           }
 }
  function add_field(){
           $args_num = func_num_args();
           $arguments = func_get_args();
           if($args_num == 4){
               if(!data_base_query::db_conn($arguments[0])){
                        return 0;
               }
              $Q = "ALTER TABLE $arguments[1] ADD $arguments[2] $arguments[3]";
              if(mysqli_query($this->link_open,$Q)){
                   mysqli_close($this->link_open);
                   return 1;
              }else{
                   mysqli_close($this->link_open);
                   return 0;
              }
           }else{
                  echo"<br>check number of arguments<br>";
                  return 0;
           }
  }
  function remove_field(){
            $args_num = func_num_args();
            $arguments = func_get_args();
            if($args_num > 2){
                   if(!data_base_query::db_conn($arguments[0])){
                          return 0;
                   }
                   $Q = "ALTER TABLE $arguments[1] DROP ";
                   $x = 2;
                   while($x < $args_num){
                          if($x == 2){       
                                    $Q = $Q.$arguments[$x];
                          }else{
                                    $Q = $Q.", DROP ".$arguments[$x];
                          }
                          $x++;
                   }
                   if(mysqli_query($this->link_open,$Q)){
                                  mysqli_close($this->link_open);
                                  return 1;
                   }else{
                            mysqli_close($this->link_open);
                            return 0;
                   }
            }else{
                  echo"<br>check number of arguments<br>";
                  return 0;
            }
  }
     function select_query(){           
            $args_num = func_num_args();
            $arguments = func_get_args();
            if($args_num > 3){
                 if(!data_base_query::db_conn($arguments[0])){
                          return 0;
                   }
                  $Q = "SELECT ";  
                   $x = 2;
                   while($x < $args_num-1){   
                       if($x == 2){
                            $Q = $Q.$arguments[$x];
                       }else{
                            $Q = $Q.",".$arguments[$x];
                       }
                       $x++;
                   }
                   $Q = $Q." FROM ".$arguments[1]." ".$arguments[$x];
                   if($runquery = mysqli_query($this->link_open,$Q)){
                        if(mysqli_num_rows($runquery) >= 1){
                              $result = array();
                              while($data = mysqli_fetch_array($runquery)){
                                   if($args_num == 4){
                                         $result[] =  $data[$arguments[2]];
                                    }  else {
                                         $x = 2;
                                         while($x < $args_num-1){
                                              $result[$x-2][] = $data[$arguments[$x]];
                                              $x++;
                                         }
                                   }                                       
                              }
                            mysqli_close($this->link_open);
                            return $result;
                        }else{
                            mysqli_close($this->link_open);
                            return 0 ;
                         }
                    }else{
                            echo"<br> make sure your arguments once again<br>";
                            mysqli_close($this->link_open);
                            return 0 ;
                   }
            }else{ 
                  echo"<br>check number of arguments<br>";
                  return 0;
            }
  }
    function change_field(){
            $args_num = func_num_args();
            $arguments = func_get_args();
            if($args_num = 2){
                   if(!data_base_query::db_conn($arguments[0])){
                          return 0;
                   }
                   $Q = "ALTER TABLE $arguments[1] CHANGE $arguments[2] $arguments[3] $arguments[4]";
                   if(mysqli_query($this->link_open,$Q)){
                                  mysqli_close($this->link_open);
                                  return 1;
                   }else{
                            mysqli_close($this->link_open);
                            return 0;
                   }
            }else{
                  echo"<br>check number of arguments<br>";
                  return 0;
            }
  }
  function rename_table(){
            $args_num = func_num_args();
            $arguments = func_get_args();
            if($args_num = 3){
                   if(!data_base_query::db_conn($arguments[0])){
                          return 0;
                   }
                   $Q = "ALTER TABLE $arguments[1] RENAME TO $arguments[2] ";
                   if(mysqli_query($this->link_open,$Q)){
                                  mysqli_close($this->link_open);
                                  return 1;
                   }else{
                            mysqli_close($this->link_open);
                            return 0;
                   }
            }else{
                  echo"<br>check number of arguments<br>";
                  return 0;
            }
  }
     function join_select(){
          $args_num = func_num_args();
          $arguments = func_get_args();
          if($args_num > 2){
                  if(!data_base_query::db_conn($arguments[0])){
                        return 0;
                   }
                  $Q = "SELECT ";
                  $x = 1;
                  while($x < $args_num-1){
                      if($x == 1){
                           $Q = $Q.$arguments[$x];
                      }else{
                           $Q = $Q.",".$arguments[$x];
                      }
                      $x++;
                  }
                  $Q = $Q." ".$arguments[$x];
                 if($runquery = mysqli_query($this->link_open,$Q)){
                      if(mysqli_num_rows($runquery) >= 1){
                           $result = array();
                           while($data = mysqli_fetch_array($runquery)){
                               if($args_num == 3){
                                   $result[] =  $data[$arguments[2]];
                                }else {
                                    $x = 1;
                                     while($x < $args_num-1){
                                         $result[$x-1][] = $data[$arguments[$x]];
                                         $x++;
                                      }
                                }
                            }
                            mysqli_close($this->link_open);
                             return $result;
                        }else{
                           mysqli_close($this->link_open);
                           return 0 ;
                        }
                  }else{
                       echo"<br> make sure your arguments once again<br>";
                       mysqli_close($this->link_open);
                       return 0 ;
                 }
          }else{
                 echo"<br>check number of arguments<br>";
                 return 0;
         }
  }
}  
?>
