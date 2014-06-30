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
    $request = curl_init();


    $urlParsed = parse_url($this->apiUrl($url));

    $urlParsed['query'] = $params;

    $body = null;
    if ( is_array($data) && !empty($data) ) {
      $body = json_encode($body, JSON_FORCE_OBJECT);
    } elseif ( is_array($data) && empty($data) ) {
      $body = json_encode(new Object());
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
    if ($data != null) {
      curl_setopt($request, CURLOPT_POST, 1);
      curl_setopt($request, CURLOPT_POSTFIELDS, $body);
    }

    // build request
    $urlParsed['query'] = is_array($urlParsed['query']) ? http_build_query($urlParsed['query']) : $urlParsed['query'];
    curl_setopt($request, CURLOPT_URL, http_build_url($urlParsed));
    curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($request);

    $statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);

    if ( curl_errno($request) == 0) {
      $result = json_decode($response,true);
      if ( $result ) {
        curl_close($request);
        return $result;
      } else {
        curl_close($request);
        return null; //@HACK throw exception
      }
    } else {
      if ( $statusCode == 404) {
        curl_close($request);
        throw new Picatic_Requestor_NotFound_Exception('Request response code: 404');
      } else {
        $message = sprintf('Unknown error: %s', $statusCode);
        try {
          $result = json_decode($response,true);
          if (isset($result['message'])) {
            $message = $result['message'];
          }
        } catch (Exception $e) {
          $message = $e->getMessage();
        }
        curl_close($request);
        throw new Picatic_Requestor_BadRequest_Exception($message);
      }
    }
  }
}
