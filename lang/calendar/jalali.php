<?php 
/**
 * @فارسی : توابع زمان و تاریخ هجری شمسی (جلالی) در پی اچ پی
 * @name: Hijri_Shamsi,Solar(Jalali) Date and Time Functions
 * @Author : Reza Gholampanahi & WebSite : http://jdf.scr.ir
 * @License: GNU/LGPL _ Open Source & Free : [all functions]
 * @Version: 2.76 =>[ 1399/11/28 = 1442/07/04 = 2021/02/16 ]
 */
function tr_num($str, $mod = 'en', $mf = '٫') {
  $num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
  $key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
  return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
}
function gregorian_to_jalali($gy, $gm, $gd, $mod = '') {
   list($gy, $gm, $gd) = explode('_', tr_num($gy . '_' . $gm . '_' . $gd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
  $g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
  $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
  $days = 355666 + (365 * $gy) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100)) + ((int) (($gy2 + 399) / 400)) + $gd + $g_d_m[$gm - 1];
  $jy = -1595 + (33 * ((int) ($days / 12053)));
  $days %= 12053;
  $jy += 4 * ((int) ($days / 1461));
  $days %= 1461;
  if ($days > 365) {
    $jy += (int) (($days - 1) / 365);
    $days = ($days - 1) % 365;
  }
  if ($days < 186) {
    $jm = 1 + (int) ($days / 31);
    $jd = 1 + ($days % 31);
  } else {
    $jm = 7 + (int) (($days - 186) / 30);
    $jd = 1 + (($days - 186) % 30);
  }
  return ($mod == '') ? array($jy, $jm, $jd) : $jy . $mod . $jm . $mod . $jd;
}
?>
