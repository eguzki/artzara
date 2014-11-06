<?php
// hello_world Component
/**
* Content code
* @package hello_world
* @Copyright (C) 2004 Doyle Lewis
* @ All rights reserved
* @ hello_world is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version 1.0
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_newsfeeds' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$task = mosGetParam( $_REQUEST, 'task', array(0) );

switch ($task) {

	case "publish":
		publishHelloWorld( $id, 1, $option );
		break;

	case "unpublish":
		publishHelloWorld( $id, 0, $option );
		break;

	case "new":
		editHelloWorld( 0, $option );
		break;
 
	case "edit":
		editHelloWorld( $id[0], $option );
		break;
 
	case "remove":
		removeHelloWorld( $id, $option );
		break;

	case "save":
		saveHelloWorld( $option );
		break;

	case "cancel":
		cancelHelloWorld( $option );
		break;

	default:
		showHelloWorld( $option );
		break;
}