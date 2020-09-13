<?php
namespace Bing\Search;


/**
 * Bing News Search Class
 * @author drahot
 */
class NewsSearch extends Search
{
    /**
     * News Sort By
     * @var string
     */
    private $newsSortBy;

    /**
     * Get SearchType
     * @return string
     */
    public function getSearchType()
    {
        return 'News';
    }

    /**
     * Get News Sort By
     * @return string
     */
    public function getNewsSortBy()
    {
        return $this->newsSortBy;
    }


    /**
     * Set News Sort By
     * @param string $sortBy
     * @return type
     */
    public function setNewsSortBy($sortBy)
    {
        $this->newsSortBy = $sortBy;
        return $this;
    }

    /**
     * Add Additional Parameters
     * @param array $params
     * @return array
     */
    protected function addAddtionalParameters(array $params)
    {

        if (!empty($this->newsSortBy)) {
            $params['NewsSortBy'] = "'".$this->newsSortBy."'";
        }
        return $params;
    }

}
