var XHR = XHR || {};

XHR = (function (base) {
  base.moduleName = 'XHR';

  // base.call = function(type, url, opts, callback) {
  base.call = function(type, url, opts) {
    return new Promise(function (resolve, reject) {
         var xhr = new XMLHttpRequest(), fd;

         xhr.onload = function (event) {
             var jsonResponse = JSON.parse(xhr.response);
             if(jsonResponse.error) reject(jsonResponse);
             resolve(jsonResponse); // Si la requête réussit, on résout la promesse
         };

         xhr.onerror = function (err) {
             reject(err); // Si la requête échoue, on rejette la promesse en envoyant les infos de l'erreur
         }

         if (type === 'POST' && opts) {
           if(opts.nodeName == 'FORM'){
             fd = new FormData(opts);
           }else if(opts instanceof FormData){
             fd = opts;
           }else {
             fd = new FormData();
             for (var key in opts) {
               fd.append(key, opts[key]);
             }
           }
         }

         xhr.open(type, url);
         xhr.send(opts ? fd : null);
     });
  }

  base.get = base.call.bind(base, 'GET');
  base.post = base.call.bind(base, 'POST');

  base.submit = function(event, formElement, callback) {
    event.preventDefault();
    console.log('[POST REQUEST] '+formElement.action);
    base.post(formElement.action, formElement)
    .then(function (server) {
      callback(server);
    })
    .catch(function(server) {
      LOG.error(server.error);
    });
  }

  return base;
})(XHR || {});
