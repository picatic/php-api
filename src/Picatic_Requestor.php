<?php

/**
 * Basic request dispatch using CURL
 */
class Picatic_Requestor implements Picatic_Requestor_Interface, Picatic_Consumer_Interface {

  public $picaticApiInstance = null;

  public function setPicaticApi($picaticApi) {
    $this->picaticApiInstance = $picaticApi;
  }

  public function getPicaticApi() {
    return $this->picaticApiInstance;
  }

  public function apiUrl($path) {
    return sprintf("%s%s",$this->getPicaticApi()->getApiBaseUrl(),$path);
  }

  public function request($method, $url, $data=null, $params=null) {
    $method = strtoupper($method);
    $request = curl_init();

    $urlParsed = parse_url($this->apiUrl($url));

    $urlParsed['query'] = $params;

    $body = null;
    if ( is_array($data) && !empty($data) ) {
      $body = json_encode($data, JSON_FORCE_OBJECT);
    } elseif ( is_array($data) && empty($data) ) {
      $body = json_encode(new Object(), JSON_FORCE_OBJECT);
    } else {
      $body = $data;
    }

    // set headers
    $headers = array(
      'Content-Type: application/json'
    );
    if ($this->getPicaticApi()->getApiKey() != null) {
      $headers[] = sprintf('X-Picatic-Access-Key: %s', $this->getPicaticApi()->getApiKey());
    }

    // if we have data, this is a POST
    if ($method == 'POST') {
      curl_setopt($request, CURLOPT_POST, 1);
      curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($request, CURLOPT_POSTFIELDS, $body);
    } else if ($method == 'PUT') {
      curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'PUT');
    } else if ($method == 'DELETE') {
      curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    // build request
    $urlParsed['query'] = is_array($urlParsed['query']) ? http_build_query($urlParsed['query']) : $urlParsed['query'];
    curl_setopt($request, CURLOPT_URL, http_build_url($urlParsed));
    curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($request);

    $statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
    $errno = curl_errno($request);
    $error_message = curl_error($request);
    if ( $errno == 0 && $statusCode >= 200 && $statusCode <= 299 ) {
      curl_close($request);
      $result = json_decode($response, true);
      if ( $result !== false) {
        return $result;
      } else {
        return null; //@HACK throw exception
      }
    } else {
      curl_close($request);
      $error_result = @json_decode($response, true);
      $message = is_array($error_result) && array_key_exists('message', $error_result) ? $error_result['message'] : false;
      if ( $statusCode == 404 ) {
        throw new Picatic_Requestor_NotFound_Exception( $message ? $message : 'Request response code: 404');
      } else if ( $statusCode == 401 ) {
        throw new Picatic_Requestor_Unauthorized_Exception( $message ? $message : 'Unauthorized');
      } else if ( $statusCode == 403 ) {
        throw new Picatic_Requestor_Forbidden_Exception( $message ? $message : 'Forbidden' );
      } else if ( $statusCode == 422 ) {
        //@TODO Parse validation error into this Exception
        throw new Picatic_Requestor_Validation_Exception();
      } else if ( $statusCode == 500 ) {
        print_r($response);
        throw new Picatic_Requestor_Server_Exception( $message ? $message : 'Server Exception');
      } else {
        if ( $errno != 0 ) {
          $message = $error_message;
        } else {
          $message = sprintf('Unknown error: %s', $statusCode);
        }
        try {
          $result = json_decode($response,true);
          if (isset($result['message'])) {
            $message = $result['message'];
          }
        } catch (Exception $e) {
          $message = $e->getMessage();
        }
        throw new Picatic_Requestor_BadRequest_Exception($message);
      }
    }
  }
}
