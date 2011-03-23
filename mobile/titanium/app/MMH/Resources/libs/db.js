var db = (function() {
  var dbHandle = null, safe;
  safe = function(string) {
    // TODO properly escape here
    return string;
    //return string.replace(/'/,"\'");
  };

  return {
    insertKey: function(name, value) {
      db.open();
      db.execute("REPLACE INTO prefs(name, value) VALUES('"+safe(name)+"','"+safe(value)+"');");
    },
    open: function() {
      if(dbHandle === null) {
        dbHandle = Titanium.Database.open(mmh.constant('databaseName'));
        //dbHandle.execute('DROP TABLE prefs;');
        dbHandle.execute('CREATE TABLE IF NOT EXISTS prefs(name TEXT, value TEXT, PRIMARY KEY(name, value));');
        if(!dbHandle) {
          Ti.API.info('COULD NOT CREATE DB HANDLE');
        }
      }
    },
    query: function(sql) {
      db.open();
      Ti.API.info('dbHandle ' + dbHandle + ' -> ' + sql);
      var result = dbHandle.execute(sql);
      if(!result.isValidRow()) {
        Ti.API.info(sql + ' is null');
        return null;
      }
      return result;
    },
    queryForKey: function(key) {
      db.open();
      var value = null, rs, sql = "SELECT value FROM prefs WHERE name='"+safe(key)+"';";
      rs = db.query(sql);
      if(rs !== null) {
        value = rs.fieldByName('value');
        Ti.API.info('Value ' + key + ' was found');
      }
      
      return value;
    },
    execute: function(sql) {
      db.open();
      Ti.API.info('dbHandle ' + dbHandle + ' -> ' + sql);
      Ti.API.info(sql);
      return dbHandle.execute(sql);
    }
  };
})();
