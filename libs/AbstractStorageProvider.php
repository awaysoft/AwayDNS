<?php

namespace away\DNS;

abstract class AbstractStorageProvider {

    abstract function get_answer($question);

}
