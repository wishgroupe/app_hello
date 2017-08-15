/**
 * \file BoondManager_App.js
 * \brief App's iFrame Management
 * \author Tanguy Lambert
 * \date 14 Mars 2017
 *
 */
if(!window.BoondManager)
    window.BoondManager = {
        _mainDivId:'frame_output',
        _targetOrigin: '*',
        init:function(a) {
            BoondManager._mainDivId=a.mainDivId;
            if(a.targetOrigin != undefined) BoondManager._targetOrigin=a.targetOrigin;
        },
        postMessage: function(payload) {
            if(parent && parent.postMessage) {
                var nowTime = new Date;
                payload['time'] = nowTime.getTime();
                parent.postMessage(payload, BoondManager._targetOrigin);
            }
        },
        redirect:function(url) {
            BoondManager.postMessage({'action': 'redirect', 'url': url});
        },
        setSize:function(h) {
            BoondManager.postMessage({'action': 'setSize', 'height': h});
        },
        setAutoResize:function() {
            if(document.getElementById(BoondManager._mainDivId))
                BoondManager.postMessage({'action': 'setSize', 'height': (document.getElementById(BoondManager._mainDivId).offsetHeight+10)});
        },
        scrollTo:function(h) {
            BoondManager.postMessage({'action': 'scrollTo', 'height': h});
        },
        confirm: function(confirm_message, bYES, bNO, language) {
            switch(language) {
                default:
                    lYES = 'Oui';
                    lNO = 'Non';
                    break;
                case 'en':
                    lYES = 'YES';
                    lNO = 'NO';
                    break;
            }
            if(bYES) bYES += ';'; else bYES = '';
            if(bNO) bNO += ';'; else bNO = '';
            BoondManager.openModalMessage(confirm_message+'<br /><br /><table width="100%"><tr><td width="21%">&nbsp;</td><td width="25%"><input type="button" value="'+lYES+'" onclick="'+bYES+'BoondManager.closeModalMessage();" id="byes_modalmessage" /></td><td width="8%">&nbsp;</td><td width="25%"><input type="button" value="'+lNO+'" onclick="'+bNO+'BoondManager.closeModalMessage();" id="bno_modalmessage" /></td><td width="21%">&nbsp;</td></tr></table>');
        },
        alert: function(alert_message, bOK) {
            if(bOK) bOK += ';'; else bOK = '';
            BoondManager.openModalMessage(alert_message+'<br /><br /><table width="100%"><tr><td width="37%">&nbsp;</td><td width="26%"><input type="button" value="OK" onclick="'+bOK+'BoondManager.closeModalMessage();" id="bok_modalmessage" /></td><td width="37%">&nbsp;</td></tr></table>');
        },
        showModalMask: function() {
            BoondManager.postMessage({'action': 'showModalMask'});
        },
        hideModalMask: function() {
            BoondManager.postMessage({'action': 'hideModalMask'});
        },
        openModalMessage: function(innerHTML) {
            blocmessage = document.getElementById('div_modalmessage');
            blocmessage.innerHTML = '';
            blocmessage.innerHTML = innerHTML;
            blocheight = blocmessage.offsetHeight/2;
            blocmessage.style.marginTop = '-'+blocheight+'px';

            BoondManager.showModalMask();
            BoondManager.showModalMessage();
        },
        closeModalMessage: function() {
            blocmessage = document.getElementById('div_modalmessage');
            blocmessage.style.visibility = 'hidden';
            blocmessage.innerHTML = '';

            BoondManager.hideModalMask();
            BoondManager.hideModalMessage();
        },
        showModalMessage: function() {
            document.getElementById('div_modalmessage').style.visibility = 'visible';
            if(navigator.appName=='Microsoft Internet Explorer') document.getElementById('frame_mask').style.visibility = "visible";
            document.getElementById('div_mask').style.visibility = 'visible';
        },
        hideModalMessage: function() {
            if(navigator.appName=='Microsoft Internet Explorer') document.getElementById('frame_mask').style.visibility = "hidden";
            document.getElementById('div_mask').style.visibility = 'hidden';
        }
    }