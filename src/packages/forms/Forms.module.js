var FORMS = FORMS || {};
FORMS = (function (base) {
  base.moduleName = 'FORMS';

  base.init = function(){
    // Attach custom Events to forms
    let ajaxForms = document.querySelectorAll('.form-ajax');
    for (var i = 0; i < ajaxForms.length; i++){

      // Init textarea auto-grow.
        let textareas = ajaxForms[i].querySelectorAll('textarea');
        for (var j = 0; j < textareas.length; j++)
          var growingTextarea = new Autogrow(textareas[j]);

        base.handleXHRSubmit(ajaxForms[i]);
        // base.handlePatternValidation(ajaxForms[i]);
    }
  }

  base.handlePatternValidation = function (formElement) {
      let inputsWithPattern = formElement.querySelectorAll('input[pattern]');
      for (var i = 0; i < inputsWithPattern.length; i++)
          inputsWithPattern[i].addEventListener('input', base.onInputValidation, false)
  }

  base.handleXHRSubmit = function (formElement) {
      // handle submit event
      formElement.addEventListener('submit', function(event) {
            event.preventDefault();

            // Handle submission via XHR
            XHR.post(event.target.action, event.target)
            .then(function(server) {
                // Get the callback function name for success
                let successCallback = event.target.getAttribute('ajax-success');
                // And call it.
                if(successCallback)
                base.executeFunctionByName(successCallback, window, server);
            }).catch(function(server) {
                // Get the callback function name for failure
                let failCallback = event.target.getAttribute('ajax-failure');
                // And call it.
                if(failCallback)
                base.executeFunctionByName(failCallback, window, server);
            });
      }, false);
  }

  base.onInputValidation = function(event) {
      let input = event.target;
      let thePattern = input.getAttribute('pattern');
      let regex = new RegExp(thePattern);
      if(!regex.test(input.value)){
          base.setFeedback(input.getAttribute('name'), input.getAttribute('title'))
      } else {
          base.setFeedback(input.getAttribute('name'), 'Valid', 'success');
      }
  }

  base.executeFunctionByName = function(functionName, context /*, args */) {
    var args = Array.prototype.slice.call(arguments, 2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for(var i = 0; i < namespaces.length; i++) {
      context = context[namespaces[i]];
    }
    return context[func].apply(context, args);
  }

  return base;
})(FORMS || {});


function customSuccess(response){
    alert('this is a sucess');
}

function customFailure(response){
    alert('this is a failure');
}
