{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'show_change_accounts':
                document.accounts.action.value="show_change_accounts";
                document.accounts.submit();
                return true;
            case 'do_delete_accounts':
                retval = confirm("Are you sure you want to delete the selected accounts?");

                if(retval) {
                    document.accounts.action.value="do_delete_accounts";
                    document.accounts.submit();
                } else {
                    return;
                }
                return true;
            case 'do_purge_accounts':
                retval = confirm("Are you sure you want to purge the selected accounts? This will remove all the account info for all services for the selected users!");

                if(retval) {
                    document.accounts.action.value="do_purge_accounts";
                    document.accounts.submit();
                } else {
                    return;
                }
                return true;
            case 'do_activate_accounts':
                document.accounts.action.value="do_activate_accounts";
                document.accounts.submit();
                return true;
            case 'do_deactivate_accounts':
                document.accounts.action.value="do_deactivate_accounts";
                document.accounts.submit();
                return true;
        }
    }
    function checkall() {
        count = document.accounts.elements.length;
            for (i=0; i < count; i++) {
                if(document.accounts.elements[i].checked == 1) {
                    document.accounts.elements[i].checked = 0;
                    document.accounts.check_uncheck.checked = 0;
                } else {
                    document.accounts.elements[i].checked = 1;
                    document.accounts.check_uncheck.checked = 1;
                }
            }
    }
</script>
{/literal}
