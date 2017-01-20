<?php
/**
 * tools.php
 */
namespace Lib;

class Tools {
    /**
     * Encode une URL en base64.
     *
     * @param string $input URL à encoder
     * @return string Chaîne de caractères encodée
     */
    public static function base64_url_encode($input) {return strtr(base64_encode($input), '+/', '-_');}

    /**
     * Décode une chaîne de caractères en base64 en URL.
     *
     * @param string $input Chaîne à décoder
     * @return string URL décodée
     */
    public static function base64_url_decode($input) {return base64_decode(strtr($input, '-_', '+/'));}

    /**
     * Encode & crypte un tableau en base64.
     *
     * @param array $payload Tableau à encoder & crypter
     * @param string $key Clef utilisée pour crypter la chaîne de caractères
     * @return string Chaîne de caractères encodée & cryptée
     */
    public static function signedRequest_encode($payload, $key) {
        $payload['issued_at'] = time();
        $payload = self::base64_url_encode(json_encode($payload));
        return self::base64_url_encode(hash_hmac('SHA256', $payload, $key)).'.'.$payload;
    }

    /**
     * Décrypte & décode une chaîne de caractères en base64 en URL.
     *
     * @param array $signed_request Chaîne de caractères à décrypter & décoder
     * @param string $key Clef utilisée pour décrypter la chaîne de caractères
     * @return boolean|array
     * - `false` : Si le décodage est incorrect
     * - __array__ : Tableau décrypté & décodé_, uniquement si le décodage est correct_
     */
    public static function signedRequest_decode($signed_request, $key) {
        $tabRequest = explode('.', $signed_request, 2); //0 = encoded_signature AND 1 = payload
        return (sizeof($tabRequest) == 2 && self::base64_url_decode($tabRequest[0]) == hash_hmac('sha256', $tabRequest[1], $key))
            ?json_decode(self::base64_url_decode($tabRequest[1]), true)
            :false;
    }

    /**
     * Encode un tableau en chaîne de caractères.
     *
     * @param array $tabParam Tableau à encoder
     * @return string Chaîne de caractères encodée
     */
    public static function objectID_BM_encode($tabParam = array()) {
        $retval = '';$object = implode('.',$tabParam);$length = strlen($object);
        for($idx = 0; $idx < $length; $idx++) $retval .= str_pad(base_convert(ord($object[$idx]), 10, 16), 2, '0', STR_PAD_LEFT);
        return $retval;
    }

    /**
     * Décode une chaîne de caractères en tableau.
     *
     * @param string $stringParam Chaîne de caractères à décoder
     * @return array Tableau décodé
     */
    public static function objectID_BM_decode($stringParam = '') {
        $retval = '';
        if(strlen($stringParam) % 2 == 1) $stringParam = '0'.$stringParam;
        $length = strlen($stringParam);
        for($idx = 0; $idx < $length; $idx += 2) $retval .= chr(base_convert(substr($stringParam, $idx, 2), 16, 10));
        return explode('.',$retval);
    }
}
