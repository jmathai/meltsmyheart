var contactList = function(_view, _tableParams) {
  var callFailure, callSuccess, contactTableData, makeCall, rowClickCallback, rowClickCallbackFailure,
      rowClickCallbackSuccess, rowHeight = 50, tblContacts, tblParams=_tableParams, view=_view;

  makeCall = function() {
    httpClient.initAndSend({
      method:'POST',
      url: mmh.constant('siteUrl') + '/api/recipients',
      postbody: mmh.user.getRequestCredentials(),
      success: callSuccess,
      failure: callFailure
    });
  };

  callSuccess = function() {
    var json = JSON.parse(this.responseText), recipients, rows, emails = {}, params, i;
    // just in case
    mmh.ui.loader.hide();
    for(i in json.params.recipients) {
      recipient = json.params.recipients[i];
      emails[recipient.r_email] = 1;
    }
    rows = contactTableData(emails);
    params = mmh.util.merge({data:rows}, tblParams);
    params.height = (rows.length * rowHeight);
    tblContacts = Ti.UI.createTableView(params);
    view.add(tblContacts);
  };
  callFailure = function() {
    // just in case
    mmh.ui.loader.hide();
    Ti.UI.createAlertDialog({
        title: 'Problem loading contacts',
        message: 'Sorry, we could not load your contacts.'
    }).show();
    mmh.util.log('opening contacts failed');
  };

  contactTableData = function(emails) {
    var people = Ti.Contacts.getAllPeople(), p, rows = [], email, title, phone, cont, row, image;
    for (var i = 0; i < people.length; i++) {
      cont = true;
      p = people[i];
      if(p.fullName === null) {
        cont = false;
      }

      email = '';
      if(p.email !== null) {
        if(p.email.home !== null) {
          email = p.email.home[0];
        } else if(p.email.other !== null) {
          email = p.email.other[0];
        } else if(p.email.work !== null) {
          email = p.email.work[0];
        } else {
          cont = false;
        }
      } else {
        cont = false;
      }

      phone = '';
      if(p.phone !== null) {
        if(p.phone.mobile !== null) {
          phone = p.phone.mobile[0];
        } else if(p.phone.iPhone !== null) {
          phone = p.phone.iPhone[0];
        } else if(p.phone.main !== null) {
          phone = p.phone.main[0];
        }
      }

      if(cont) {
        icon = emails[email] === undefined ? 'images/add-24.png' : 'images/remove-24.png';
        title = p.fullName + '\n' + email;
        row = Ti.UI.createTableViewRow({ className:'contactRow',title: title, /*leftImage: p.image.toBlob(),*/ rightImage: icon, fullName: p.fullName, email: email, phone: phone, idx: rows.length });
        row.addEventListener('click', rowClickCallback);
        rows.push(row);
      }
    }
    mmh.util.log('contact list has ' + rows.length + ' entries');
    return rows;
  };

  rowClickCallback = function(e) {
    var data = e.rowData, postbody, source, instTblContacts;
    instTblContacts = tblContacts;
    mmh.util.log('instTblContacts');
    mmh.util.log(instTblContacts);
    mmh.util.log(JSON.stringify(data));
    source = e.source
    postbody = mmh.util.merge(mmh.user.getRequestCredentials(), {name: data.fullName, email: data.email, mobile: data.phone, rowIndex: data.idx});
    httpClient.initAndSend({
      method: 'POST',
      url: mmh.constant('siteUrl') + '/api/recipient/modify',
      postbody: postbody,
      success: rowClickCallbackSuccess,
      failure: rowClickCallbackFailure
    });
  };

  rowClickCallbackFailure = function(e) {
    mmh.util.log('failure');
    mmh.util.log(source);
    mmh.util.log(this.responseText);
  };

  rowClickCallbackSuccess = function(e) {
    var json = JSON.parse(this.responseText), image;
    image = json.params.action == 'added' ? 'images/remove-24.png' : 'images/add-24.png';
    tblContacts.data[0].rows[json.params.rowIndex].rightImage=image;
    mmh.util.log('success ' + image);
    mmh.util.log(this.responseText);
    mmh.util.log();
  };

  return {
    render: function() {
      makeCall();
    }
  };
};
