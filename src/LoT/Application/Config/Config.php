<?php
namespace LoT\Application\Config;

class Config
{
    /** @var array */
    private $data;
    
    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

    /**
     * @param array $data
     */
    public function merge(array $data)
    {
        foreach ($data as $key => $value) {
            if (! isset($this->data[$key])) {
                $this->data[$key] = $value;
                continue;
            }
            
            $this->data[$key] = array_merge($this->data[$key], $value);
        }
    }
    
    /**
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }
}
