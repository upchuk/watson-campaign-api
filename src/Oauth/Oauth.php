<?php

namespace Upchuk\WatsonCampaignApi\Oauth;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Upchuk\WatsonCampaignApi\Exception\IbmWatsonException;

/**
 * Handles Oauth authentication to generate an access token.
 */
class Oauth {

  /**
   * @var \Upchuk\WatsonCampaignApi\Oauth\OauthCredentials
   */
  private $credentials;

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  private $guzzle;

  /**
   * @var string
   */
  private $url;

  /**
   * Oauth constructor.
   *
   * @param \Upchuk\WatsonCampaignApi\Oauth\OauthCredentials $credentials
   * @param \GuzzleHttp\ClientInterface $guzzle
   * @param $url
   */
  public function __construct(OauthCredentials $credentials, ClientInterface $guzzle, $url) {
    $this->credentials = $credentials;
    $this->guzzle = $guzzle;
    $this->url = $url;
  }

  /**
   * Creates an access token based on the credentials.
   */
  public function generateAccessToken() {
    $payload = [
      'client_id' => $this->credentials->getClientId(),
      'client_secret' => $this->credentials->getClientSecret(),
      'refresh_token' => $this->credentials->getRefreshToken(),
      'grant_type' => 'refresh_token',
    ];

    try {
      $response = $this->guzzle->request('POST', $this->url . '/oauth/token', [
        'form_params' => $payload,
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded',
        ]
      ]);
    }
    catch (GuzzleException $exception) {
      throw new IbmWatsonException('The access token could not be generated. An exception has been thrown by the service.');
    }


    if ($response->getStatusCode() !== 200) {
      throw new IbmWatsonException('The access token could not be generated. The request did not return a 200 status code but ' . $response->getStatusCode());
    }

    $contents = $response->getBody()->getContents();
    $decoded = json_decode($contents);
    return isset($decoded->access_token) ? $decoded->access_token : FALSE;
  }
}