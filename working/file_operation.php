<?php


final class file_operation{
   /*
         object->read_line("file_name",line_number);
         object->read_line("file_name",6); 

         object->read_line("file_name",line_number,number_of_more_line_after_that);
         object->read_line("file_name",6,8);  --->>> it will read line number 6 and 8 more line after that as well
   */
    public function read_line(){
         $arguments = func_get_args();
         $num_args = func_num_args();
              if(!file_exists($arguments[0]) || !is_readable($arguments[0])){
                    return 0;
              }else{
                    $x = 0;
                    $result = 0;  // this change
                    $file_open = fopen("$arguments[0]","r");
                       flock($file_open,LOCK_SH);
                       if($num_args == 2){
                             if(!is_int($arguments[1])){
                                   flock($file_open,LOCK_UN);
                                   fclose($file_open);                                 
                                   return 0; 
                             }
                              while($content = fgets($file_open)){
                                    if(++$x == $arguments[1]){
                                         $result = rtrim($content);
                                         break;
                                     }
                               }
                        }else if($num_args == 3){
                              if(!is_int($arguments[1]) || !is_int($arguments[2])){
                                   flock($file_open,LOCK_UN);
                                   fclose($file_open);                                 
                                   return 0; 
                              }
                               $result = array();
                               while($content = fgets($file_open)){
                                     if(++$x == $arguments[1]){
                                         $line = $x;
                                         $result[] = rtrim($content);
                                         while($x < $line+$arguments[2] && $content = fgets($file_open)){
                                              $result[] = rtrim($content);
                                              $x++;
                                          }
                                          break;
                                      }
                               }
                        }
                      flock($file_open,LOCK_UN);
                      fclose($file_open);
                      return $result;
                }
        }
        /*
              $object->write_post("file_name","post_to_write","singled_line_additional_data")
              $object->write_post("file_name","$post","time","name"); -->> time and name is singled line addtional data more singled line data can be stored
        */
        public function write_post(){
                   $arguments = func_get_args();
                   $arg_nums = func_num_args();
                   $arguments[1] = rtrim($arguments[1]);
                   $post_array = explode("\n","$arguments[1]");
                   $num = count($post_array)+$arg_nums-2;
                   $data = $num;
                   if($arg_nums > 2){
                       for($loop = 2;$loop < $arg_nums;$loop++){
                           $data = $data."\n".$arguments[$loop];
                       }
                   }
                   $data = $data."\n".$arguments[1];
                   if($file_open = fopen("$arguments[0]","w")){
                        flock($file_open,LOCK_EX);
                             fwrite($file_open,$data);
                        flock($file_open,LOCK_UN);
                      fclose($file_open);
                      return 1;
                   }else{
                         return 0;
                   }
        }
          /*
              $object->read_post("file_name");
              $object->read_post("file_name"); -->> it return 1'D array where singled_line_additional_data is return first then comment
              like in previous write_post() time is indexed 0 in array and name is indexed 1 then remaining array is post
        */
        public function read_post($file_name){
               if(!file_exists($file_name) || !is_readable($file_name)){
                     return 0;
               }else{
                     $start = 0;
                     $result = array();
                     $file_open = fopen("$file_name","r");
                         flock($file_open,LOCK_SH);
                              $num = fgets($file_open);
                              while(++$start < $num+1 && $content = fgets($file_open)){
                                    $result[] = $content;
                              }
                         flock($file_open,LOCK_UN);
                     fclose($file_open);
                     return $result;
               }
        }
         /*
              $object->write_comment("file_name","comment","singled_line_additional_data");
              $object->write_comment("file_name","comment","time","name"); -->> as same as in write_post
        */
        public function write_comment(){
               $arguments = func_get_args();
               $arg_nums = func_num_args();
          
               if(!file_exists($arguments[0]) || !is_readable($arguments[0])){
                     return 0;
               }
               $arguments[1] = rtrim($arguments[1]);
               $comment_array = explode("\n","$arguments[1]");
               $num = count($comment_array);
               $data = $num+$arg_nums-2;
               if($arg_nums > 2){
                      for($loop = 2 ; $loop < $arg_nums ; $loop++){
                            $data = $data."\n".$arguments[$loop];
                      }
               }
               $data = "\n".$data."\n".$arguments[1];
               if($file_open = fopen("$arguments[0]","a")){
                      flock($file_open,LOCK_EX);
                           fwrite($file_open,$data);
                      flock($file_open,LOCK_UN);
                  fclose($file_open);
                  return 1;
              }else{
                      return 0;
              }
         }
          /*
              $object->read_comment("file_name");
              $object->read_comment("file_name"); -->> it return 2'D array where it contain arrays on comments and singled_line_additional_data is return first then comment
              like in previous write_comment() time is indexed 0 in array and name is indexed 1 then remaining array is post
        */
        public function read_comment($file_name){
               if(!file_exists($file_name) || !is_readable($file_name)){
                     return 0;
               }else{
                     $start = 0;
                     $result = array();
                     $file_open = fopen("$file_name","r");
                     flock($file_open,LOCK_SH);
                            $num = fgets($file_open);
                            while(++$start < $num+1 && $content = fgets($file_open)){ }
                            $num = fgets($file_open);
                            $index = 0;
                            do{
                                   $start = 0;
                                   while(++$start < $num+1 && $content = fgets($file_open)){
                                           $result[$index][] = $content;
                                    }
                                    $num = fgets($file_open);
                            }while($num && ++$index);
                    flock($file_open,LOCK_UN);
                    fclose($file_open);
                    return $result;
                 }
             }
             /*
                 $object->read_file("filename");  --->> it will read whole content of file in an array with each line as an array value
             */
            public function read_file($file_name){
                  if(!file_exists($file_name) || !is_readable($file_name)){
                        return 0;
                   }else{
                        $file_open = fopen("$file_name","r");
                            flock($file_open,LOCK_SH);
                                 $result = file("$file_name");
                            flock($file_open,LOCK_UN);
                        fclose($file_open);
                        foreach($result as $key=>$value){
                            $result[$key] = trim($value);                        
                        }
                        return $result;
                  }
            }
            /*
               $object->read_comment_file("filename");

               it will same as a read_comment() but if comment is not written in same file where post is written
            */
            public function read_comment_file($file_name){
               if(!file_exists($file_name) || !is_readable($file_name)){
                     return 0;
               }else{
                     $result = array();
                     $file_open = fopen("$file_name","r");
                     flock($file_open,LOCK_SH);
                            $num = fgets($file_open);
                            $index = 0;
                            do{
                                   $start = 0;
                                   while(++$start < $num+1 && $content = fgets($file_open)){
                                           $result[$index][] = trim($content);
                                    }
                                    $num = fgets($file_open);
                            }while($num && ++$index);
                    flock($file_open,LOCK_UN);
                    fclose($file_open);
                    return $result;
                 }
             }

            /*
             
            $object->list_add("filename","item"); --->>> adds item to list 

            multiple item can be added like 
            $object->list_add("filename","item","item 1","item 2"); 

              it add data to list
            */
             public function list_add(){

             	      $arg_nums = func_num_args();
             	      $arguments = func_get_args();
             	      $item = "";
                      for($loop = 1;$loop < $arg_nums;$loop++){
                      		  $item = $item.$arguments["$loop"]."\n";
                      }

                     $file_open = fopen("$arguments[0]","a");
                     if(!$file_open){
                     	 return 0;
                     }
                     flock($file_open,LOCK_EX);
                        fwrite($file_open,$item);
                     flock($file_open,LOCK_UN);
                     fclose($file_open);
                     return 1;
           }
           /*
              it replace item with another item

              $object->list_update("filename","item_in_list","new_item");  ---->> it will update all the item_in_list with new_item
           
           */
           public function list_update($file_name,$update_item,$new_item){
             	
             	  $list_item = [];
             	  $position = [];
             	  $data = "";
             	  $update_item = trim($update_item);
             	  $new_item = trim($new_item);
               
             	  if($position = $this->list_search($file_name,$update_item)){
             		   if($list_item = $this->read_file($file_name)){
             		   	
             		       foreach($position as $key){
                             $list_item[$key-1] = "$new_item";             		                      
             		       }
             		   }          
             	  }
             	  if(count($list_item)){
             	  	        foreach($list_item as $item){
                                $data = $data.$item."\n";   
             	  	        }
             	  	        $file_open = fopen("$file_name","w");
             	  	        if(!$file_open){
             	  	        	  return 0;
             	  	        }
             	  	        flock($file_open,LOCK_EX);
             	  	           fwrite($file_open,"$data");
             	  	        flock($file_open,LOCK_UN);
                          fclose($file_open);
                          return 1;
             	  }
             	  return 0;
             }
             /*
                   it search for item in list and return array contains line number of item in list

                   $object->list_search("filename","item_to_search"); 
             */

             public function list_search($file_name,$search_item){
             	      $list = [];
             	      $position = [];
                 
                      if($file_open = fopen($file_name,"r")){
                         $count = 1;
                            while($str = fgets($file_open)){
                                  if(trim($search_item) == trim($str)){
                                      $position[] = $count;
                                  }
                                   $count++;
                            }
                         fclose($file_open);
                      }else{
                            return  0;
                      }
             	      if(count($position)){
             	           return $position;
             	      }else{
                          return 0;
             	      }
             }

             /*
                  it will remove the item from the list

                  $object->list_remove($filename,"item_to_be_removed");
             
             */
              public function list_remove($file_name , $data){

                     $list = "";
                     $write = 0;
                     $data = trim($data);
             		
                     if($list_item = $this->read_file($file_name)){
                           foreach($list_item as $item){
                               if(trim($item) != $data){
                                    $list = $list.$item."\n";
                               }else{
                                    $write = 1;
                               }
                           }
                         if($write == 1){
                             $file_open = fopen("$file_name","w");
             	  	         if(!$file_open){
             	  	            return 0;
             	  	         }
             	  	          flock($file_open,LOCK_EX);
             	  	             fwrite($file_open,"$list");
             	  	          flock($file_open,LOCK_UN);
                             fclose($file_open);
                             return 1;
                          }else{
                             return 0;
                          }
                      }else{
                             return 0;
                      }
              }
              
              public function write_section(){
                   $arguments = func_get_args();
                   $arg_nums = func_num_args();
                   
                   $arguments[1] = rtrim($arguments[1]);
                   $post_array = explode("\n","$arguments[1]");
                   $num = count($post_array)+$arg_nums-2;
                   $data = $num;
                   if($arg_nums > 2){
                       for($loop = 2;$loop < $arg_nums;$loop++){
                           $data = $data."\n".$arguments[$loop];
                       }
                   }
                   if(file_exists($arguments[0])){
                        if(filesize($arguments[0])){
                   	           $data = "\n".$data."\n".$arguments[1]; 
                        }else{
                               $data = $data."\n".$arguments[1];
                        }
                   }else{
                          $data = $data."\n".$arguments[1];
                   }
                   if($file_open = fopen("$arguments[0]","a")){
                        flock($file_open,LOCK_EX);
                             fwrite($file_open,$data);
                        flock($file_open,LOCK_UN);
                      fclose($file_open);
                      return 1;
                   }else{
                         return 0;
                   }
              }
              public function read_section($file_name){
              	
              	    if($data = $this->read_comment_file($file_name)){
                           return $data;              	    
              	    }else{
                           return 0;
                    }
              
              }
}
?>
