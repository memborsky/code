{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'show_change_messages':
                document.messages.action.value="show_change_messages";
                document.messages.submit();
                return true;
            case 'do_delete_messages':
                document.messages.action.value="do_delete_messages";
                document.messages.submit();
                return true;
        }
    }
    function checkall() {
        count = document.messages.elements.length;
            for (i=0; i < count; i++) {
                if(document.messages.elements[i].checked == 1) {
                    document.messages.elements[i].checked = 0;
                    document.messages.check_uncheck.checked = 0;
                } else {
                    document.messages.elements[i].checked = 1;
                    document.messages.check_uncheck.checked = 1;
                }
            }
    }
</script>
{/literal}
