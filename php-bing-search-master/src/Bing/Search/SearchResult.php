<?php

namespace Bing\Search;

use \Iterator;
use \Countable;
use \ArrayAccess;

/**
 * SearchResult Class
 * @author drahot
 */
class SearchResult implements ArrayAccess, Iterator, Countable
{

    /**
     * ResultObject Container
     * @var array
     */
    private $container;
    
    /**
     * Iterator
     * @var int
     */    
    private $index; 

    /**
     * Constructor
     * @param string $json 
     * @param string $searchType 
     * @return void
     */
    public function __construct($json, $searchType)
    {
        $jsonObject = @json_decode($json);
        if (!$jsonObject) {
            throw new \RuntimeException("Ivalid JSON Data!");
        }
        $classname = __NAMESPACE__.'\\'.ucfirst($searchType).'Result';
        $this->container = array();

        foreach ($jsonObject->d->results as $obj) {
            $resultObj = new $classname($obj);
            $this->container[] = $resultObj;
        }
    }

    /**
     * Rewind
     * @return type
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Valid
     * @return boolean
     */
    public function valid()
    {
        return ($this->index < count($this->container));
    }

    /**
     * Next
     * @return void
     */
    public function next() 
    {
        ++$this->index;
    }

    /**
     * Current Value
     * @return Object
     */
    public function current() 
    {
        return $this->container[$this->index];
    }

    /**
     * Return Key Value
     * @return index
     */
    public function key() 
    {
        return $this->index;
    }

    /**
     * Search Result Count
     * @return type
     */
    public function count()
    {
        return count($this->container);
    }

    /**
     * Get Value
     * @param mixed $offset 
     * @return ResultObject
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Check Exists Value
     * @param mixed $offset 
     * @return boolean
     */
    public function offsetExists($offset) 
    {
        return isset($this->container[$offset]);
    }

    /**
     * Set Value
     * @param mixed $offset 
     * @param mixed $value 
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unset
     * @param mixed $offset 
     * @return void
     */
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

}

/**
 * Web Result Class
 * @author drahot
 */
class WebResult 
{
    private $id;
    private $title;
    private $description;
    private $displayUrl;
    private $url;

    /**
     * Constructor
     * @param stdClass $webObj 
     * @return void
     */
    public function __construct($webObj)
    {
        $this->id = $webObj->ID;
        $this->title = $webObj->Title;
        $this->description = $webObj->Description;
        $this->displayUrl = $webObj->DisplayUrl;
        $this->url = $webObj->Url;
    }

    /**
     * Get ID
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Title
     * @return string
     */
    public function getTitle()    
    {
        return $this->title;        
    }
    
    /**
     * Get Description
     * @return string
     */    
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get DisplayUrl
     * @return string
     */    
    public function getDisplayUrl()
    {
        return $this->displayUrl;
    }

    /**
     * Get Url
     * @return string
     */    
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * toString Magic Method
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %s", $this->id, $this->url);
    }

}

/**
 * Image Result Class
 * @author drahot
 */
class ImageResult 
{

    private $id;
    private $title;
    private $mediaUrl;
    private $sourceUrl;
    private $displayUrl;
    private $width;
    private $height;
    private $fileSize;
    private $contentType;
    private $thumbnail;

    /**
     * Constructor
     * @param stdClass $imageObj 
     * @return void
     */
    public function __construct($imageObj)
    {
        $this->id = $imageObj->ID;
        $this->title = $imageObj->Title;
        $this->mediaUrl = $imageObj->MediaUrl;
        $this->sourceUrl = $imageObj->SourceUrl;
        $this->displayUrl = $imageObj->DisplayUrl;
        $this->width = $imageObj->Width;
        $this->height = $imageObj->Height;
        $this->fileSize = $imageObj->FileSize;
        $this->contentType = $imageObj->ContentType;
        $this->thumbnail = new Thumbnail($imageObj);
    }

    /**
     * Get Id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }    

    /**
     * Get Title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get MediaUrl
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->mediaUrl;
    }

    /**
     * Get SourceUrl
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * Get DisplayUrl
     * @return string
     */
    public function getDisplayUrl()
    {
        return $this->displayUrl;
    }

    /**
     * Get Image Width
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get Image Height
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get Image FileSize
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Get Content Type
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Get Thumbnail
     * @return Thumbnail
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * toString Magic Method
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %s", $this->id, $this->mediaUrl);
    }

}

/**
 * Thumbnail Class
 * @author drahot
 */
class Thumbnail 
{
    private $mediaUrl;
    private $contentType;
    private $fileSize;
    private $width;
    private $height;

    /**
     * Constructor
     * @param stdClass $imageObj 
     * @return void
     */
    public function __construct($imageObj)
    {
        $thumbnail = $imageObj->Thumbnail;
        $this->mediaUrl = $thumbnail->MediaUrl;
        $this->contentType = $thumbnail->ContentType;
        $this->fileSize = $thumbnail->FileSize;
        $this->width = $thumbnail->Width;
        $this->height = $thumbnail->Height;
    }

    /**
     * Get MediaUrl
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->mediaUrl;
    }

    /**
     * Get Content Type
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Get File size
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Get Image Width
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get Image Height
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * toString Magic Method
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %s", $this->id, $this->mediaUrl);
    }

}

/**
 * Video Result Class
 * @author drahot & barelon
 */
class VideoResult
{

    private $id;
    private $title;
    private $mediaUrl;
    private $displayUrl;
    private $runTime;
    private $thumbnail;

    /**
     * Constructor
     * @param stdClass $videoObj
     * @return void
     */
    public function __construct($videoObj)
    {
        $this->id = $videoObj->ID;
        $this->title = $videoObj->Title;
        $this->mediaUrl = $videoObj->MediaUrl;
        $this->displayUrl = $videoObj->DisplayUrl;
        $this->runTime = $videoObj->RunTime;
        if (isset($videoObj->Thumbnail)) {
            $this->thumbnail = new Thumbnail($videoObj);
        }
    }

    /**
     * Get Id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get MediaUrl
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->mediaUrl;
    }

    /**
     * Get DisplayUrl
     * @return string
     */
    public function getDisplayUrl()
    {
        return $this->displayUrl;
    }

    /**
     * Get Run Time
     * @return int
     */
    public function getRunTime()
    {
        return $this->runTime;
    }

    /**
     * Get Thumbnail
     * @return Thumbnail
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * toString Magic Method
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %s", $this->id, $this->mediaUrl);
    }

}

class CompositeResult
{

    private $web =  array();
    private $image = array();
    private $video = array();
    private $news = array();

    function __construct($obj)
    {
        $classnameWeb = __NAMESPACE__.'\\'.'WebResult';
        foreach ($obj->Web as $web) {
           $this->web[] = new $classnameWeb ($web);
        }

        $classnameImage = __NAMESPACE__.'\\'.'ImageResult';
        foreach ($obj->Image as $image) {
           $this->image[] = new $classnameImage ($image);
        }

        $classnameVideo = __NAMESPACE__.'\\'.'VideoResult';
        foreach ($obj->Video as $video) {
           $this->video[] = new $classnameVideo ($video);
        }

        $classnameNews = __NAMESPACE__.'\\'.'NewsResult';
        foreach ($obj->News as $news) {
           $this->news[] = new $classnameNews ($news);
        }
    }

    public function getWeb()
    {
        return $this->web;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function getNews()
    {
       return $this->news;
    }
}



/**
 * News Result Class
 * @author drahot & krlvisual
 */
class NewsResult
{

    private $id;
    private $title;
    private $url;
    private $description;
    private $date;
    private $source;
    private $breakingNews;

    /**
     * Constructor
     * @param stdClass $imageObj
     * @return void
     */
    public function __construct($imageObj)
    {
        $this->id = $imageObj->ID;
        if (property_exists($imageObj, 'Title')) {
            $this->title = $imageObj->Title;
        }
        if (property_exists($imageObj,'Url')) {
            $this->url = $imageObj->Url;
        }
        if (property_exists($imageObj, 'Snippet')) {
            $this->description = $imageObj->Snippet;
        }
        if (property_exists($imageObj,'Date')) {
            $this->date = $imageObj->Date;
        }
        if (property_exists($imageObj,'Source')) {
            $this->source = $imageObj->Source;
        }
        if (property_exists($imageObj, 'BreakingNews')) {
            $this->breakingNews = $imageObj->BreakingNews;
        }
    }

    /**
     * Get Id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Get SourceUrl
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getBreakingNews()
    {
        return $this->breakingNews;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }



    /**
     * toString Magic Method
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %s", $this->id, $this->sourceUrl);
    }

}



