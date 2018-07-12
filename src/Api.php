<?php

namespace Upchuk\WatsonCampaignApi;

use Upchuk\WatsonCampaignApi\Exception\IbmWatsonException;
use Upchuk\WatsonCampaignApi\Xml\AddContact;
use Upchuk\WatsonCampaignApi\Xml\Request;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Makes the XML based API requests to IBM Watson
 */
class Api {

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  private $guzzle;

  /**
   * @var string
   */
  private $access_token;

  /**
   * @var
   */
  private $url;

  /**
   * Api constructor.
   *
   * @param \GuzzleHttp\ClientInterface $guzzle
   * @param $url
   * @param $access_token
   */
  public function __construct(ClientInterface $guzzle, $url, $access_token) {
    $this->guzzle = $guzzle;
    $this->access_token = $access_token;
    $this->url = $url;
  }

  /**
   * Makes a request to add a contact to the database.
   *
   * @param \Upchuk\WatsonCampaignApi\Xml\AddContact $addContact
   *
   * @return bool
   */
  public function addContact(AddContact $addContact) {
    $request = new Request($addContact->build());
    try {
      $response = $this->guzzle->request('POST', $this->url . '/XMLAPI', [
        'body' => $request->build(),
        'headers' => [
          'Content-Type' => 'text/xml;charset=utf-8',
          'Authorization' => 'Bearer ' . $this->access_token
        ]
      ]);
    }
    catch (GuzzleException $exception) {
      throw new IbmWatsonException($exception->getMessage(), $exception->getCode());
    }

    if ($response->getStatusCode() !== 200) {
      throw new IbmWatsonException('There was a problem with the request.');
    }

    $contents = $response->getBody()->getContents();
    $xml = simplexml_load_string($contents);
    if (!$xml instanceof \SimpleXMLElement) {
      throw new IbmWatsonException('The response did not amount to a valid XML string.');
    }

    if (!$xml->xpath('Body/RESULT/SUCCESS')) {
      throw new IbmWatsonException('The success key is missing from the response XML.');
    }

    if ($xml->xpath('Body/RESULT/SUCCESS')[0]->__toString() != 'TRUE') {
      throw new IbmWatsonException('The server returned a negative response to the request.');
    }

    return TRUE;
  }

}