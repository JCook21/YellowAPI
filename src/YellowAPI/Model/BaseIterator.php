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
            return $this->parseXml($result->xml());
        }

        return $this->parseJson($result->json());
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
        //@codingStandardsIgnoreStart
        $currentPage = (int) $xml->Summary->Paging->CurrentPage;
        $pageCount   = (int) $xml->Summary->Paging->PageCount;
        //@codingStandardsIgnoreEnd
        $this->setNextValues($pageCount, $currentPage);

        return $results;
    }

    /**
     * Method to parse JSON returned into an array.
     * @param stdClass $data
     *
     * @return array
     */
    protected function parseJson(stdClass $data)
    {
        $results     = $data->listings;
        $currentPage = $data->summary->currentPage;
        $pageCount   = $data->summary->pageCount;
        $this->setNextValues($pageCount, $currentPage);

        return $results;
    }

    /**
     * Method to decide if there are more results to fetch
     * @param int $pageCount
     * @param int $currentPage
     */
    protected function setNextValues($pageCount, $currentPage)
    {
        if ($pageCount === $currentPage) {
            $this->nextToken = false;

            return;
        }
        $this->nextToken = true;
        $this->pageCount++;
    }
}
