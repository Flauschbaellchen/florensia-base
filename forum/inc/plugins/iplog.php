<?php
/** This plugin is based on MrD.'s MyBB 1.2.x iplog plugin **/
 
// Disallow direct access to this file for security reasons
if(!defined('IN_MYBB'))
{
    die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

// admin hooks
$plugins->add_hook( 'admin_tools_menu_logs',      'iplog_tools' );
$plugins->add_hook( 'admin_tools_action_handler', 'iplog_action_handler' );

// logging hooks
$plugins->add_hook( 'member_do_login_end',              'iplog_exec' );
$plugins->add_hook( 'portal_do_login_end',              'iplog_exec' );
$plugins->add_hook( 'member_activate_accountactivated', 'iplog_exec' );

function iplog_info()
{
    return array(
        'name'          => 'User IP Log',
        'description'   => 'Logs a members IP every time they login.',
        'author'        => 'nobleclem',
        'authorsite'    => 'http://fatalexception.us',
        'version'       => '1.1',
        'website'       => 'http://mods.mybboard.net/',
        'guid'          => 'c3f49f23d9bef6611d0a140ee441d618',
        'compatibility' => '16*'
    );
}

function iplog_is_installed()
{
    global $db;

    if( $db->table_exists( 'iplogs' ) ) {
        return true;
    }

    return false;
}

function iplog_install()
{
    global $db;

    if( !$db->table_exists( 'iplogs' ) ) {
        $sql = "
        CREATE TABLE IF NOT EXISTS `". TABLE_PREFIX ."iplogs` (
          `uid` int(10) NOT NULL,
          `ipaddress` varchar(50) NOT NULL,
          `dateline` bigint(30) NOT NULL,
          KEY `uid` (`uid`),
          KEY `ipaddress` (`ipaddress`),
          KEY `dateline` (`dateline`)
        ) ENGINE=MyISAM;";

        $db->write_query( $sql );
    }
}

function iplog_uninstall()
{
    global $db;

    if( $db->table_exists( 'iplogs' ) ) {
        $db->write_query( "DROP TABLE ". TABLE_PREFIX ."iplogs" );
    }
}

function iplog_exec()
{
    global $user, $db;

    $values = array(
        'uid'       => $db->escape_string( $user['uid'] ),
        'ipaddress' => $db->escape_string( get_ip() ),
        'dateline'  => $db->escape_string( time() )
    );

    $db->insert_query( 'iplogs', $values );
}

function iplog_tools( $ref )
{
    $ref[] = array(
        'id'    => 'iplog',
        'title' => 'User IP Logs',
        'link'  => 'index.php?module=tools/iplog'
    );
}

function iplog_action_handler( $ref )
{
    global $lang;

    $tmp = array( '..', '..', '..', 'inc', 'plugins', 'iplog', 'admin.php' );
    $file = implode( DIRECTORY_SEPARATOR, $tmp );

    $ref['iplog'] = array(
        'active' => 'iplog',
        'file'   => $file
    );

    $tmp = array( dirname( __FILE__ ), 'iplog', 'languages', $lang->language . '.admin.php' );
    if( file_exists( implode( DIRECTORY_SEPARATOR, $tmp ) ) ) {
        include_once implode( DIRECTORY_SEPARATOR, $tmp );
    }
    else {
        array_pop( $tmp );
        $tmp[] = 'english.admin.php';

        include_once implode( DIRECTORY_SEPARATOR, $tmp );
    }

    if( isset( $l ) && is_array( $l ) ) {
        foreach( $l as $key => $val ) {
            $lang->$key = $val;
        }
    }
}
