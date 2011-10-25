{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'my_admin_delete':
                retval = confirm("Are you sure you want to delete the selected groups?");

                if (retval) {
                    document.my_admin_group_actions.action.value="my_admin_delete";
                    document.my_admin_group_actions.submit();
                } else {
                    return;
                }
                return true;
            case 'my_admin_edit':
                document.my_admin_group_actions.action.value="my_admin_edit";
                document.my_admin_group_actions.submit();
                return true;
            case 'my_groups_in_withdraw':
                retval = confirm("Are you sure you want to leave the selected groups?");

                if (retval) {
                    document.my_groups_in.action.value="my_groups_in_withdraw";
                    document.my_groups_in.submit();
                } else {
                    return;
                }
                return true;
            case 'accept_invite':
                document.invites_mine.action.value="accept_invite";
                document.invites_mine.submit();
                return true;
            case 'decline_invite':
                document.invites_mine.action.value="decline_invite";
                document.invites_mine.submit();
                return true;
            case 'retract_invite':
                document.invites_sent.action.value="retract_invite";
                document.invites_sent.submit();
                return true;
            case 'deny_request':
                document.requests_received.action.value="deny_request";
                document.requests_received.submit();
                return true;
            case 'allow_request':
                document.requests_received.action.value="allow_request";
                document.requests_received.submit();
                return true;
            case 'retract_request':
                document.requests_sent.action.value="retract_request";
                document.requests_sent.submit();
                return true;
        }
    }
</script>
{/literal}
