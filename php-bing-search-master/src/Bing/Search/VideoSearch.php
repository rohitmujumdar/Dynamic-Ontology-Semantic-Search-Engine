<?php

namespace Bing\Search;

/**
 * Bing Video Search Class
 * @author drahot
 */
class VideoSearch extends Search
{
    
    /**
     * Video Filters
     * @var string
     */ 
    private $videoFilters;

    /**
     * Video Sort By
     * @var string
     */
    private $videoSortBy;

    /**
     * Get SearchType
     * @return string
     */
    public function getSearchType()
    {
        return 'Video';
    }

    /**
     * Get Video Filters
     * @return array
     */
    public function getVideoFilters()
    {
        return $this->videoFilters;
    }

    /**
     * Get Video Sort By
     * @return string
     */
    public function getVideoSortBy()
    {
        return $this->videoSortBy;
    }

    /**
     * Set Video Filters
     * @param array $videoFilters
     * @return VideoSearch
     */
    public function setVideoFilters(array $videoFilters)
    {
        $checkOptions = array('Duration', 'Aspect','Resolution');
        foreach (array_keys($videoFilters) as $option) {
            if (in_array($option, $checkOptions) === false) {
                throw new \InvalidArgumentException("Options is not valid!");
            }
        }
        $this->videoFilters = $videoFilters;
        return $this;
    }

    /**
     * Set Video Sort By
     * @param string $sortBy
     * @return type
     */
    public function setVideoSortBy($sortBy)
    {
        $this->videoSortBy = $sortBy;
        return $this;
    }

    /**
     * Add Additional Parameters
     * @param array $params 
     * @return array
     */
    protected function addAddtionalParameters(array $params)
    {
        if (count($this->videoFilters) > 0) {
            $videoFilters = '';
            array_walk($this->videoFilters, function ($value, $key) use (&$videoFilters) {
                if (strlen($videoFilters)) {
                    $videoFilters .= '+';
                }
                $videoFilters .= $key.':'.$value;
            });
            $params['VideoFilters'] = "'".$videoFilters."'";
        }
        if (!empty($this->videoSortBy)) {
            $params['VideoSortBy'] = "'".$this->videoSortBy."'";
        }
        return $params;
    }

}
