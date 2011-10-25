<!-- START: header.tpl -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Flare</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="copyright" content="&copy; 2004 Flare Project Team">
        <meta name="Author" content="Flare Project Team">
        <link rel='stylesheet' type='text/css' href='templates/{$_TEMPLATE}/templates/styles.css'>
        {section name=css_inc loop=$CSS_INC}
            <link rel='stylesheet' type='text/css' href='{$CSS_INC[css_inc].css}'>
        {sectionelse}
            <!-- No extra css inc'd -->
        {/section}

        <link rel="shortcut icon" href="images/favicon.ico">

        {if $JS_INC}
            {include file=js/$JS_INC}
        {else}
            <!-- No js file inc'd -->
        {/if}
    </head>
    <body>
        <table class='main_page'>
            <tr>
                <td>
                    <table class='table_outer_two'>
                        <tr>
                            <td style='padding: 20px 20px 20px 20px;'>
                    <table class='nav_area'>
                        <tr>
                            <td class='user_info_area'>
                                Logged in as :: {$_USERNAME}
                                {if $_IS_ADMIN}
                                    :: access <a href='admin.php'>ADMIN</a>
                                {/if}
                                <br>
                                <a href='index.php?extension=Authentication&amp;action=logout'>Logout</a>
                            </td>
                            <td class='links_area'>
                                {$EXTENSION_NAVIGATION}
                            </td>
                        </tr>
                    </table>
                    <p />
<!-- END: header.tpl -->
