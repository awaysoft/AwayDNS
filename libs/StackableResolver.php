<?php

namespace away\DNS;

use \Exception;

class StackableResolver {

    /**
     * @var array
     */
    protected $resolvers = [];

    private function init_resolvers(array $providers) {
        foreach ($providers as $name => $config) {
            $className = '\\away\\DNS\\Providers\\' . ucfirst($name) . 'Provider';
            $provider = new $className($config);
            array_push($this->resolvers, $provider);
        }
    }

    public function __construct(array $config) {
        if (!isset($config['providers'])) {
            throw new Exception('Config file has no providers info.');
        }
        $this->init_resolvers($config['providers']);
    }

    public function get_answer($question) 
    {
        foreach ($this->resolvers as $resolver) {
            $answer = $resolver->get_answer($question);
            if ($answer) {
                return $answer;
            }
        }
        
        return array();
    }

}
