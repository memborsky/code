{literal}
<script type='text/javascript'>
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
