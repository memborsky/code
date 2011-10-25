{include file=header.tpl}
<!-- START:	admin/clamav_main.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
                <a href='admin.php?extension=ClamAV&amp;action=show_scans'>{$_CLAM_SCANS}</a> ::
                {$_CLAM_SCHEDULE_SCAN} ::
                <a href='admin.php?extension=ClamAV&amp;action=show_settings'>{$_SETTINGS}</a>
            </div>
            <p />
            &nbsp;<p />
            <table width="100%">
                <form name='clamform' id='clamform' method='post' action='admin.php'>
                    <tr>
                        <!--
                            Code for Treeview functionality borrowed (and modified) from
                            http://www.scbr.com/docs/products/dhtmlxTree/index.shtml

                            This project, at the time of code borrow, was open
                            sourced under GNU GPL. An exerpt from the license
                            is below

                            - Open Source - GPL (standard edition only)
                            - Commercial License (standard and professional editions):
                                in order to use any edition of dhtmlxTree in a
                                commercial project, get all features available
                                in professional edition or to have support you
                                can purchase a Commercial License.
                                Contact us to buy.
                        -->
                        <td width='10%'></td>
                        <!-- tree area -->
                        <td width="25%" align='right' style='vertical-align: top;'>
                            <div id="treebox"
                                style=" width:250px;
                                    height:500px;
                                    background-color:#f5f5f5;
                                    border :1px solid Silver;
                                    text-align: left;
                                    overflow: auto;"/>
                            </div>
                        </td>

                        <!-- tree area -->
                        <td width="40%">
                            <div>
                                <input type='hidden' name='extension' value='ClamAV'>
                                <input type='hidden' id='fl_action' name='action' value='do_schedule_clamscan'>
                                <input type="hidden" value="0" id="theValue" />
                            </div>
                            <div id="filelist"
                                style=" width:600px;
                                    height:500px;
                                    background-color:#f5f5f5;
                                    border :1px solid Silver;
                                    overflow: auto;
                                    font-size: 12px;"/>
                                <table width='100%'>
                                    <tr>
                                        <td style='width: 10%; font-weight: bold;'>Remove</td>
                                        <td style='width: 90%; font-weight: bold;'>Filename</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td width='10%'></td>
                    </tr>
                    <tr>
                        <td width='10%'></td>
                        <td width='40%' colspan='2' align='center'>
                            <input type='submit' value='Scan' class='input_btn'>
                        </td>
                        <td width='10%'></td>
                    </tr>
                </form>
            </table>
        </td>
    </tr>
</table>
<!-- END:	admin/clamav_main.tpl -->
{include file=footer.tpl}
