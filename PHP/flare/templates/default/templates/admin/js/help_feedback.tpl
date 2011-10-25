{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'do_delete_feedback':
                document.help.action.value="do_delete_feedback";
                document.help.submit();
                return true;
            case 'show_reply_feedback':
                document.help.action.value="show_reply_feedback";
                document.help.submit();
                return true;
            case 'do_mark_read':
                document.help.action.value="do_mark_read";
                document.help.submit();
                return true;
            case 'do_mark_unread':
                document.help.action.value="do_mark_unread";
                document.help.submit();
                return true;
        }
    }
    function checkall() {
        count = document.help.elements.length;
            for (i=0; i < count; i++) {
                if(document.help.elements[i].checked == 1) {
                    document.help.elements[i].checked = 0;
                    document.help.check_uncheck.checked = 0;
                } else {
                    document.help.elements[i].checked = 1;
                    document.help.check_uncheck.checked = 1;
                }
            }
    }
    function read_feedback(item) {
        window.open("admin.php?extension=Help&action=do_read_feedback&feedback_id="+item, "popup","width=800,height=400,resizable=yes,scrollbars=yes");
    }
</script>
{/literal}
