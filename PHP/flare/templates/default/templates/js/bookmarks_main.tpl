{literal}
<script type='text/javascript'>
    function checkall() {
        count = document.bookmarks.elements.length;
            for (i=0; i < count; i++) {
                if(document.bookmarks.elements[i].checked == 1) {
                    document.bookmarks.elements[i].checked = 0;
                    document.bookmarks.check_uncheck.checked = 0;
                } else {
                    document.bookmarks.elements[i].checked = 1;
                    document.bookmarks.check_uncheck.checked = 1;
                }
            }
    }
</script>
{/literal}
