<?php

$page->add_breadcrumb_item($lang->iplog_title, "index.php?module=tools/iplog");
$page->output_header( $lang->iplog_title );

// setup tab
$sub_tabs['iplogs'] = array(
    'title'       => $lang->iplog_title,
    'link'        => 'index.php?module=tools/iplog',
    'description' => $lang->iplog_description
);

$page->output_nav_tabs( $sub_tabs, 'iplogs' );

// get page we are on and perpage count
$pagecnt = intval( $mybb->input['page'] ) ? intval( $mybb->input['page'] ) : 1;
$perpage = intval( $mybb->input['perpage'] ) ? intval( $mybb->input['perpage'] ) : $mybb->settings['threadsperpage'];

$where = array();
$where[] = '1=1';

// username filter
if( $mybb->input['username'] ) {
    $where[] = "u.username LIKE '%" . $db->escape_string( $mybb->input['username'] ) . "%'";
}

// ip filter
if( $mybb->input['ipaddress'] ) {
    $where[] = "l.ipaddress = '" . $db->escape_string( $mybb->input['ipaddress'] ) . "'";
}

// default order
$orderBy  = 'l.dateline';
$orderDir = 'DESC';

// check for custom order
if( in_array( $mybb->input['orderby'], array( 'u.username', 'l.dateline', 'l.ipaddress' ) ) ) {
    $orderBy = $mybb->input['orderby'];
}
if( in_array( $mybb->input['orderdir'], array( 'ASC', 'DESC' ) ) ) {
    $orderDir = $mybb->input['orderdir'];
}

// build and execute query
$sql = "
SELECT l.uid,
       u.username, u.usergroup, u.displaygroup,
       l.dateline,
       l.ipaddress

 FROM ". TABLE_PREFIX ."iplogs l

 LEFT JOIN ". TABLE_PREFIX ."users u
   ON u.uid = l.uid

 WHERE ". implode( ' AND ', $where ) ."

 ORDER BY {$orderBy} {$orderDir}";
$res = $db->query( $sql );

// get total number of results
$total = $db->num_rows( $res );

// seek to start
$db->data_seek( $res, ($pagecnt - 1) * $perpage );

// display results in a pretty table
$table = new Table;
$table->construct_header( $lang->iplog_username );
$table->construct_header( $lang->iplog_datetime );
$table->construct_header( $lang->iplog_ipaddress );

for( $i = 0; $i < $perpage; $i++ ) {
    if( $row = $db->fetch_array( $res ) ) {
        // format date/time
        $row['dateline'] = date( 'jS M Y, G:i', $row['dateline'] );

        // format username
        $row['username'] = format_name(
            $row['username'], $row['usergroup'], $row['displaygroup']
        );
        $row['username'] = build_profile_link(
            $row['username'], $row['uid']
        );

        if( !$row['username'] ) {
            $row['username'] = $lang->iplog_guest;
        }

        $table->construct_cell( $row['username']  );
        $table->construct_cell( $row['dateline'] );
        $table->construct_cell( $row['ipaddress'] );
        $table->construct_row();
    }
}

// display no logs error if no logs found
if( $table->num_rows() == 0 ) {
    $table->construct_cell( $lang->iplog_nologs, array( 'colspan' => 3 ) );
    $table->construct_row();
}

// display table
$table->output( $lang->iplog_title );

// setup pagination links if we have more to display
if( $total > $perpage ) {
    echo draw_admin_pagination(
        $pagecnt,
        $perpage,
        $total,
        "index.php?module=tools/iplog&amp;perpage={$perpage}&amp;username={$mybb->input['username']}&amp;ipaddress={$mybb->input['ipaddress']}&amp;orderby={$mybb->input['orderby']}&amp;orderdir={$mybb->input['orderdir']}"
    ) . "<br />";
}

// setup form
$order_by = array(
    'u.username'  => $lang->iplog_username,
    'l.dateline'  => $lang->iplog_datetime,
    'l.ipaddress' => $lang->iplog_ipaddress
);
$order_dir = array(
    'ASC'  => $lang->iplog_ascending,
    'DESC' => $lang->iplog_descending
);
$form = new Form( 'index.php?module=tools/iplog', 'post' );

// build form
$form_container = new FormContainer( $lang->iplog_filterlogs );
$form_container->output_row(
    $lang->iplog_username, '',
    $form->generate_text_box( 'username', $uid, array( 'id' => 'username' ) ),
    'username'
);
$form_container->output_row(
    $lang->iplog_ipaddress, '',
    $form->generate_text_box( 'ipaddress', $uid, array( 'id' => 'ipaddress' ) ),
    'ipaddress'
);
$form_container->output_row(
    'Order By', '',
    $form->generate_select_box(
        'orderby', $order_by, $orderBy, array( 'id' => 'orderby' )
    ) . " {$lang->iplog_in} " .
    $form->generate_select_box(
        'orderdir', $order_dir, $orderDir, array( 'id' => 'orderdir' )
    ) . " {$lang->iplog_order}",
    'order'
);

$form_container->end();

$buttons[] = $form->generate_submit_button( $lang->iplog_filterlogs );
$form->output_submit_wrapper( $buttons );
$form->end();

$page->output_footer();
