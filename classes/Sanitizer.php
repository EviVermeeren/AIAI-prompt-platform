<?php
class Sanitizer
{
    public static function sanitize($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = self::sanitize($value);
            }
        } else {
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }
}
