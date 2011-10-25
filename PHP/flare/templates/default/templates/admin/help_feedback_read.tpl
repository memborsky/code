{include file='header_empty.tpl'}
<!-- START: 	help_feedback_read.tpl -->
<table class='main'>
<tr>
    <td class='myfiles_list_main'>
        <div class='myfiles_section_header'>
            <h3>Feedback</h3>
        </div>
        <p />
        <div style='font-size: 10pt;'>
            <div>
                <span style='font-weight: bold;'>From {$EMAIL} on {$DATE}</span>
            </div>
            <div>
                <span style='font-weight: bold'>Short title:</span> {$SHORT_DESC}
            </div>
            <div style='float: left; clear: left;'>
                <span style='font-weight: bold;'>Feedback:</span>
            </div>
            <div style='float: right; clear: right;'>
                {$CONTENT}
            </div>
        </div>
        <div style='padding-top: 20px; text-align: center; float: center; clear: left; clear: right;'>
            <a href='#' onClick='javascript:window.close();'>Close Window</a>
        </div>
    </td>
</tr>
</table>
<!-- END:	help_feedback_read.tpl -->
