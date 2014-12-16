<?php

namespace Gapi;

/**
 * Class GapiReportEntry
 * 
 * Storage for individual Gapi report entries
 *
 */
class GapiReportEntry
{
  private $metrics    = array();
  private $dimensions = array();
  
  public function __construct ($metrics, $dimesions)
  {
    $this->metrics    = $metrics;
    $this->dimensions = $dimesions;
  }
  
  /**
   * toString function to return the name of the result
   * this is a concatented string of the dimesions chosen
   * 
   * For example:
   * 'Firefox 3.0.10' from browser and browserVersion
   *
   * @return String
   */
  public function __toString ()
  {
    if (is_array($this->dimensions))
    {
      return implode(' ', $this->dimensions);
    }
    else 
    {
      return '';
    }
  }
  
  /**
   * Get an associative array of the dimesions
   * and the matching values for the current result
   *
   * @return Array
   */
  public function getDimensions ()
  {
    return $this->dimensions;
  }
  
  /**
   * Get an array of the metrics and the matchning
   * values for the current result
   *
   * @return Array
   */
  public function getMetrics ()
  {
    return $this->metrics;
  }
  
  /**
   * Call method to find a matching metric or dimension to return
   *
   * @param $name String name of function called
   * @param $parameters
   * @return String
   * @throws \InvalidArgumentException if not a valid metric or dimensions, or not a 'get' function
   */
  public function __call ($name, $parameters)
  {
    if (!preg_match('/^get/',$name))
    {
      throw new \InvalidArgumentException('No such function "' . $name . '"');
    }
    
    $name = preg_replace('/^get/','',$name);
    
    $metric_key = Gapi::array_key_exists_nc($name,$this->metrics);
    
    if ($metric_key)
    {
      return $this->metrics[$metric_key];
    }
    
    $dimension_key = Gapi::array_key_exists_nc($name,$this->dimensions);
    
    if ($dimension_key)
    {
      return $this->dimensions[$dimension_key];
    }

    throw new \InvalidArgumentException('No valid metric or dimesion called "' . $name . '"');
  }
}
