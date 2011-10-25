{literal}
<script type='text/javascript'>
    function actions(action) {
        switch(action) {
            case 'show_action_zip':
                document.filesform.action.value="show_action_zip";
                document.filesform.submit();
                return true;
                break;
            case 'do_extract_archive':
                document.filesform.action.value="do_extract_archive";
                document.filesform.submit();
                return true;
                break;
            case 'do_action_new_dir':
                dir = prompt("Name of new folder");
                if (dir) {
                    document.filesform.action.value="do_action_new_dir";
                    document.filesform.dir.value=dir;
                    document.filesform.submit();
                    return true;
                }
                break;
            case 'do_action_delete':
                document.filesform.action.value="do_action_delete";
                document.filesform.submit();
                return true;
                break;
            case 'do_action_bookmark':
                document.filesform.action.value="do_action_bookmark";
                document.filesform.submit();
                return true;
                break;
            case 'show_action_archive':
                document.filesform.action.value="show_action_archive";
                document.filesform.submit();
                return true;
                break;
            case 'do_action_paste':
                document.filesform.action.value="do_action_paste";
                document.filesform.submit();
                return true;
                break;
            case 'do_action_cut':
                document.filesform.action.value="do_action_cut";
                document.filesform.submit();
                return true;
                break;
            case 'do_action_copy':
                document.filesform.action.value="do_action_copy";
                document.filesform.submit();
                return true;
                break;
            case 'do_action_rename':
                rename = prompt("Enter the new name for the item you selected");
                if (rename) {
                    document.filesform.action.value="do_action_rename";
                    document.filesform.rename.value=rename;
                    document.filesform.submit();
                    return true;
                }
                break;
            default:
                alert('The specified action is currently not supported in Flare.');
                return false;
                break;
        }
    }

    function checkbox_count() {
        var total = 0;
        var max = document.filesform.count.length;
        for (var idx = 0; idx < max; idx++) {
            if (eval("document.filesform.count[" + idx + "].checked") == true) {
                total += 1;
            }
        }

        return total;
    }

    function checkall() {
        count = document.filesform.elements.length;
            for (i=0; i < count; i++) {
                if(document.filesform.elements[i].checked == 1) {
                    document.filesform.elements[i].checked = 0;
                    document.filesform.check_uncheck.checked = 0;
                } else {
                    document.filesform.elements[i].checked = 1;
                    document.filesform.check_uncheck.checked = 1;
                }
            }
    }
</script>
{/literal}
