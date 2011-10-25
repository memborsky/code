<!-- START:	js/groups_create.tpl -->
{literal}
<script type='text/javascript'>
    function check_availability() {
        name=document.groups.group_name.value
        window.open("index.php?extension=Groups&action=do_check_for_existing_group&group_name="+name, "popup","width=800,height=400,resizable=yes,scrollbars=yes");
    }
    function group_desc(name) {
        var form = document.forms[0]
        for(i = 0; i < document.forms[0].elements.length; i++){
            if(form.elements[i].type == "radio"){
                if (form.elements[i].value == name) {
                    document.getElementById(name).style.display = 'block'
                } else {
                    document.getElementById(form.elements[i].value).style.display = 'none'
                }
            }
        }
    }
</script>
{/literal}
<!-- END:	js/groups_create.tpl -->
