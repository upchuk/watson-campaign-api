<?php

namespace Upchuk\WatsonCampaignApi\Oauth;

/**
 * Holds the Oauth credentials for authentication.
 */
class OauthCredentials {

  /**
   * @var string
   */
  private $client_id;

  /**
   * @var string
   */
  private $client_secret;

  /**
   * @var string
   */
  private $refresh_token;

  /**
   * Oauth constructor.
   *
   * @param $client_id
   * @param $client_secret
   * @param $refresh_token
   */
  public function __construct($client_id, $client_secret, $refresh_token) {
    $this->client_id = $client_id;
    $this->client_secret = $client_secret;
    $this->refresh_token = $refresh_token;
  }

  /**
   * @return string
   */
  public function getClientId() {
    return $this->client_id;
  }

  /**
   * @return string
   */
  public function getClientSecret() {
    return $this->client_secret;
  }

  /**
   * @return string
   */
  public function getRefreshToken() {
    return $this->refresh_token;
  }
}