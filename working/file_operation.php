<?php

namespace Air/file_operation;

final class file_operation{

    public function read_line(){
         $arguments = func_get_args();
         $num_args = func_num_args();
              if(!file_exists($arguments[0]) || !is_readable($arguments[0])){
                    return 0;
              }else{
                    $x = 0;
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
                                         $result = trim($content);
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
                                         $result[] = trim($content);
                                         while($x < $line+$arguments[2] && $content = fgets($file_open)){
                                              $result[] = trim($content);
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

        public function write_post(){
                   $arguments = func_get_args();
                   $arg_nums = func_num_args();
            
                   $post_array = explode("\n","trim($arguments[1])");
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
         
      public  function read_post($file_name){
               if(!file_exists($file_name) || !is_readable($file_name)){
                     return 0;
               }else{
                     $start = 0;
                     $result = array();
                     $file_open = fopen("$file_name","r");
                         flock($file_open,LOCK_SH);
                              $num = fgets($file_open);
                              while(++$start < $num+1 && $content = fgets($file_open)){
                                    $result[] = trim($content);
                              }
                         flock($file_open,LOCK_UN);
                     fclose($file_open);
                     return $result;
               }
        }
        public function write_comment(){
               $arguments = func_get_args();
               $arg_nums = func_num_args();
          
               if(!file_exists($arguments[0]) || !is_readable($arguments[0])){
                     return 0;
               }
          
               $comment_array = explode("\n","trim($arguments[1])");
               $num = count($comment_array);
               $data = "\n".$num+$arg_nums-2;
               if($arg_nums > 2){
                      for($loop = 2 ; $loop < $arg_nums ; $loop++){
                            $data = $data."\n".$arguments[$loop];
                      }
               }
               $data = $data."\n".$arguments[1];
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
                                           $result[$index][] = trim($content);
                                    }
                                    $num = fgets($file_open);
                            }while($num && ++$index);
                    flock($file_open,LOCK_UN);
                    fclose($file_open);
                    return $result;
                 }
             }
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
    
             public function list_search($file_name,$search_item){
             	      $list = [];
             	      $position = 0;
                 
             	      $file_open = fopen("$file_name","r");
             	      if(!$file_open){
             	      	 return 0;
             	      }
             	      flock($file_open,LOCK_SH);
             	         while(++$position && $item = fgets($file_open)){
             	         	  if(trim($item) == $search_item){
             	      	    	   $list[] = $position;
             	      	     }
             	         }
             	      flock($file_open,LOCK_UN);
                     fclose($file_open);
                     
             	      if(count($list)){ 
             	           return $list;
             	      }else{
                          return 0;             	      
             	      }
             }
              public function write_section(){
                   $arguments = func_get_args();
                   $arg_nums = func_num_args();

                   $post_array = explode("\n","trim($arguments[1])");
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
              	    }
              
              }

}
?>
