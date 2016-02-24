<?php

// create a message queue
// $msg_queue = msg_get_queue("123456");


// send a message to the queue
// msg_send($msg_queue, 1, "test1");
// msg_send($msg_queue, 10, "test10");


// check the status of the message queue
// print_r(msg_stat_queue($msg_queue));


// recieve a message from the 1 queue (should be test1)
// msg_receive($msg_queue, 1, $msg_type, 1024, $msg);
// var_dump($msg_type);
// var_dump($msg);
// echo "\n";

// recieve a message from the 10 queue (should be test10)
// msg_receive($msg_queue, 10, $msg_type, 1024, $msg);
// var_dump($msg_type);
// var_dump($msg);
// echo "\n";


// remove a message queue
// msg_remove_queue($msg_queue);

print "Read all the commented out code for all the message queue examples.\n";

?>
