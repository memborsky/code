{literal}
<script type='text/javascript'>
    function check_availability() {
        name=document.groups.group_name.value
        window.open("admin.php?extension=Groups&action=do_check_for_existing_group&group_name="+name, "popup","width=800,height=400,resizable=yes,scrollbars=yes");
    }
</script>
{/literal}
