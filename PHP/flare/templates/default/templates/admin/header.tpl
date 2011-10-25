<!-- START: admin/header.tpl -->
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

        <!-- Added to make Xinha extension work -->
        <script type="text/javascript">
            _editor_url  = "extensions/Xinha/"  // (preferably absolute) URL (including trailing slash) where Xinha is installed
            _editor_lang = "en";      // And the language we need to use in the editor.
        </script>
        <script type="text/javascript" src="extensions/Xinha/htmlarea.js"></script>
        <!-- End of code to make Xinha extension work -->

        <!-- Includes general javascript that can be displayed on all pages -->
        <script type="text/javascript" src="templates/{$_TEMPLATE}/templates/admin/js/general.tpl"></script>

        {if $JS_INC}
            {include file=js/$JS_INC}
        {else}
            <!-- No js file inc'd -->
        {/if}
    </head>
    {if $BODY_ON_LOAD}
    <body onLoad="{$BODY_ON_LOAD}">
    {else}
    <body>
    {/if}
        <table class='main_page'>
            <tr>
                <td>
                    <table class='table_outer_two'>
                        <tr>
                            <td style='padding: 20px 20px 20px 20px;'>
                                <table class='nav_area'>
                                    <tr>
                                        <td class='user_info_area'>
                                            Logged in as :: {$_USERNAME} :: <a href='admin.php'>ADMIN</a><br>
                                            <a href='index.php?extension=Authentication&amp;action=logout'>Logout</a>
                                        </td>
                                        <td class='links_area'>
                                            {$EXTENSION_NAVIGATION}
                                        </td>
                                    </tr>
                                </table>
                            <p />
                    { if $ADMIN_CONTROLS }
                    <table class='table_outer_three'>
                        <tr>
                        {section name=admin loop=$ADMIN_CONTROLS start=1}
                                <td style='text-align: center; width: 20%;'>
                                <a href='admin.php?extension={$ADMIN_CONTROLS[admin].name}'><img src='{$ADMIN_CONTROLS[admin].image}' alt='{$ADMIN_CONTROLS[admin].admin_displayed_name}' title='{$ADMIN_CONTROLS[admin].admin_displayed_name}'></a><br />{$ADMIN_CONTROLS[admin].admin_displayed_name}</td>
                                {if $smarty.section.admin.index % 5 == 0}
                                    </tr><tr>
                                {/if}
                        {/section}
                        </tr>
                    </table>
                    { /if }
                    <p />
<!-- END: admin/header.tpl -->
