{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'do_update_permissions':
                document.filesform.action.value="do_update_permissions";
                document.filesform.submit();
                return true;
            case 'show_change_owner':
                document.filesform.action.value="show_change_owner";
                document.filesform.submit();
                return true;
        }
    }
    function checkall() {
        count = document.filesform.elements.length;
            for (i=0; i < count; i++) {
                /**
                * javascript uber-hack to only check and uncheck the boxes next
                * to the folders. If you change the elements name for 'item'
                * in the template file, you MUST change 'item' below and the
                * substring offsets for that new word!
                */
                var item_name = document.filesform.elements[i].name;
                if (item_name.substring(0,4) == "item") {
                    if(document.filesform.elements[i].checked == 1) {
                        document.filesform.elements[i].checked = 0;
                        document.filesform.check_uncheck.checked = 0;
                    } else {
                        document.filesform.elements[i].checked = 1;
                        document.filesform.check_uncheck.checked = 1;
                    }
                }
            }
    }
</script>
{/literal}
