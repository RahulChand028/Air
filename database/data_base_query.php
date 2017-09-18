<?php


  class runner {

    private $link_open;
    private $db = "igag";
    private $domain = "localhost";
    private $admin = "root";
    private $password = "";



        function db_conn(){

            $this->link_open = mysqli_connect($this->domain,$this->admin,"");
            if(!$this->link_open){
                   //mysqli_connect_error();
                   return 0;
             }
            if(!mysqli_select_db($this->link_open,$this->db)){
                   mysqli_close($this->link_open);
                   return 0;
             }
             return 1;
       }

      function run() {

          if(!self::db_conn()){
               return -1;
          }

          $query_parts = explode(" ",$this->query);

          if($query_parts[0] == "SELECT") {

                     $from_key = array_search("FROM",$query_parts);

                     if($runquery = mysqli_query($this->link_open,$this->query)) {

                        if(mysqli_num_rows($runquery) >= 1) {
                           
                           $result = array();
                           
                           while($data = mysqli_fetch_array($runquery)) {

                                if($from_key == 2) {

                                     $result[] =  $data[$query_parts[1]];
                                 
                                 } else if($from_key > 2){
                                     
                                     $x = 1;
                                     
                                     $index = 0;
                                     
                                     while($x < $from_key){

                                         $result[$index][] = $data[$query_parts[$x]];
                                     
                                         $x = $x+2;
                                     
                                         $index++;
                                     }
                                 }
                            }
                            mysqli_close($this->link_open);
                            return $result;
                     } else {

                            mysqli_close($this->link_open);
                            
                            return 0 ;
                     }
                 } else {
                   
                     return -1;
               }

            

          } else if ($this->grand_child == "DROP_TABLE" || $this->grand_child == "ALTER_TABLE") {
 
                 if(mysqli_query($this->link_open,$Q)) {

                        mysqli_close($this->link_open);

                        return 1;
                  } else {
                        
                        mysqli_close($this->link_open);

                        return 0;
                  }
  
          } else {

                    if($runquery = mysqli_query($this->link_open,$this->query)) {

                         if($result = mysqli_affected_rows($this->link_open)) {

                               mysqli_close($this->link_open);

                               return $result;

                         } else {
                             
                               mysqli_close($this->link_open);

                               return -1;
                        }
                    } else {

                            mysqli_close($this->link_open);

                            return -1;
                    }
          }
      }

  }
  class where_class extends runner {

      public $query;

      function __construct($para , $grand_child = "") {

           $this->query = $para;

           $this->grand_child = $grand_child;

      } 

      function where($para) {

          $this->query = $this->query." WHERE ".$para;

          return $this;

      }

      function order_by($order_by) {

          $this->query = $this->query." ORDER BY ".$order_by;

          return $this;
          
      }

      function group_by($group_by) {

          $this->query = $this->query." GROUP BY ".$group_by;

          return $this;

      }

     function having($having) {

          $this->query = $this->query." HAVING ".$having;

          return $this;

      }
  }



  class from_table {

      var $query;

      var $child;

      function __construct($para,$child = "") {

          $this->query = $para;

          $this->child = $child;
          
      }

      function from() {

           $this->query = $this->query." FROM ";

           $table = "";

           $params = func_get_args();

           foreach($params as $arg) {

                if($table == "") {
 
                       $table = $arg;

                } else {

                      $table = $table." , ".$arg;

                }

           }

           $this->query = $this->query.$table;
 
           return new where_class($this->query,$this->child);       
           
      }

      function values(){

          $this->query = $this->query." VALUES (";

          $values = "";

          $params = func_get_args();

           foreach($params as $arg) {

                if($values == "") {
 
                       $values = "'".$arg."'";

                } else {

                      $values = $values." , '".$arg."'";

                }

           }

           $this->query = $this->query.$values." )";

           return new where_class($this->query);

      }

      function set(){

          $changes = "";

          $params = func_get_args();

          $this->query = $this->query." SET ";

          foreach($params as $change) {
              
              if($changes == "") {

                 $changes = $changes.$change;
              } else {

                  $changes = $changes." , ".$change;
              }

          }

          $this->query = $this->query.$changes;
          
          return new where_class($this->query);

      }

      function modify($new_modified) {

         $this->query = $this->query." MODIFY ".$new_modified;

         return new where_class($this->query);

      }

      function add($new_added) {

         $this->query = $this->query." ADD ".$new_added;

         return new where_class($this->query);
      }

      function drop($prev_droped) {

          $this->query = $this->query." DROP ".$prev_droped;

          return new where_class($this->query);
      }

      function change($new_change) {

          $this->query = $this->query." CHANGE ".$new_change;

          return new where_class($this->query);
      }

      function rename_to($new_name) {

          $this->query = $this->query." RENAME TO ".$prev_droped;

          return new where_class($this->query);

      }

  }



  class DB {

      function select() {

            $params = func_get_args();

            $query = "SELECT ";

            foreach($params as $arg) {
              
                if($query == "SELECT ") {

                     $query = $query.$arg;

                } else {

                    $query = $query." , ".$arg;
                }
                
            }
          
            return  new from_table($query,"SELECT");
      }

      function insert_into(){

            $params = func_get_args();

            $query = "INSERT INTO ".$params[0]." (";

            $x = $query;

            array_shift($params);
        

            foreach($params as $arg) {
              
                if($query == $x) {

                     $query = $query.$arg;

                } else {

                    $query = $query.",".$arg;
                }
                
            }

            $query = $query." )";
          
            return  new from_table($query,"INSERT");

      }

      function update($table) {

          $query = "UPDATE ".$table;
           
          return new from_table($query,"UPDATE");
      }

      function alter_table($table){

          $query = "ALTER TABLE ".$table;

          return new from_table($query,"ALTER_TABLE");

      }

      function drop_table($table) {

          $query = 'DROP TABLE '.$table;

          return new where_class($query,"DROP_TABLE");

      }

      function delete_from($table) {

          $query = "DELETE FROM ".$table;

          return new where_class($query,"DELETE");

      }

  }




  ?>
