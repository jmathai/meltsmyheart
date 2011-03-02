var httpClient = (function() {
  return {
    initAndSend: function(params) {
      var url = params.url ? params.url : null,
          method = params.method ? params.method : null,
          timeout = params.timeout ? params.timeout : 120000,
          headers = params.headers ? params.headers : [],
          postbody= params.postbody ? params.postbody : {},
          success = params.success ? params.success : function(e) { Ti.API.info('Called default success callback'); },
          failure = params.failure ? params.failure : function(e) { Ti.API.info('Called default error callback'); },
          xhr;
      if(url === null) {
        Ti.API.info('No url passed in to initAndSend');
        return;
      } else if (method === null) {
        Ti.API.info('No url passed in to initAndSend');
        return;
      }

      xhr = Ti.Network.createHTTPClient({'timeout':timeout});
      xhr.onerror = failure;
      xhr.onload = success;
      Titanium.API.info(xhr);
      Titanium.API.info('(http call) ' + method + ':' + url + ' -> postbody:' + JSON.stringify(postbody));
      xhr.open(method, url);
      xhr.send(postbody);
      return xhr;
    }
  };
})();
