             public function validate_url($url){
                 if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)){
                      return 1;
                 }else{
                      return 0;
                 }
            }
