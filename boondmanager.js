"use strict";

if (!window.BoondManager) {
	window.BoondManager = function () {
		var SDK_VERSION = "1.0";
		var _mainDivId = null;
		var _targetOrigin = 'https://app.boondmanager.com';
		var _parentID = null;
		var pendingCall = [];
		var lastID = 1;

		function init(a) {
			if(typeof a !== 'undefined') {
				if (typeof a.mainDivId !== 'undefined') _mainDivId = a.mainDivId;
				if (typeof a.targetOrigin !== 'undefined') _targetOrigin = a.targetOrigin;
				if (typeof a.parentID !== 'undefined') _parentID = a.parentID; else {
					var url = new URL(window.location);
					_parentID = url.searchParams.get('iFrameID');
				}
			}
			addEventListenerOnMessage();
			return call('registerSDK', {
				version: SDK_VERSION
			}).then(function (registered) {
				if (!registered) {
					throw new Error('sdk is outdated, please upgrade your sdk');
				}
			});
		}

		function redirect(url) {
			send('redirect', {
				url: url
			});
		}

		function setSize(h) {
			send('setSize', {
				height: h
			});
		}

		function setAutoResize() {
			var elt = null;

			if (_mainDivId) {
				if (document.getElementById(_mainDivId)) {
					elt = document.getElementById(_mainDivId);
				}
			} else {
				elt = document.getElementsByTagName('html').item(0);
			}

			if (elt) {
				setSize(elt.offsetHeight + 10);
			}
		}

		function scrollTo(h) {
			postMessage('scrollTo', {
				height: h
			});
		}

		function confirm(confirm_message, title, bYES, bNO, language) {
			return call('confirm', {
				message: confirm_message,
				title: title,
				yesLabel: bYES,
				noLabel: bNO
			});
		}

		function alert(alert_message, title, bOK) {
			return send('alert', {
				message: alert_message,
				title: title,
				okLabel: bOK
			});
		}

		function showModalMask() {
			postMessage({
				'action': 'showModalMask'
			});
		}

		function hideModalMask() {
			postMessage({
				'action': 'hideModalMask'
			});
		}

		function openModalMessage(innerHTML) {
			var blocmessage = document.getElementById('div_modalmessage');
			blocmessage.innerHTML = '';
			blocmessage.innerHTML = innerHTML;
			var blocheight = blocmessage.offsetHeight / 2;
			blocmessage.style.marginTop = '-' + blocheight + 'px';
			showModalMask();
			showModalMessage();
		}

		function closeModalMessage() {
			var blocmessage = document.getElementById('div_modalmessage');
			blocmessage.style.visibility = 'hidden';
			blocmessage.innerHTML = '';
			hideModalMask();
			hideModalMessage();
		}

		function showModalMessage() {
			document.getElementById('div_modalmessage').style.visibility = 'visible';
			if (navigator.appName === 'Microsoft Internet Explorer') document.getElementById('frame_mask').style.visibility = "visible";
			document.getElementById('div_mask').style.visibility = 'visible';
		}

		function hideModalMessage() {
			if (navigator.appName === 'Microsoft Internet Explorer') document.getElementById('frame_mask').style.visibility = "hidden";
			document.getElementById('div_mask').style.visibility = 'hidden';
		}

		function onMessage(event) {
			var message = event.data;
			var action = message.action;

			switch (action) {
				case 'answer':
					handleResponse(message);
					break;

				case 'onBeforeSave':
					triggerOnBeforeSave(message);
					break;

				case 'onAfterSave':
					triggerOnAfterSave(message);
					break;

				case 'onBeforeValidate':
					triggerOnBeforeValidate(message);
					break;

				case 'onAfterValidate':
					triggerOnAfterValidate(message);
					break;
			}
		}

		function triggerOnAfterSave(message) {
			reply(message.id, publicFunctions.onAfterSave());
		}

		function triggerOnBeforeSave(message) {
			reply(message.id, publicFunctions.onBeforeSave());
		}

		function triggerOnBeforeValidate(message) {
			reply(message.id, publicFunctions.onBeforeValidate());
		}

		function triggerOnAfterValidate(message) {
			reply(message.id, publicFunctions.onAfterValidate());
		}

		function onBeforeSave() {
			return true;
		}

		function onAfterSave() {
			return true;
		}

		function onBeforeValidate() {
			return true;
		}

		function onAfterValidate() {
			return true;
		}

		function setSaveButtonEnabled(enabled) {
			send('enableSave', {
				state: enabled === true
			});
		}

		function handleResponse(message) {
			var data = message.data;
			var callID = message.answerToCallID;
			var error = message.error;
			var index = pendingCall.findIndex(function (item) {
				return item.callID === callID;
			});

			if (index > -1) {
				var item = pendingCall.splice(index, 1)[0];

				if (error) {
					var reject = item.reject;
					reject(error);
				} else {
					var resolve = item.resolve;
					resolve(data);
				}
			}
		}

		function call(method, params) {
			var callID = ++lastID;
			return new Promise(function (resolve, reject) {
				Promise.resolve(params).then(function (params) {
					pendingCall.push({
						callID: callID,
						resolve: resolve,
						reject: reject
					});
					var payload = {
						time: new Date().getTime(),
						data: params,
						action: method,
						iFrameID: _parentID,
						requireAnswer: true,
						id: callID
					};
					postMessage(payload);
				});
			});
		}

		function send(method, params) {
			var callID = ++lastID;
			Promise.resolve(params).then(function (params) {
				var payload = {
					time: new Date().getTime(),
					data: params,
					action: method,
					iFrameID: _parentID,
					requireAnswer: false,
					id: callID
				};
				postMessage(payload);
			});
		}

		function reply(toCallID, params) {
			Promise.resolve(params).then(function (params) {
				var callID = ++lastID;
				var payload = {
					id: callID,
					time: new Date().getTime(),
					action: 'answer',
					iFrameID: _parentID,
					data: params,
					answerToCallID: toCallID,
					requireAnswer: false
				};
				postMessage(payload);
			});
		}

		function postMessage(payload) {
			if (!parent || !parent.postMessage) {
				console.error('unable to post message to parent');
				return;
			}

			parent.postMessage(payload, _targetOrigin);
		}

		function addEventListenerOnMessage() {
			window.addEventListener('message', onMessage, false);
		}

		function test() {
			send('test');
		}

		function getModel() {
			return call('getModel');
		}

		var publicFunctions = {
			init: init,
			postMessage: postMessage,
			redirect: redirect,
			setSize: setSize,
			setAutoResize: setAutoResize,
			scrollTo: scrollTo,
			confirm: confirm,
			alert: alert,
			showModalMask: showModalMask,
			hideModalMask: hideModalMask,
			setSaveButtonEnabled: setSaveButtonEnabled,
			onAfterSave: onAfterSave,
			onBeforeSave: onBeforeSave,
			onBeforeValidate: onBeforeValidate,
			onAfterValidate: onAfterValidate,
			test: test,
			getModel: getModel
		};
		return publicFunctions;
	} ();
}