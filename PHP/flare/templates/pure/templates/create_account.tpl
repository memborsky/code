<body id='create_account_body'>
    <span class='create_account_header'>{$_CREATE_ACCT_MSG_1}</span>
    <p>
    {$_CREATE_ACCT_MSG_2}
    <p>
    {$_CREATE_ACCT_MSG_3}
    <ul>
        <li>{$_CREATE_ACCT_SCHOOL_1}</li>
        <li>{$_CREATE_ACCT_SCHOOL_2}</li>
    </ul>
    {$_CREATE_ACCT_MSG_4}
    <p><hr width='40%'><p>
    <span class='create_account_header'>
    {$_CREATE_ACCT_MSG_5}
    </span>
    <br>
    <span class='create_account_subtitle'>&nbsp;&nbsp;&nbsp; {$_CREATE_ACCT_MSG_6}</span>

<form method='post' action='index.php' name='create'>
    <input type='hidden' name='extension' value='Accounts'>
    <input type='hidden' name='action' value='create_account'>
    <table class='create_account_personal_info' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='create_account_pi_col_1'>{$_NAME}</td>
                    <td class='create_account_pi_col_2'>
                    <input type='text' name='name'>
                    </td>
                <td></td>
            </tr>
            <tr>
                <td class='create_account_pi_col_1'>{$_STUDENT_ID}</td>
                    <td class='create_account_pi_col_2'>
                    <input type='text' name='student_id'>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class='create_account_pi_col_1'>{$_TECH_EMAIL}</td>
                <td class='create_account_pi_col_2'>
                    <input type='text' name='indtech_email'>
                </td>
                <td><span class='create_account_indtech'>{$_TECH_EXT}</td>
            </tr>
        </tbody>
    </table>
    <span class='create_account_header'>{$_FLARE_INFO}</span><br>
    <table class='create_account_flare_info' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='create_account_fi_col_1'>{$_CREATE_ACCT_REQ_USERNAME}</td>
                <td class='create_account_fi_col_2'>
                    <input type='text' name='username'>
                </td>
                <td class='create_account_fi_col_3'>{$_CREATE_ACCT_CHK_AVAIL}</td>
            </tr>
            <tr>
                <td class='create_account_fi_col_1'>{$_CREATE_ACCT_SEC_EMAIL}</td>
                <td class='create_account_fi_col_2'>
                    <input type='text'  name='secondary_email'>
                </td>
                <td class='create_account_fi_col_3'>{$_CREATE_ACCT_WHY}</td>
            </tr>
            <tr>
                <td class='create_account_fi_col_1'>{$_USR_PASSWORD}</td>
                <td class='create_account_fi_col_2'>
                    <input type='password' name='password'>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class='create_account_fi_col_1'>{$_VER_USR_PASSWORD}</td>
                <td class='create_account_fi_col_2'>
                    <input type='password' name='password_verify'>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p>
    <table class='create_account_checkbox_area' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='create_account_cb_col_1'>
                    <input type='checkbox' name='privacy_agreement'>
                </td>
                <td class='create_account_cb_col_2'>{$_CREATE_ACCT_MSG_7}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class='create_account_cb_col_1'>
                    <input type='checkbox' name'tos'>
                </td>
                <td class='create_account_cb_col_2'>
                    {$_CREATE_ACCT_MSG_8}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class='create_account_cb_col_1'>
                    <input type='checkbox' name='tost'>
                </td>
                <td class='create_account_cb_col_2'>
                    {$_CREATE_ACCT_MSG_9}
                </td>
            </tr>
        </tbody>
    </table>
    <p>
    <table class='create_account_collect' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td class='create_account_co_col_1'>{$_CREATE_ACCT_MSG_10}</td>
                <td class='create_account_co_col_2'></td>
            </tr>
            <tr>
                <td class='create_account_co_col_1'>{$_CREATE_ACCT_MSG_11}</td>
                <td class='create_account_co_col_2'></td>
            </tr>
            <tr>
                <td class='create_account_co_col_1'>{$_CREATE_ACCT_MSG_12}</td>
                <td class='create_account_co_col_2'></td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;"><br>
        {$_CREATE_ACCT_WHY_RECORD}<p />
    </div>
    <table class='create_account_buttons' border='0' cellpadding='2' cellspacing='2'>
        <tbody>
            <tr>
                <td style="width: 150px; text-align: center;">
                    <input type='submit' name='submit' value='Create Account'>
                </td>
                <td style="width: 149px; text-align: center;">
                    <input type='reset' name='reset' value='Erase Form'>
                </td>
            </tr>
        </tbody>
    </table>
    <p />
    We collect a secondary email address because we realize that some
    students may not use their Indiana Tech account fulltime. Note however
    that all system wide messages and news will be sent to your Indiana
    Tech email address. If you do not stay up to date with the emails, then
    we cannot be held responsible for any problems that may arise from us
    modifying the system or performing maintenance while you are logged in.
</form>
