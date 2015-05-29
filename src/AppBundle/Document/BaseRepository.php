<?php
namespace AppBundle\Document;

trait BaseRepository
{
    /**
     * Getting either the option passed in or the default
     *
     * @param array $options
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getOption($options, $key, $default = null)
    {
        return isset($options[$key]) ? $options[$key] : $default;
    }
}