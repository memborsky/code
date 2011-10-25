{include file='header.tpl}
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align:top;'>
<div>
    <h2>All options</h2>
    <form name="form" action="options.php" method="post">
        <input type="hidden" name="action" value="update" />
        <table width="100%">

{section name=opn loop=$OPTIONS}

        <tr>
            <th scope='row'><label for='{$OPTIONS[opn].name}'>{$OPTIONS[opn].name}</label></th>
            <td><input type='text' name='option[{$OPTIONS[opn].name}]' size='30' value='{$OPTIONS[opn].value}' class='input_txt'/></td>
            <td>{$OPTIONS[opn].desc}</td>
        </tr>
{/section}

        </table>
        <input type="submit" name="Update" value="Update Settings &raquo;" class='input_btn'/>
    </form>
</div>
    </td>
</tr>
</table>
{include file=footer.tpl}
