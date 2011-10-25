{include file='header.tpl'}
<!-- START:     admin/reports/denied_hosts.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
            </div>
            <p />
            <table align='center' style='background-color: #ccc; width: 100%;'>
                <tr>
                    <td style='font-weight: bold;'>
                        IP Address
                    </td>
                    <td style='font-weight: bold;'>
                        Hostname Guess
                    </td>
                    <td style='font-weight: bold;'>
                        Services that are Denied
                    </td>
                </tr>
                {section name=den loop=$DENIED}
                <tr style='background-color: #fff;'>
                    <td style='width: 33%;'>
                        {$DENIED[den].ip}
                    </td>
                    <td style='width: 33%;'>
                        {$DENIED[den].host}
                    </td>
                    <td style='width: 33%;'>
                        {$DENIED[den].services}
                    </td>
                </tr>
                { sectionelse }
                    <tr style='background-color: #fff;'><td colspan='3'>
                    No hosts are currently being denied
                    </td></tr>
                {/section}
            </table>
        </td>
    </tr>
</table>
<!-- END:       admin/reports/denied_hosts.tpl -->
{include file='footer.tpl'}
