# Air

This is php script for mysql database in php help to perfor most of php task 



creating object

$class_object = new data_base_query(server,user,password);

To insert data

$class_object->insert_value(database_name,table_name,field_1,field_2,value_1,value_2);
field could of any number.
return 1    on success
return 0    on failed

To retrive value

if only one field is given then one dimensional array will be return
if two or more field given then two dimensional array will be return

field could of any number.

return 1    on success
return 0    on failed


$class_object->return_value(database_name,table_name,field_1,field_2);

retrive value with single match
$class_object->return_match_value(database_name,table_name,field_1,match_field,match_value);

retrive value with double value
$class_object->return_d_match_value(database_name,table_name,field_1,match_field_1,match_value,match_field_2,match_value);

To Update value

field could of any number.

return 1    on success
return 0    on failed

update value with single match
$class_object->update_value(database_name,table_name,field_1,new_value,match_field_,match_value);

update value with two matches
$class_object->d_update_value(database_name,table_name,field_1,new_value,match_field,match_value,match_field,match_value);


