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
                                         $result = $content;
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
                                         $result[] = $content;
                                         while($x < $line+$arguments[2] && $content = fgets($file_open)){
                                              $result[] = $content;
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
                   $num = count($post_array)+$arg_num-2;
                   $data = $num;
                   if($arg_nums > 2){
                       for($loop = 2;$loop < $arg_nums;$loop++){
                           $data = $data."\n".$arguments[$loop];
                       }
                   }
                   $data = $data."\n".$post;
                   if($file_open = fopen("$arguments[0]","w")){
                        flock($file_open,LOCK_SH);
                             fwrite($file_open,$data);
                        flock($file_open,LOCK_UN);
                      fclose($file_open);
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
                                    $result[] = $content;
                              }
                         flock($file_open,LOCK_UN);
                     fclose($file_open);
                     return $result;
               }
        }
      public function write_comment($file_name,$identity,$comment){
               if(!file_exists($file_name) || !is_readable($file_name)){
                     return 0;
               }
               $date = date("h")." : ".date("i").date("A")."  ".date("j")."-".date("M")."-".date("Y");
               $comment_array = explode("\n","trim($comment)");
               $num = count($comment_array)+2;
               $data = "\n".$num."\n".$identity."\n".$date."\n".$comment;
               if($file_open = fopen("$file_name","a")){
                      flock($file_open,LOCK_SH);
                           fwrite($file_open,$data);
                      flock($file_open,LOCK_UN);
                  fclose($file_open);
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
                                           $result[$index][] = $content;
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
                                           $result[$index][] = $content;
                                    }
                                    $num = fgets($file_open);
                            }while($num && ++$index);
                    flock($file_open,LOCK_UN);
                    fclose($file_open);
                    return $result;
                 }
             }

}
?>
