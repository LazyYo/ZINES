<script type="text/javascript">
    // Init Forms
    FORMS.init();


    let textEditors = document.querySelectorAll('textarea.editor');
    for (var i = 0; i < textEditors.length; i++) {
        var editor = CodeMirror.fromTextArea(textEditors[i], {
          theme: 'mbo',
          lineNumbers: true,
          mode: "application/x-httpd-php",
          indentUnit: 4,
          indentWithTabs: true,
          enterMode: "keep",
          tabMode: "shift"
        });
    }


</script>
