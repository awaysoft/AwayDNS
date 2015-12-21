AwayDNS - A pure PHP DNS Server
==============

This is an Authoritative DNS Server written in pure PHP using swoole extension.
It will listen to DNS request on the default port (Default: port 53) and give answers about any donamin that it has DNS records for.
This class can be used to give DNS responses dynamically based on your pre-existing PHP code.

Support Record Types
====================

* A
* NS
* CNAME
* SOA
* PTR
* MX
* TXT
* AAAA
* OPT
* AXFR
* ANY

PHP Requirements
================

* `PHP 5.4+`
* PHP extension: `swoole`

Thanks
================
This project is based on yswery/PHP-DNS-SERVER

License
================
MIT License