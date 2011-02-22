var camera = (function() {
  return {
    start: function(params){
      var success = params.success ? params.success : function(ev) { Titanium.API.info('Calling default camera success callback'); },
          failure = params.failure ? params.failure : function(ev) { Titanium.API.info('Calling default camera failure callback'); };
      return Titanium.Media.showCamera({success: success, error: failure});
    }
  };
})();
