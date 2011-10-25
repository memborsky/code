{include file=header.tpl}
<!-- START: 	admin/email_mailing_list_add.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Email&amp;action=show_write_email'>Send New Email</a> ::
            <a href='admin.php?extension=Email&amp;action=show_mailing_lists'>Mailing Lists</a> ::
            Create Mailing List ::
            <a href='admin.php?extension=Email&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
        <p />
        <form method='post' action='admin.php' name='help'>
        <div>
            <input type='hidden' name='extension' value='Email'>
            <input type='hidden' name='action' value='do_create_mailing_list'>
        </div>
        <table style='width: 70%;' align='center'>
            <tr>
                <td>List Name</td>
                <td>
                    <input type='text' name='list_name' size='60' class='input_txt'/>
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>
                    List Members
                </td>
                <td>
                    <select name='list_members[]' multiple='multiple' style='width: 430px;' size='10' class='input_txt'>
                        {section name=usr loop=$USERS}
                            <option value='{$USERS[usr].id}' />{$USERS[usr].username}
                        {/section}
                    </select>
                </td>
            </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: right; width: 50%;'>
                    <input type='submit' name='submit' value='Create Mailing List' class='input_btn'>
                </td>
                <td style='text-align: left; width: 50%;'></td>
            </tr>
        </table>
    </td>
</tr>
</table>
<!-- END: 	admin/email_mailing_list_add.tpl -->
{include file=footer.tpl}
