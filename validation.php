<?php
/**
 * Simple Form Validation Class
 *
 * PHP data validation and filtering class that makes validating any data easy.
 *
 * @author		Turan Karatuğ - <tkaratug@hotmail.com.tr>
 * @version 	v1.0.0
 * @copyright	18.02.2016
 */
 
class Validation
{
    public $errors      = [];

    protected $rules    = [];

    protected $data     = [];

    /**
     * Set validation Rules
     * @param $rules
     */
    public function set_rules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * Set Data to Validate
     * @param $data
     */
    public function set_data($data, $sanitize = false)
    {
        if($sanitize == true)
            $this->data = $this->sanitize($data);
        else
            $this->data = $data;
    }

    public function is_valid($data = [], $rules = [])
    {
        if( ! is_array($data) || ! is_array($rules) ) {
            return false;
        }

        if ( count($data) == 0 ) {
            $data   = $this->data;
        }

        if( count($rules) == 0) {
            $rules  = $this->rules;
        }

        foreach($rules as $key => $value) {
            $parts = explode('|', $value);

            foreach($parts as $part) {
                if(strpos($part, ',')) {
                    $group  = explode(',', $part);
                    $filter = $group[0];
                    $params = $group[1];
                    
                    if($filter == 'matches') {
                        if($this->matches($data[$key], $data[$params]) === false)
                            $this->errors[] = $this->get_error($filter . '_error', ['%s' => $key, '%t' => $params]);
                    } else {
                        if($this->$filter($data[$key], $params) === false)
                            $this->errors[] = $this->get_error($filter . '_error', ['%s' => $key, '%t' => $params]);
                    }                    
                } else {
                    if ($this->$part($data[$key]) === false)
                        $this->errors[] = $this->get_error($part . '_error', $key);
                }
            }
        }

        if(count($this->errors) > 0)
            return false;
        else
            return true;

    }

    /**
     * Get Validation Error
     * @param   $key    string
     * @param   $change string|array
     * @return  string
     */
    function get_error($key = '', $change = '')
    {
        if( ! is_string($key) ) {
            return false;
        }

        // Error Messages
        $message = [
            'required_error'    => '%s alanı boş bırakılamaz',
            'numeric_error'     => '%s alanı numeric tipinde olmalıdır',
            'email_error'       => 'E-posta adresi geçersiz',
            'min_len_error'     => '%s alanı minimum %t karakter olmalıdır',
            'max_len_error'     => '%s alanı maximum %t karakter olmalıdır',
            'exact_len_error'   => '%s alanı %t karakter olmalıdır',
            'alpha_error'       => '%s alanı alpha karakter olmalıdır',
            'alpha_num_error'   => '%s alanı alphanumeric karakter olmalıdır',
            'alpha_dash_error'  => '%s alanı alpha dash karakter olmalıdır',
            'alpha_space_error' => '%s alanı alpha space karakter olmalıdır',
            'integer_error'     => '%s alanı integer tipinde olmalıdır',
            'boolean_error'     => '%s alanı boolean tipinde olmalıdır',
            'float_error'       => '%s alanı float tipinde olmalıdır',
            'valid_url_error'   => '%s alanı url formatında olmalıdır',
            'valid_ip_error'    => '%s alanı geçerli bir ip olmalıdır',
            'valid_ipv4_error'  => '%s alanı geçerli bir ipv4 olmalıdır',
            'valid_ipv6_error'  => '%s alanı geçerli bir ipv6 olmalıdır',
            'valid_cc_error'    => '%s alanı geçerli bir kredi kartı numarası olmalıdır',
            'contains_error'    => '%s alanı "%t" içermelidir',
            'min_numeric_error' => '%s alanı minimum "%t" değeri alabilir',
            'max_numeric_error' => '%s alanı maximum "%t" değeri alabilir',
            'matches_error'     => '%s alanı %t alanı ile eşleşmiyor'
        ];

        if( array_key_exists($key, $message) ) {
            $str = $message[$key];

            // Change special words
            if( ! is_array($change) ) {
                if( ! empty($change) ) {
                    return str_replace('%s', $change, $str);
                } else {
                    return $str;
                }
            } else {
                if( ! empty($change) ) {
                    $keys = [];
                    $vals = [];

                    foreach($change as $key => $value) {
                        $keys[] = $key;
                        $vals[] = $value;
                    }

                    return str_replace($keys, $vals, $str);
                } else {
                    return $str;
                }
            }

        } else {
            return false;
        }
    }

    /**
     * Sanitizing Data
     * @param   string  $data
     * @return  string
     */
    public function sanitize($data)
    {
        if( ! is_array($data) ) {
            return filter_var(trim($data), FILTER_SANITIZE_STRING);
        } else {
            foreach($data as $key => $value) {
                $data[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }
            return $data;
        }

    }

    /**
     * Required Field Control
     * @param   string  $data
     * @return  bool
     */
    protected function required($data)
    {
        if(!empty($data) && !is_null($data) && $data !== '') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Numeric Field Control
     * @param   int  $data
     * @return  bool
     */
    protected function numeric($data)
    {
        if(is_int($data) || is_numeric($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Email Validation
     * @param   string  $email
     * @return  bool
     */
    protected function email($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Minimum Character Check
     * @param   string  $data
     * @param   int     $length
     * @return  bool
     */
    protected function min_len($data, $length)
    {
        if(strlen(trim($data)) < $length) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Maximum Character Check
     * @param   string  $data
     * @param   int     $length
     * @return  bool
     */
    protected function max_len($data, $length)
    {
        if(strlen(trim($data)) > $length) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Exact Length Check
     * @param   string  $data
     * @param   int     $length
     * @return  bool
     */
    protected function exact_len($data, $length)
    {
        if(strlen(trim($data)) == $length) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Alpha Character Validation
     * @param   string  $data
     * @return  bool
     */
    protected function alpha($data)
    {
        if( ! is_string($data) ) {
            return false;
        }

        return ctype_alpha($data);
    }

    /**
     * Alphanumeric Character Validation
     * @param   string  $data
     * @return  bool
     */
    protected function alpha_num($data)
    {
        return ctype_alnum($data);
    }

    /**
     * Alpha-dash Character Validation
     * @param   string  $data
     * @return  bool
     */
    protected function alpha_dash($data)
    {
        return (!preg_match("/^([-a-z0-9_-])+$/i", $data)) ? false : true;
    }

    /**
     * Alpha-space Character Validation
     * @param   string  $data
     * @return  bool
     */
    protected function alpha_space($data)
    {
        return (!preg_match("/^([A-Za-z0-9- ])+$/i", $data)) ? false : true;
    }

    /**
     * Integer Validation
     * @param   int  $data
     * @return  bool
     */
    protected function integer($data)
    {
        if( is_int($data) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Boolean Validation
     * @param   string  $data
     * @return  bool
     */
    protected function boolean($data)
    {
        if($data === true || $data === false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Float Validation
     * @param   string  $data
     * @return  bool
     */
    protected function float($data)
    {
        if( is_float($data) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * URL Validation
     * @param   string  $url
     * @return  bool
     */
    protected function valid_url($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * IP Validation
     * @param   string  $ip
     * @return  bool
     */
    protected function valid_ip($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * IPv4 Validation
     * @param   string  $ip
     * @return  bool
     */
    protected function valid_ipv4($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * IPv6 Validation
     * @param   string  $ip
     * @return  bool
     */
    protected function valid_ipv6($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Credit Card Validation
     * @param   string  $data
     * @return  bool
     */
    protected function valid_cc($data)
    {
        $number = preg_replace('/\D/', '', $data);

        if (function_exists('mb_strlen')) {
            $number_length = mb_strlen($number);
        } else {
            $number_length = strlen($number);
        }

        $parity = $number_length % 2;

        $total=0;

        for ($i=0; $i<$number_length; $i++) {
            $digit = $number[$i];

            if ($i % 2 == $parity) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $total += $digit;
        }

        return ($total % 10 == 0) ? true : false;
    }

    /**
     * Field must contain something
     * @param   string  $data
     * @param   string  $part
     * @return  bool
     */
    protected function contains($data, $part)
    {
        if(strpos($data, $part) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Minimum Value Validation
     * @param   int     $data
     * @param   int     $min
     * @return  bool
     */
    protected function min_numeric($data, $min)
    {
        if(is_numeric($data) && is_numeric($min) && $data >= $min) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Maximum Value Validation
     * @param   int     $data
     * @param   int     $max
     * @return  bool
     */
    protected function max_numeric($data, $max)
    {
        if(is_numeric($data) && is_numeric($max) && $data <= $max) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Matched Fields Validation
     * @param   string $data
     * @param   string $field
     * @return  bool
     */
    protected function matches($data, $field)
    {
        if($data == $field)
            return true;
        else
            return false;
    }

}

?>
