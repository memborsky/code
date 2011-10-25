{literal}
<script type='text/javascript'>
function archive_desc(name) {
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
