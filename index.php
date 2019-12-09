<?php

require_once 'init.php';
require_once 'boondmanager.php';

$script = isset($_REQUEST['script']) ? $_REQUEST['script'] : '';

$boondmanager = new BoondManager(APP_KEY);

// decoding data sent by boondmanager
$data = $boondmanager->signedRequestDecode($_REQUEST['signedRequest']);

switch($script) {
	// installation api
	case 'install':
		// install script needs an answer in json
		header('Content-Type: application/json');

		// handle first request with installationCode
		if($data && isset($data['installationCode']) && $data['installationCode'] == APP_CODE) {
			exit(json_encode([
				'result' => true
			]));
		}

		// handle second request with appToken
		if($data && isset($data['appToken']) && isset($data['clientToken']) && isset($data['clientName'])) {
			// backup the customer token (for example, in a database)
			file_put_contents('appToken.txt', $data['appToken']);

			exit(json_encode([
				'result' => true
			]));
		}

		 //if something went wrong, return false
		exit(json_encode([
			'result' => false
		]));
		break;

	// uninstall api
	case 'uninstall':
		// install script needs an answer in json
		header('Content-Type: application/json');

		if($data && isset($data['clientToken'])) {//Authorization's confirmation
			// deleting the token
			if(file_exists('appToken.txt')) {
				unlink('appToken.txt');
			}

			exit(json_encode([
				'result' => true
			]));
		}

		exit(json_encode([
			'result' => false
		]));
		break;

	// configuration's view
	case 'configuration':
		echo 'Configuration Page';
		break;

	// main's view
	default:
		$boondmanager->setAppToken( file_get_contents('appToken.txt') );
		$boondmanager->setUserToken( $data['userToken'] );

		$response = $boondmanager->callApi('application/current-user');
		if(!$response) {
			exit('You do not have right for this api !');
		}

		$function = isset($_REQUEST['function']) ? $_REQUEST['function'] : '';
		$firstName = $response->data->attributes->firstName;
		$lastName = $response->data->attributes->lastName;

$page = <<<CONTENT
<!DOCTYPE html>
<html>
	<head>
	    <script type="text/javascript" src="https://ui.boondmanager.com/sdk/boondmanager.js"></script>
	</head>
	<body>
		<script type="text/javascript">
		    
		</script>
		
		<h1>{$function}</h1>
		
		<p>Hello {$firstName} {$lastName} </p>
		
		<p id="paragraph2" style="display: none"> Text hidden which is now visible</p>
		
		<hr/>
		
		<div>
			<a href="#" onclick="BoondManager.redirect('/resources');" class="button">go to resources</a>
		</div>
		
		<script>
			BoondManager.init({
				targetOrigin : '*'
			}).then( () => {
		        BoondManager.setAutoResize();
		    })
		</script>
	</body>
</html>
CONTENT;

		echo $page;
		break;
}




