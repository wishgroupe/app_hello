<?php
/**
 * web.php
 */

namespace Wish;


class Web
{
    /**
     * @var  \Web $_web
     */
    private $_web;

    /**
     * @var  string $_basicAuthorization
     */
    private $_basicAuthorization;

    /**
     * @var  array $_specificHeaders
     */
    private $_specificHeaders;

    /**
     * @var  string $_contentType
     */
    private $_contentType;

    /**
     * @var  string $_url
     */
    private $_url;

    /**
     * @var  array $_response
     */
    private $_response;

    /**#@+
     * @var int
     **/
    const
        ENGINE_CURL = 'curl',
        ENGINE_STREAM = 'stream',
        ENGINE_SOCKET = 'socket';
    /**#@-*/

    /**
     * Web constructor.
     * @param string|null $login
     * @param string|null $pwd
     */
    public function __construct($login = null, $pwd = null) {
        $this->_web = \Web::instance();
        $this->_specificHeaders = [];
        $this->setCredentials($login, $pwd);
        $this->reset();
    }

    /**
     * @return Web
     */
    public function reset() {
        $this->_url = null;
        $this->_contentType = null;
        $this->_response = [];
        return $this;
    }

    /**
     * @param string $contentType
     * @return Web
     */
    public function setContentType($contentType = null) {
        if($this->_web->acceptable($contentType)) $this->_contentType = 'Content-type: '.$contentType;
        return $this;
    }

    /**
     * @param string|null $login
     * @param string|null $pwd
     * @return Web
     */
    public function setCredentials($login = null, $pwd = null) {
        $this->_basicAuthorization = isset($login) && isset($pwd) ?
            'Authorization: Basic '.base64_encode($login . ':' . $pwd) : null;
        return $this;
    }

    /**
     * @param array $specificHeaders
     * @return Web
     */
    public function setSpecificHeaders($specificHeaders = []) {

        $this->_specificHeaders = [];
        if(is_array($specificHeaders)) {
            foreach($specificHeaders as $header) {
                if(is_string($header))
                    $this->_specificHeaders[] = $header;
            }
        }
        return $this;
    }

    /**
     * @param  string $pMethod  Méthode (GET|POST|PUT|DELETE) de la requête REST.
     * @param  mixed $pContent Paramètres du corps de la requête REST.
     * @return array
     */
    protected function _createOptions($pMethod, $pContent = null)
    {
        $opts = ['method' => $pMethod];
        if($this->_contentType) $opts['header'][] = $this->_contentType;
        if($this->_basicAuthorization) $opts['header'][] = $this->_basicAuthorization;
        if($this->_specificHeaders) {
            foreach($this->_specificHeaders as $header)
                $opts['header'][] = $header;
        }
        if($pContent) $opts['content'] = is_array($pContent) ? http_build_query($pContent) : $pContent;
        return $opts;
    }

    /**
     * @param  array $pParams Paramètres de l'URL de la requête REST.
     * @return string URL de la requête REST.
     */
    protected function _makeUrl($pParams = []) {
        return $this->_url . ( $pParams ? '?' . http_build_query($pParams) : '' );
    }

    /**
     * Définit l'URL et le header `Content-type` de la requête REST.
     * @param string $url
     * @param string $contentType
     * @return Web
     */
    public function setUrl($url, $contentType = null) {
        $this->_url = $url;
        $this->setContentType($contentType);
        return $this;
    }

    /**
     * Exécute une requête GET.
     * @param  array  $pParams Tableau des paramètres de l'URL la requête REST.
     * @return array|false Tableau de réponse de la requête REST ou `false` en cas d'erreur.
     */
    public function get($pParams = array()) {
        return $this->launchRequest($this->_makeUrl($pParams), $this->_createOptions('GET'));
    }

    /**
     * Exécute une requête POST.
     * @param  array  $pParams Tableau des paramètres du corps de la requête REST.
     * @param  array  $pGetParams Tableau des paramètres de l'URL de la requête REST.
     * @return array|false Tableau de réponse de la requête REST ou `false` en cas d'erreur.
     */
    public function post($pParams = array(), $pGetParams = array()) {
        return $this->launchRequest($this->_makeUrl($pGetParams), $this->_createOptions('POST', $pParams));
    }

    /**
     * Exécute une requête PUT.
     * @param  array  $pParams Tableau des paramètres du corps de la requête REST.
     * @param  array  $pGetParams Tableau des paramètres de l'URL de la requête REST.
     * @return array|false Tableau de réponse de la requête REST ou `false` en cas d'erreur.
     */
    public function put($pParams = array(), $pGetParams = array()) {
        return $this->launchRequest($this->_makeUrl($pGetParams), $this->_createOptions('PUT', $pParams));
    }

    /**
     * Exécute une requête DELETE.
     * @param  array  $pParams Tableau des paramètres de l'URL de la requête REST.
     * @return array|false Tableau de réponse de la requête REST ou `false` en cas d'erreur.
     */
    public function delete($pParams = array()) {
        return $this->launchRequest($this->_makeUrl($pParams), $this->_createOptions('DELETE'));
    }

    /**
     * @param string $url
     * @param array|NULL $options
     * @return mixed
     */
    private function launchRequest($url, array $options=NULL) {
        $this->_response = $this->_web->request($url, $options);
        if($this->_response['error']) return false;
        $isJSON = false;
        $isOK = false;

        $headers = implode("\r\n", $this->_response['headers']);
        if(preg_match('/Content-Type:\s*application\/json.*/', $headers, $exp)) $isJSON = true;
        if(preg_match('/HTTP\/\d\.?\d?\s*200.*/', $headers, $exp)) $isOK = true;

        if($isJSON) $this->_response['body'] = json_decode($this->_response['body'], true);

        if(!$isOK) {
            $this->_response['error'] = $this->_response['body'];
            return false;
        } else
            return $this->_response['body'];
    }

    /**
     * @return array
     */
    public function getLastResponse() {
        return $this->_response;
    }

    /**
     * @return string
     */
    public function getLastHeaders() {
        return $this->_response ? $this->_response['headers']: [];
    }

    /**
     * @return string
     */
    public function getLastEngine() {
        return $this->_response ? strtolower($this->_response['engine']) : self::ENGINE_CURL;
    }

    /**
     * @return bool
     */
    public function getLastCache() {
        return $this->_response ? $this->_response['cache'] : false;
    }

    /**
     * @return string
     */
    public function getLastError() {
        return $this->_response ? $this->_response['error'] : '';
    }
}
