<?php

namespace away\DNS\Providers;

use \Exception;
use \away\DNS\AbstractStorageProvider;
use \away\DNS\RecordTypeEnum;

class HostsProvider extends AbstractStorageProvider {

    private $dns_records = [];
    private $refresh_time;
    private $DS_TTL;
    private $path;
    
    private function getSystemPath() {
        /* windows is in %WINDIR%\drivers\etc\hosts, others in /etc/hosts */
        if (strpos(PHP_OS, 'Win') !== FALSE) {
            return getenv('SystemRoot') . '\\System32\\drivers\\etc\\hosts';
        }
        
        return '/etc/hosts';
    }
    
    private function parseHostsString($data) {
        $strings = explode("\n", $data);
        $result = [];
        foreach($strings as $str) {
            /* search # and delete after that */
            $pos = strpos($str, '#');
            if ($pos !== FALSE) {
                $str = substr($str, 0, $pos);
            }
            $str = trim($str);
            if (!$str) {
                continue;
            }
            
            /* search first blank */
            $pos = strpos($str, ' ');
            if ($pos === FALSE) {
                $pos = strpos($str, "\t");
                if ($pos === FALSE) {
                    continue;
                }
            }
            $key = substr($str, 0, $pos);
            $value = trim(substr($str, $pos));
            if (!$value) {
                continue;
            }
            if (!isset($result[$value])) {
                $result[$value] = [];
            }
            if (filter_var($key, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                if (!isset($result[$value]['A'])) {
                    $result[$value]['A'] = [];
                }
                array_push($result[$value]['A'], $key);
            }
            if (filter_var($key, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                if (!isset($result[$value]['AAAA'])) {
                    $result[$value]['AAAA'] = [];
                }
                array_push($result[$value]['AAAA'], $key);
            }
        }
        return $result;
    }
    
    private function refreshData() {
        $data = file_get_contents($this->path);
        if (!$data) {
            return ;
        }
        $this->dns_records = $this->parseHostsString($data);
    }

    public function __construct($config)
    {
        $this->path = isset($config['path']) ? $config['path'] : $this->getSystemPath();
        $this->DS_TTL = isset($config['default_ttl']) ? $config['default_ttl'] : 300;
        $this->refresh_time = isset($config['refresh_time']) ? $config['refresh_time'] : 60;
        $this->refreshData();
    }

    public function get_answer($question)
    {
        $answer = array();
        $domain = trim($question[0]['qname'], '.');
        $type = RecordTypeEnum::get_name($question[0]['qtype']);

        if(isset($this->dns_records[$domain]) && isset($this->dns_records[$domain][$type])) {
            if(is_array($this->dns_records[$domain][$type])) {
                foreach($this->dns_records[$domain][$type] as $ip) {
                    $answer[] = array(
                        'name' => $question[0]['qname'], 
                        'class' => $question[0]['qclass'], 
                        'ttl' => $this->DS_TTL, 
                        'data' => array(
                            'type' => $question[0]['qtype'], 
                            'value' => $ip
                        )
                    );
                }
            }
        }

        return $answer;
    }

}
