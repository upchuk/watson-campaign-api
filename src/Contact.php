<?php

namespace Upchuk\WatsonCampaignApi;

/**
 * Represents a contact in IBM Watson
 */
class Contact {

  /**
   * @var string
   */
  private $email;

  /**
   * @var array
   */
  private $preferences;

  /**
   * Contact constructor.
   *
   * @param $email
   * @param $preferences
   */
  public function __construct($email, $preferences) {
    $this->email = $email;
    $this->preferences = $preferences;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @return array
   */
  public function getPreferences() {
    return $this->preferences;
  }
}