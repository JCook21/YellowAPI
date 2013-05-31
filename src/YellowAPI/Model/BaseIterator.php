<?php

namespace YellowAPI\Model;

use Guzzle\Service\Resource\ResourceIterator;
use SimpleXMLElement;
use stdClass;

/**
 * Class: BaseIterator
 * Base class to provide iterating over results from the yellow pages API.
 *
 * @package YellowAPI
 * @author  Jeremy Cook <jeremycook0@gmail.com>
 *
 * @see     Guzzle\Service\Resource\ResourceIterator
 * @abstract
 */
abstract class BaseIterator extends ResourceIterator
{
    /**
     * Variable to indicate the page to fetch
     *
     * @var int
     */
    protected $page = 1;

    /**
     * Method to send a request
     * @return array
     */
    protected function sendRequest()
    {
        $this->command->set('pg', $this->page);
        $result = $this->command->execute();
        if ($result->getContentType() === 'text/xml') {
            return $this->parseXml($response->xml());
        }

        return $this->parseJson($response->json());
    }

    /**
     * Method to parse xml returned into an array
     * @param SimpleXMLElement $xml
     *
     * @return array
     */
    protected function parseXml(SimpleXMLElement $xml)
    {
        $results = $xml->xpath('//Listings/Listing');
        $currentPage = (int) $xml->Summary->Paging->CurrentPage;
        $pageCount = (int) $xml->Summary->Paging->PageCount;
        switch ($currentPage === $pageCount) {
        case true:
            $this->nextToken = false;
            break;
        default:
            $this->nextToken = true;
            $this->page++;
        }
    }

    /**
     * Method to parse JSON returned into an array.
     * @param stdClass $data
     * 
     * @return array
     */
    protected function parseJson(stdClass $data)
    {
    }
}
