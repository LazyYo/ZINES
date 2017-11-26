var LOG = LOG || {};

LOG = (function(base) {
  base.moduleName = 'LOG';
  base.ElementID = 'LogModuleElement';

  base.DOMElement = function() {
    var e = document.getElementById(base.ElementID);
    if(!e){
      e = document.body.appendChild(document.createElement('p'));
      e.className = 'clear';
      e.id = base.ElementID;
    }
    return e;
  }

  base.error = function(t, type){
    console.error(t);
    let DOMEl = this.DOMElement();
    if(this.clearError) clearTimeout(this.clearError);
    DOMEl.innerHTML = t;
    DOMEl.classList.add('error');
    DOMEl.classList.remove('clear');
    DOMEl.classList.remove('success');
    // QUEUE.setState(type || 'ERROR');
    this.clearError = setTimeout( function () {
        DOMEl.className = '';
        DOMEl.classList.add('clear');
        // QUEUE.setState('UP_TO_DATE');
        this.clearError = null;
    }, 3000 );
  }

  base.success = function(t, type){
    let DOMEl = this.DOMElement();
    if(this.clearError) clearTimeout(this.clearError);
    DOMEl.innerHTML = t;
    DOMEl.className = 'success';
    // QUEUE.setState(type || 'UP_TO_DATE');
    this.clearError = setTimeout( function () {
        DOMEl.className = 'clear';
        // QUEUE.setState('UP_TO_DATE');
        this.clearError = null;
    }, 3000 );
  }
  /** @function log
  *   Logs the server response object given the error flag.
  *   @param {error:{boolean},out:{object}} serverResponse - the Server response object.
  *   @param String message - The callback message in case the server is not giving one.
  */
  base.log = function(serverResponse, message){
    if(serverResponse.error){
      base.error(serverResponse.out, serverResponse.errorCode );
      return false;
    } else {
      base.success(message || serverResponse.out, serverResponse.errorCode);
      return true;
    }
  }

  base.check = function(serverResponse){
    if(serverResponse.error){
      base.error(serverResponse.log || serverResponse.out, serverResponse.errorCode );
      return false;
    } else {
      base.success(serverResponse.log || serverResponse.out, serverResponse.errorCode);
      return true;
    }
  }

  return base;
})(LOG || {});
