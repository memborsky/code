{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'show_change_mailing_list':
                document.email.action.value="show_change_mailing_list";
                document.email.submit();
                return true;
            case 'do_delete_mailing_list':
                retval = confirm("Are you sure you want to delete the selected mailing lists?");

                if(retval) {
                    document.email.action.value="do_delete_mailing_list";
                    document.email.submit();
                } else {
                    return;
                }
                return true;
        }
    }
    function checkall() {
        count = document.email.elements.length;
        for (i=0; i < count; i++) {
            if(document.email.elements[i].checked == 1) {
                document.email.elements[i].checked = 0;
                document.email.check_uncheck.checked = 0;
            } else {
                document.email.elements[i].checked = 1;
                document.email.check_uncheck.checked = 1;
            }
        }
    }
</script>
{/literal}
