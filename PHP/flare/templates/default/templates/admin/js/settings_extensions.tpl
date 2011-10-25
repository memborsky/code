{literal}
<script type='text/javascript'>
    function actions(action, extname) {
        switch(action) {
            case 'install_extension':
                document.extensions.action.value="do_install_extension";
                document.extensions.extension_name.value=extname;
                document.extensions.submit();
                return true;
            case 'remove_extension':
                document.extensions.action.value="do_remove_extension";
                document.extensions.extension_name.value=extname;
                document.extensions.submit();
                return true;
        }
    }
</script>
{/literal}
