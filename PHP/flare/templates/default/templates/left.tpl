<!-- START: 	left.tpl -->
<tr>
<td width='20%' valign='top'>
    <table class='col_left_table' border="0">
        <tbody>
            <tr>
                <td class='two'>
                </td>
                <td class='two'>
                    {$username}<br>
                    <a href='index.php?extension=Authentication&amp;action=logout'>Logout</a>
                </td>
            </tr>
        </tbody>
    </table>
</td>
<td width='80%'></td>
</tr>
<tr>	
<td width='80%' valign='top'>
    <table class='col_left_table' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='left_header'>
                    {$_NAVIGATION}
                </td>
            </tr>
            <tr>
                <td class='left_content'>
                    <table width='100%'>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table class='col_left_table' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='left_header'>
                    {$_NEWS}
                </td>
            </tr>
            <tr>
                <td class='left_content' valign='top'>
                    <table width='100%'>
                    {section name=news loop=$news_date}
                    <!--<tr class='{cycle values="news_row_one,news_row_two"}'>-->
                    <tr>
                        <td>{$news_date[$smarty.section.news.index]}</td>
                        <td>{$news_content[$smarty.section.news.index]}</td>
                    </tr>
                    {sectionelse}
                    <tr>
                        <!--<td class='news_row_one'>{$_NO_NEWS}</td>-->
                        <td>{$_NO_NEWS}</td>
                    </tr>
                    {/section}
                    </table>
                </td>
            </tr>
        </tbody>
    </table>


    <table class='col_left_table' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='left_header'>
                    {$_INVITES}
                </td>
            </tr>
            <tr>
                <td class='left_content' valign='top'>
                    <table width='100%'>
                    {section name=news loop=$news_date}
                    <!--<tr class='{cycle values="invites_row_one,invites_row_two"}'>-->
                    <tr>
                        <td>{$news_date[$smarty.section.news.index]}</td>
                        <td>{$news_content[$smarty.section.news.index]}</td>
                    </tr>
                    {sectionelse}
                    <tr>
                        <!--<td class='invites_row_one'>{$_NO_INVITES}</td>-->
                        <td>{$_NO_INVITES}</td>
                    </tr>
                    {/section}
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</td>
<!-- END: 	left.tpl -->
