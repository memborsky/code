{include file=header_empty.tpl}
<!-- START: admin/groups_member_list.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <h3>The following people are members of this group</h3>
        <ul>
        {section name=members loop=$MEMBERS}
            <li>{$MEMBERS[members].username}
        {/section}
        </ul>
        <p />
        <div style='text-align: center'><a href='#' onClick='javascript:window.close()'>Close</a></div>
    </td>
</tr>
</table>
<!-- ENd: admin/groups_member_list.tpl -->
{include file='footer.tpl}
