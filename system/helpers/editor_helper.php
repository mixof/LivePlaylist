<?php

function fckeditor()
{
  $scrip="<script type='text/javascript' src='{SITEURL}/js/tinymce/tinymce.min.js'></script>
<script type='text/javascript'>
tinymce.init({
    selector: 'textarea',
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
});
</script>";

return $scrip;  
}
?>  