{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'show_edit_topic':
                document.help.action.value="show_edit_topic";
                document.help.submit();
                return true;
            case 'do_delete_topics':
                document.help.action.value="do_delete_topics";
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
</script>
{/literal}
