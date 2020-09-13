<?php

namespace Bing\Search;

/**
 * Bing Composite Search Class
 * @author drahot & barelon
 * @todo add all the different parameters for the different services
 */
class CompositeSearch extends Search
{
    
    /**
     * Search services
     * @var string
     */ 
    private $searchServices;

    /**
     * Get SearchType
     * @return string
     */
    public function getSearchType()
    {
        return 'Composite';
    }

    /**
     * Get Services
     * @return array
     */
    public function getSearchServices()
    {
        return $this->searchServices;
    }

    /**
     * Set Search Services
     * @param array $services
     * @return CompositeSearch
     */
    public function setSearchServices(array $services)
    {
        $checkServices = array('Web', 'Image','Video','News','Spell','RelatedSearch');
        foreach ($services as $service) {
            if (in_array($service, $checkServices) === false) {
                throw new \InvalidArgumentException("Service is not valid!");
            }
        }
        $this->searchServices = $services;
        return $this;
    }

    /**
     * Add Additional Parameters
     * @param array $params 
     * @return array
     */
    protected function addAddtionalParameters(array $params)
    {
        if (count($this->searchServices) > 0) {
            $services = implode('+',$this->searchServices);
            $params['Sources'] = "'".$services."'";
        }
        return $params;
    }

}
