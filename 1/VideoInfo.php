<?php

/**
 * Fetches and retains data returned by the get_video_info call.
 * The data returned by this call enables to figure out the flv
 * urls.
 * Usage:
 * $info = new YouTube_VideoInfo('HU3-hMAGzB8');
 * $flv = $info
 *          ->request()
 *          ->getFlvUrl();
 *
 * @version 0.1
 * @author Ifthikhan Nazeem <iftecan2000@gmail.com>
 */
class YouTube_VideoInfo
{
    /**
     * URI to fetch video info.
     *
     * @var string
     */
    protected static $_infoUri = 'http://youtube.com/get_video_info?video_id=%s';

    /**
     * Id of the YouTube video.
     *
     * @var string
     */
    private $_videoId = null;

    /**
     * Response received from a get_video_info call.
     *
     * @var string
     */
    private $_responseBody = null;

    /**
     * Class constructor.
     *
     * @param string $videoId   YouTube video id.
     * @return void
     */
    public function __construct($videoId)
    {
        if (empty($videoId)) {
            throw new Exception("Video id not given");
        }

        $this->_videoId = $videoId;
    }

    /**
     * Returns the flv url for a youtube video.
     *
     * @param string    $videoId    The id of the video.
     * @param int       $format     The quality of the video. Possible values
     *                              are (5, 34, 35, 18, 22, 37, 38, 43, 45, 0).
     *                              0 represents the full res url.
     * @return string
     */
    public function getFlvUrl($format = 18)
    {
        $urls = $this->getFlvUrls();

        if (!array_key_exists($format, $urls)) {
            throw new Exception("No flv url for the format '$format' exists.");
        }

        return $urls[$format];
    }

    /**
     * Retrieves the set of urls for all the formats.
     *
     * @param string $content   Content from youtube get_video_info call.
     * @return array            The urls to fetch flv sources. The keys of the array are
     *                          the fmt values reflecting the video quality and dimensions.
     *                          The link to the full res url will be within the zero index.
     */
    public function getFlvUrls()
    {
        // '#&fmt_url_map=[0-9]+\|(.*&id=[0-9A-Za-z]*)#i', // The order fmt_url_map and fmt_stream_map changes.
        if (!preg_match_all(
                '#&fmt_url_map=[0-9]+\|(.*)&allow_ratings#i',
                $this->_responseBody,
                $matches
            )
        ) {
            throw new Exception('FLV urls not found in the info content');
        }

        list( , list($match)) = $matches;
        unset($matches);

        /**
         * The url for the mp4 file does not have a fmt value attached. When
         * exploded within the below loop the list construct looks for a second
         * value which results in an undefined offset notice. Concatenated to
         * avoid the notice as well as to determine the full-res url.
         */
        $match .= ',0';

        $urls = array();
        foreach (explode('|', $match) as $value) {
            list($url, $format) = explode(',', $value);
            $urls[trim($format)] = trim($url);
        }

        return $urls;
    }

    /**
     * Initiates an http request to the get_video_info resource.
     *
     * @return YouTube_VideoInfoResponse    For fluent interface.
     * @throws Exception If the reponse is empty or invalid.
     */
    public function request()
    {
        $content = $this->_fetch();

        $this->_validateResponse($content);

        $this->_responseBody = $content;

        return $this;
    }

    /**
     * Fetches the data
     *
     * @access protected
     * @return string
     */
    private function _fetch()
    {
        $contents = file_get_contents(
            sprintf(static::$_infoUri, $this->_videoId)
        );

        return urldecode($contents);
    }

    /**
     * Validates the response. Checks if the returned status is an error.
     *
     * @param string $content
     * @return void
     */
    protected function _validateResponse($content)
    {
        /**
         * @todo Include the error message returned in the response as the
         * exception message. This will give more clarity to the message.
         */
        if (preg_match('#status=error&#', $content, $matches)) {
            throw new Exception('Call to get_video_info failed with an error status.');
        }
    }
}
