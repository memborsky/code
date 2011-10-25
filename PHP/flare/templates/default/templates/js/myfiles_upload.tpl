{literal}
<script type='text/javascript'>
    function refresh_list(val) {
        opener.focus();
        opener.document.filesform.submit();
        self.close();
    }

    function submit_upload() {
        opener.document.filesform.path=document.upload.path.value;
        document.upload.submit();
    }
</script>
{/literal}
