{include file='header.tpl'}
<table class='main'>
    <tr>
        <td width="50%" valign="top">
          {$VITALS}
          <br>
          {$NETWORK}
        </td>

        <td width="50%" valign="top">
            {$HARDWARE}
        </td>
    </tr>

    <tr>
        <td colspan="2">
            {$MEMORY}
        </td>
    </tr>

    <tr>
        <td colspan="2">
            {$FILESYSTEMS}
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td width="67%" valign="top">
            {$MBTEMP}
            <br>
            {$MBFANS}
        </td>

        <td width="33%" valign="top">
            {$MBVOLTAGE}
        </td>
    </tr>
</table>
<!--$text['created'];

echo '<a href="http://phpsysinfo.sourceforge.net">&nbsp;phpSysInfo-' . $VERSION . '</a> ' . strftime ($text['gen_time'], time());-->
{include file='footer.tpl'}
