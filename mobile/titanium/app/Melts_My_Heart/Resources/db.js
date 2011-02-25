var db = (function() {
  var dbHandle;
  return {
    open: function() {
      if(!dbHandle) {
        dbHandle = Titanium.Database.open(mmh.constant('databaseName'));
        dbHandle.execute('CREATE TABLE IF NOT EXISTS prefs(name TEXT, value TEXT, PRIMARY KEY(name));');
      }
    },
    query: function(sql) {
      db.open();
      Titanium.API.info('dbHandle ' + dbHandle);
      var result = dbHandle.execute(sql);
      if(!result.validRow) {
        return null;
      }
      
      return result;
    },
    queryForKey: function(key) {
      var value = null, rs;
      rs = db.query("SELECT value FROM prefs WHERE name='"+key+"'");
      if(rs !== null) {
        value = rs.fieldByName('value');
      }
      return value;
    },
    execute: function(sql) {
      db.open();
      db.execute(sql);
    }
  };
})();
