<?php
	
	/**
	 * esse controlador tem o comportamento diferenciado por
	 * @author jean
	 *
	 */
	class ACKtextos_Controller extends System_Controller
	{
	    function index () 
	    {
			System_View::load("ack/textos/index");
		}
		
	    function privacidade () 
	    {
	    	System_View::load("ack/textos/privacidade");
		}
		
	    function termos () 
	    {
	    	System_View::load("ack/textos/termos");
		}
	}
?>