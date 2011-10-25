{ include file="header.tpl" }
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Settings&amp;action=show_settings'>{$_SETTINGS}</a> ::
            Install/Remove Extensions
        </div>
    <p />
    <form method ='post' action='admin.php' name='extensions'>
        <div>
            <input type='hidden' name='extension' value='Settings'>
            <input type='hidden' name='action' value='do_install_extension'>
            <input type='hidden' name='extension_name' value=''>
            
        </div>

        <div style='text-align: center; font-size: 10pt;'>Extensions with a <span style='color: red'>*</span> next to them are required. You will not be able to remove them.</div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='acct_main_item_header'>Required</td>
                <td class='acct_main_item_header'>Extension Name</td>
                <td class='acct_main_item_header'>Modify Extension</td>
            </tr>
            { section name=ext loop=$extensions }
            <tr style='background-color: {cycle values="#d0d0d0,#eee"}'>
                <td align='center' width='5%'>
                    { if $extensions[ext].req }
                    <span style='color: red; font-size: 12pt;'>*</span>
                    { /if }
                </td>
                <td width='55%'>
                    { $extensions[ext].name }
                </td>
                    <td align='center' width='20%'>
                    { if $extensions[ext].is_installed }
                        { if $extensions[ext].req }
                        <input type='button' value='remove' class='input_btn' disabled='disabled'>
                        { else }
                        <input type='button' value='remove' class='input_btn' onClick='actions("remove_extension","{$extensions[ext].name}")'>
                        { /if }
                    { else }
                    <input type='button' id='{ $extensions[ext].name }' value='install' class='input_btn' onClick='actions("install_extension","{$extensions[ext].name}")'>
                    { /if }
                </td align='center' width='20%'>
            </tr>
            { /section }
        </table>
    </form>
    </td>
</tr>
</table>

{include file="footer.tpl"}
