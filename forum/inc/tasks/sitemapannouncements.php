<?php

/**
 * Copyright © 2008 mavericck
 *
 * This file is part of Sitemap Generator.
 *
 * Sitemap Generator is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sitemap Generator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Sitemap Generator.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

function task_sitemapannouncements($task)
{
    global $mybb, $db, $lang;

    require_once(MYBB_ADMIN_DIR . 'inc/functions_sitemap_generator.php');
    $lang->load('forum_sitemap_generator');

    $success = sg_make_sitemap('announcements', sg_fetch_announcements(), $mybb->settings['sg_announcements_threshold'], true);
    if ($success)
    {
        add_task_log($task, $lang->task_sitemapannouncements_ran);
    }
}
?>