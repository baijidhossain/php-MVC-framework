<?php

class Util
{

  public static function generateRandomString($length = 32)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  public static function DataReplacer($data, $text)
  {
    foreach ($data as $name => $value) {
      $text = str_ireplace("{{" . $name . "}}", $value, $text);
    }

    return $text;
  }

  public static function redirect($url)
  {
    header("Location: $url");
    die();
  }

  // back to previous page function
  public static function redirectBack($path = null)
  {
    $path = $path === null ? $_SERVER['HTTP_REFERER'] ?? APP_URL : APP_URL . $path;
    header("Location: $path");
    die();
  }

  public static function checkPostValues(array $values)
  {
    $validated = true;


    foreach ($values as $field) {
      if (
        !isset($_POST[$field])
        || (!is_array($_POST[$field]) && trim($_POST[$field]) == "")
        || (is_array($_POST[$field]) && empty($_POST[$field]))
      ) {

        $validated = false;
        break;
      }
    }



    return $validated;
  }

  public static function validateNumber($num)
  {
    if (!$num) {
      return false;
    }

    $num = ltrim(trim($num), "+88");
    $number = '88' . ltrim($num, "88");

    $ext = [
      "88017",
      "88013",
      "88016",
      "88015",
      "88018",
      "88019",
      "88014",
    ];
    if (
      is_numeric($number) && strlen($number) === 13
      && in_array(substr($number, 0, 5), $ext)
    ) {
      return $number;
    }

    return false;
  }

  public static function validateDate($date)
  {
    if (!DateTime::createFromFormat("Y-m-d", $date)) {
      return false;
    }

    return true;
  }

  public static function formatMoney($money)
  {
    $formatter = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
    $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
    return $formatter->parse($money);
  }
}
