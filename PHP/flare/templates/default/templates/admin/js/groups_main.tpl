{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'show_edit_group':
                document.groups.action.value="show_edit_group";
                document.groups.submit();
                return true;
            case 'do_delete_groups':
                retval = confirm("Are you sure you want to delete the selected groups?");

                if(retval) {
                    document.groups.action.value="do_delete_groups";
                    document.groups.submit();
                    return true;
                } else {
                    return;
                }
        }
    }

    function checkall() {
        count = document.groups.elements.length;
            for (i=0; i < count; i++) {
                if(document.groups.elements[i].checked == 1) {
                    document.groups.elements[i].checked = 0;
                    document.groups.check_uncheck.checked = 0;
                } else {
                    document.groups.elements[i].checked = 1;
                    document.groups.check_uncheck.checked = 1;
                }
            }
    }
</script>
{/literal}
