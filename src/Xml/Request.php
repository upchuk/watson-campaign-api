<?php

namespace Upchuk\WatsonCampaignApi\Xml;

/**
 * Builds an XML request
 */
class Request {

  /**
   * @var string
   */
  private $body;

  /**
   * Request constructor.
   *
   * @param $body
   */
  public function __construct($body) {
    $this->body = $body;
  }

  /**
   * @return string
   */
  public function build() {
    $body = $this->body;
    return "
      <Envelope>
        <Body>
          $body
        </Body>
      </Envelope>
    ";
  }
}