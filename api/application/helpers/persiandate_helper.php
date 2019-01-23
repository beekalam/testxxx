<?php
require_once  APPPATH."third_party/jdf.php";
function div($a, $b){

    return (int) ($a / $b);
}

function convert_jalali_to_gregorian($date,$seperator='/'){
    list($year,$month,$day) = explode($seperator,$date);
    list($year,$month,$day)=JalaliToGregorian($year,$month,$day);
    if(strlen($day) == 1)
        $day = '0'.$day;
    if(strlen($month) == 1)
        $month = '0'.$month;
    return "{$year}-{$month}-{$day}";
}


function convert_gregorian_iso_to_jalali_iso($g_iso,$separator="-",$pad_with_zero=false){
    $year=substr($g_iso,0,4);
    $month=substr($g_iso,5,2);
    $day=substr($g_iso,8,2);
//    echo str_repeat(".",100);
//    print_r(compact("year","month","day"));
//    echo str_repeat(".",100);
    list($jyear, $jmonth, $jday) = gregorian_to_jalali($year,$month,$day);
    if($pad_with_zero){
        if($jmonth>=1 && $jmonth<=9)
            $jmonth = "0".$jmonth;
        if($jday>=1 && $jday<=9)
            $jday= "0".$jday;
    }
    return $jyear.$separator.$jmonth.$separator.$jday;
}

function JalaliToGregorian($year,$month,$day){

    $gDaysInMonth =array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $jDaysInMonth = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
    $jy=$year-979;
    $jm=$month-1;
    $jd=$day-1;
    $jDayNo=365*$jy + div($jy,33)*8 + div((($jy%33)+3),4);
    for ($i=0; $i < $jm; ++$i)
        $jDayNo += $jDaysInMonth[$i];
    $jDayNo +=$jd;
    $gDayNo=$jDayNo + 79;
    //146097=365*400 +400/4 - 400/100 +400/400
    $gy=1600+400*div($gDayNo,146097);
    $gDayNo = $gDayNo%146097;
    $leap=1;
    if($gDayNo >= 36525)
    {
        $gDayNo =$gDayNo-1;
        //36524 = 365*100 + 100/4 - 100/100
        $gy +=100* div($gDayNo,36524);
        $gDayNo=$gDayNo % 36524;

        if($gDayNo>=365)
            $gDayNo = $gDayNo+1;
        else
            $leap=0;
    }
    //1461 = 365*4 + 4/4
    $gy += 4*div($gDayNo,1461);
    $gDayNo %=1461;
    if($gDayNo>=366)
    {
        $leap=0;
        $gDayNo=$gDayNo-1;
        $gy += div($gDayNo,365);
        $gDayNo=$gDayNo %365;
    }
    $i=0;
    $tmp=0;
    while ($gDayNo>= ($gDaysInMonth[$i]+$tmp))
    {
        if($i==1 && $leap==1)
            $tmp=1;
        else
            $tmp=0;

        $gDayNo -= $gDaysInMonth[$i]+$tmp;
        $i=$i+1;
    }
    $gm=$i+1;
    $gd=$gDayNo+1;
    return array($gy, $gm, $gd);
}
