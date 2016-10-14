<?php
    class Utils {
        public static function json_encode_no_nulls($obj) {
            return preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode(Utils::convert_to_array($obj)));
        }

        public static function convert_to_array($obj, $recursive = true) {
            $arr = get_object_vars($obj);
                foreach($arr as $key => $val) {
                    if ($recursive) {
                        if (is_object($val)) {
                            $arr[$key] = Utils::convert_to_array($val, $recursive);
                        }
                    }
                    if (ctype_upper($key[0])) {
                        $newKey = strtolower($key[0]) . substr($key, 1);
                        $arr[$newKey] = $arr[$key];
                        unset($arr[$key]);
                    }
                }
            
            return $arr;
        }

        public static function json_decode_full(string $json, $obj) {
            $stdObj = json_decode($json);
            if ($stdObj)
                Utils::pick_and_place($obj, $stdObj);
            return $obj;
        }

        public static function pick_and_place($obj, $stdObj) {
            $className = get_class($obj);
            foreach ($stdObj as $key => $val) {
                if (property_exists($className, $key)) {
                    if (class_exists($key)) {
                        $obj->$key = new $key($val);
                        Utils::pick_and_place($obj->$key, $val);
                    } else { 
                        $obj->$key = $val;
                    } 
                } elseif (property_exists($className, ucwords($key))) {
                    $key = ucwords($key);
                    if (class_exists($key)) {
                        $obj->$key = new $key($val);
                        Utils::pick_and_place($obj->$key, $val);
                    } else { 
                        $obj->$key = $val;
                    } 
                }
            }
        }
    }
?>