/**
 * \file BoondManager_App.js
 * \brief App's iFrame management
 * \author Tanguy Lambert
 * \version 1.0
 * \date 23 January 2017
 *
 */

function setMessageModal(innerHTML) {
    blocmessage = document.getElementById('div_modalmessage');
    blocmessage.innerHTML = '';
    blocmessage.innerHTML = innerHTML;
    blocheight = blocmessage.offsetHeight/2;
    blocmessage.style.marginTop = '-'+blocheight+'px';

    apitime = new Date;
    window.top.postMessage({name: 'show_mask', time: apitime.getTime()}, '*');
    setTimeout("displayMessageModal()", 100); //Le délai doit être identique à Wish_IFrame.js

}

function displayMessageModal() {
    document.getElementById('div_modalmessage').style.visibility = 'visible';
    if(navigator.appName=='Microsoft Internet Explorer') document.getElementById('frame_mask').style.visibility = "visible";
    document.getElementById('div_mask').style.visibility = 'visible';
}

function closeMessageModal() {
    blocmessage = document.getElementById('div_modalmessage');
    blocmessage.style.visibility = 'hidden';
    blocmessage.innerHTML = '';

    apitime = new Date;
    window.top.postMessage({name: 'hide_mask', time: apitime.getTime()}, '*');
    setTimeout("hideMaskModal()", 100); //Le délai doit être identique à Wish_IFrame.js
}

function hideMaskModal() {
    if(navigator.appName=='Microsoft Internet Explorer') document.getElementById('frame_mask').style.visibility = "hidden";
    document.getElementById('div_mask').style.visibility = 'hidden';

}

if(!window.BM)
    window.BM = {
        maindivid: null,
        _noframe: false,
        init: function (a) {
            BM._maindivid = a.maindivid;

            if (a.noframe != undefined) BM._noframe = a.noframe;

            if (navigator.appName == 'Microsoft Internet Explorer') {
                var frameMask = document.createElement("iframe");
                frameMask.id = 'frame_mask';
                frameMask.src = 'about:blank';
                frameMask.frameBorder = '0';
                frameMask.scrolling = 'no';
                document.body.prepend(frameMask);
            }

            var divMask = document.createElement("div");
            divMask.id = 'div_mask';
            document.body.prepend(divMask);

            var divModalMessage = document.createElement("div");
            divModalMessage.id = 'div_modalmessage';
            document.body.prepend(divModalMessage);
        },
        redirect: function (url) {
            window.top.postMessage({name: 'redirect', urlRedirect: encodeURIComponent(url)}, '*');
        },
        setSize: function (h) {
            if (!BM._noframe)
                window.top.postMessage({name: 'set_size', height: h}, '*');
        },
        setAutoResize: function () {
            if (!BM._noframe)
                window.top.postMessage({name: 'set_size', height: (document.getElementById(BM._maindivid).offsetHeight + 10)}, '*');
        },
        scrollTo: function (h) {
            if (!BM._noframe)
                window.top.postMessage({name: 'scroll_to', scrollTo: h}, '*');
        },
        alert: function (alert_message, bOK) {
            if (bOK) bOK += ';'; else bOK = '';
            setMessageModal(alert_message + '<br /><br /><table width="100%"><tr><td width="37%">&nbsp;</td><td width="26%"><input type="button" value="OK" onclick="' + bOK + 'closeMessageModal();" id="bok_modalmessage" /></td><td width="37%">&nbsp;</td></tr></table>');
        },
        confirm: function (confirm_message, bYES, bNO, language) {
            switch (language) {
                default:
                    lYES = 'Oui';
                    lNO = 'Non';
                    break;
                case 'en':
                    lYES = 'YES';
                    lNO = 'NO';
                    break;
            }
            if (bYES) bYES += ';'; else bYES = '';
            if (bNO) bNO += ';'; else bNO = '';
            setMessageModal(confirm_message + '<br /><br /><table width="100%"><tr><td width="21%">&nbsp;</td><td width="25%"><input type="button" value="' + lYES + '" onclick="' + bYES + 'closeMessageModal();" id="byes_modalmessage" /></td><td width="8%">&nbsp;</td><td width="25%"><input type="button" value="' + lNO + '" onclick="' + bNO + 'closeMessageModal();" id="bno_modalmessage" /></td><td width="21%">&nbsp;</td></tr></table>');
        }
    }
