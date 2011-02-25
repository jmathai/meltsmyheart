var httpClient = (function() {
  return {
    initAndSend: function(params) {
      var url = params.url ? params.url : null,
          method = params.method ? params.method : null,
          timeout = params.timeout ? params.timeout : 120000,
          headers = params.headers ? params.headers : [],
          postbody= params.postbody ? params.postbody : {},
          success = params.success ? params.success : function(e) { Titanium.API.info('Called default success callback'); },
          failure = params.failure ? params.failure : function(e) { Titanium.API.info('Called default error callback'); },
          xhr;
      if(url === null) {
        Titanium.API.info('No url passed in to initAndSend');
        return;
      } else if (method === null) {
        Titanium.API.info('No url passed in to initAndSend');
        return;
      }

      xhr = Titanium.Network.createHTTPClient({'timeout':timeout});
      xhr.onerror = failure;
      xhr.onload = success;
      xhr.open(method, url);
      xhr.send(postbody);
      return xhr;
    }
  };
})();
