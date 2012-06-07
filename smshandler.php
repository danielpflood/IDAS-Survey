<?php

require_once 'tropo.class.php';
require_once 'func.php';
$tropo = new Tropo();
$session = new Session();
$initialText = $session->getInitialText();
if($initialText){
	$from = $session->getFrom();
	$callerID = $from["id"];
	if(isset($callerID)){
		$state = getState($callerID);
		if($state=="confirming"){
			if($initialText == "Yes"){
				confirmUser($callerID);
				$tropo->say("Awesome, you have been confirmed.");
			}
			elseif($initialText == "No"){
				setState($callerID,"cancelled");
			    $tropo->say("Well that's just too bad. Your account has been cancelled.");
			}
			else{
				$tropo->say("That wasn't an option, sorry.");
			}
		}
		elseif($state=="cancelled"){
			$tropo->say("Your account has been cancelled, you can not interact with the application any longer.");
		}
	}
	return $tropo->RenderJson();
}
else{
	log("in else");
	$to = "+".$session->getParameters("to");
	log("got to");
	$msg = $session->getParameters("msg");
	log("got msg");
	$tropo = new Tropo();
	log("instantiated tropo");
	$tropo->call($to, array('network'=>'SMS'));
	log("made call");
	$tropo->say($msg);
	log("sent msg");
	return $tropo->RenderJson(); 
}

?> 