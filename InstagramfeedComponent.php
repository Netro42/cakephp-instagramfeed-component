<?php

App::uses('Component', 'Controller');

/**
 * Instagramfeed 
 *
 * @package App.Controller.Component
 */
class InstagramfeedComponent extends Component
{
    public $endpoint = 'https://www.instagram.com/%s/';
    public $format = 'json';
    public $errors = array();

    /**
     * Creates url representing endpoint based on specific params
     *
     * @param string $uri Url which should be queried
     * @param string $format Format to use for response (json or xml)
     *
     * @author Pawel Drylo <pawel.drylo@netro42.com>
     * @return string
     */
    protected function getEndpoint($username, $format = 'json')
    {
        return sprintf($this->endpoint, $username);
    }

    /**
     * Fetches feed from instagram and returns as many items as you request with $limit
     *
     * @param string $username Instagram username 
     *
     * @author Toby Cox <toby.cox@netro42.com>
     * @return array
     */
    public function getFeedFromInstagram(string $endpoint, int $limit)
    {
        $returned = $this->request($endpoint);
        if (!$returned) {
            $this->errors[] = 'Instagram did not return any results';
            return false;
        }

        // TODO - put following line into the conditional clause if possible.
        $_returned = json_decode($returned, true);
        if (count($_returned['user']['media']['nodes']) > $limit) {
            $status = true;
            $more_available = $_returned['user']['page_info']['has_next_page'];
            $_returned = array_chunk($_returned['user']['media']['nodes'], $limit);
            $returned = json_encode(array('items' => $_returned[0], 'more_available' => $more_available, 'status' => $status));
        }
        unset($_returned);

        return $returned;
    }

    /**
     * Fetches feed from instagram
     *
     * @param string $username Instagram username 
     *
     * @author Toby Cox <toby.cox@netro42.com>
     * @return array
     */
    public function getFeed(string $username, int $limit)
    {
        if (!is_integer($limit)) {
            $this->errors[] = 'Limit must be a number';
        } elseif ($limit > 20) {
            $this->errors[] = 'Instagram have a limit of 20 for the maximum';
        }
        return (count($this->errors) ? json_encode(array('errors' => $this->errors)) : $this->getFeedFromInstagram($this->getEndpoint($username, $this->format), $limit));
    }

    /**
     * Request Instagram feed
     *
     * @param string $endpoint Endpoint
     *
     * @author Toby Cox <toby.cox@netro42.com>
     * @return stdClass|DOMDocument
     */
    public function request(string $endpoint)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
        	CURLOPT_URL => $endpoint . '?__a=1',
            CURLOPT_RETURNTRANSFER => 1,
        ));

        $response = curl_exec($ch);

        return $response;
    }
}
