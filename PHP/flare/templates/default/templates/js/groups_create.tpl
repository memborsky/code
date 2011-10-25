<!-- START:	js/groups_create.tpl -->
{literal}
<script type='text/javascript'>
    function verify_data() {
        if (document.groups.share_amount.value < 10) {
            alert("Group share must be at least 10 Megabytes");
            return false;
        }

        difference = document.groups.total_quota.value - document.groups.share_amount.value;
        if (difference < 0) {
            alert("The share amount you chose exceeds your total quota!");
            return false;
        } 

        if (document.groups.group_name.value == "") {
            alert("You must enter a group name");
            return false;
        } else {
            document.groups.submit();
            return true;
        }
    }

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

    // getObject and char_counter function taken from
    // http://javascript.internet.com/forms/character-counter.html
    function getObject(obj) {
        var theObj;
        if(document.all) {
            if(typeof obj=="string") {
                return document.all(obj);
            } else {
                   return obj.style;
            }
        }

        if(document.getElementById) {
            if(typeof obj=="string") {
                return document.getElementById(obj);
            } else {
                return obj.style;
            }
        }

        return null;
    }
    
    // Count input characters
    function char_counter(start,finish,text,characters) {
        var startObj=getObject(start);
        var finishObj=getObject(finish);
        var length=characters - startObj.value.length;
        if(length <= 0) {
            length=0;
            text='<span class="disable"> '+text+' </span>';
            startObj.value=startObj.value.substr(0,characters);
        }
        finishObj.innerHTML = text.replace("{CHAR}",length);
    }

    function verify_quota() {
        var quota = 0;
        
        if (isNaN(document.groups.share_amount.value)) {
            alert('Please enter only numerical values into the group quota.');
            document.groups.share_amount.value = '';
        }
    }
</script>
{/literal}
<!-- END:	js/groups_create.tpl -->
