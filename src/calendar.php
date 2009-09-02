<?php
/**
 * 日历
 * @copyright (c) Emlog All Rights Reserved
 * @version emlog-3.3.0
 * $Id$
 */

require_once('init.php');

//建立日志时间写入数组
$query = $DB->query("SELECT date FROM ".DB_PREFIX."blog WHERE hide='n' and type='blog'");
while ($date = $DB->fetch_array($query))
{
	$logdate[] = date("Ymd",$date['date']);
}
//获取当前日期
$n_year  = date("Y",$localdate);
$n_year2 = date("Y",$localdate);
$n_month = date("m",$localdate);
$n_day   = date("d",$localdate);
$time    = date("Ymd",$localdate);
$year_month = date("Ym",$localdate);

if (isset($_GET['record']))
{
	$n_year = substr(intval($_GET['record']),0,4);
	$n_year2 = substr(intval($_GET['record']),0,4);
	$n_month = substr(intval($_GET['record']),4,2);
	$year_month = substr(intval($_GET['record']),0,6);
}

//年月跳转连接
$m  = $n_month - 1;
$mj = $n_month + 1;

$m  = ($m < 10) ? '0' . $m : $m;
$mj = ($mj < 10) ? '0' . $mj : $mj;

$year_up = $n_year;
$year_down = $n_year;

if ($mj > 12)
{
	$mj = '01';
	$year_up = $n_year + 1;
}
if ( $m < 1)
{
	$m = '12';
	$year_down = $n_year - 1;
}
$url = './calendar.php?record=' . ($n_year - 1) . $n_month;//上一年份
$url2 = './calendar.php?record=' . ($n_year + 1) . $n_month;//下一年份
$url3 = './calendar.php?record=' . $year_down . $m;//上一月份
$url4 = './calendar.php?record=' . $year_up . $mj;//下一月份

$calendar =
"<table class=\"calendartop\" cellspacing=\"0\">
<tr>
<td>
<a href=\"javascript:void(0);\" onclick=\"sendinfo('$url','calendar');\"> &laquo; </a>$n_year2<a href=\"javascript:void(0);\" onclick=\"sendinfo('$url2','calendar');\"> &raquo; </a>
</td>
<td>
<a href=\"javascript:void(0);\" onclick=\"sendinfo('$url3','calendar');\"> &laquo; </a>$n_month<a href=\"javascript:void(0);\" onclick=\"sendinfo('$url4','calendar');\"> &raquo; </a>
</td>
</tr>
</table>
<table class=\"calendar\" cellspacing=\"0\">
<tr>
    <td class=\"week\">{$lang['monday_short']}</td>
    <td class=\"week\">{$lang['tuesday_short']}</td>
    <td class=\"week\">{$lang['wednesday_short']}</td>
    <td class=\"week\">{$lang['thursday_short']}</td>
    <td class=\"week\">{$lang['friday_short']}</td>
    <td class=\"week\">{$lang['saturday_short']}</td>
    <td class=\"sun\">{$lang['sunday_short']}</td>
</tr>";

//获取给定年月的第一天是星期几
$week = @date("w",mktime(0,0,0,$n_month,1,$n_year));
//获取给定年月的天数
$lastday = @date("t",mktime(0,0,0,$n_month,1,$n_year));
//获取给定年月的最后一天是星期几
$lastweek = @date("w",mktime(0,0,0,$n_month,$lastday,$n_year));
if ( $week == 0)
{
	$week = 7;
}
$j = 1;
$w = 7;
$isend = false;
//外循环生成行
for ($i = 1;$i <= 6;$i++)
{
	if ($isend || ($i == 6 && $lastweek==0))
	{
		break;
	}
	$calendar .= '<tr>';
	//内循环生成列
	for($j ; $j <= $w; $j++)
	{
		if ($j < $week)
		{
			$calendar.= '<td>&nbsp;</td>';
		} elseif ( $j <= 7 ) {
			$r = $j - $week + 1;
			//如果该日有日志就显示url样式
			$n_time = $n_year . $n_month . '0' . $r;
			//有日志且为当天
			if (@in_array($n_time,$logdate) && $n_time == $time)
			{
				$calendar .= "<td class=\"day\"><a href=\"./?record=$n_time\">". $r .'</a></td>';
			} elseif (@in_array($n_time,$logdate)) {
				$calendar .= "<td class=\"day2\"><a href=\"./?record=$n_time\">". $r .'</a></td>';
			} elseif ($n_time == $time){
				$calendar .= '<td class="day">'. $r .'</td>';
			} else {
				$calendar.= '<td>'. $r .'</td>';
			}
		}else{
			$t = $j - ($week - 1);
			if ($t > $lastday)
			{
				$isend = true;
				$calendar .= '<td>&nbsp;</td>';
			} else {
				//如果该日有日志就显示url样式
				$t < 10 ? $n_time = $n_year . $n_month . '0' . $t : $n_time = $n_year . $n_month . $t;
				if (@in_array($n_time,$logdate) && $n_time == $time)
				{
					$calendar .= "<td class=\"day\"><a href=\"./?record=$n_time\">". $t .'</a></td>';
				} elseif(@in_array($n_time,$logdate)){
					$calendar .= "<td class=\"day2\"><a href=\"./?record=$n_time\">". $t .'</a></td>';
				} elseif($n_time == $time) {
					$calendar .= '<td class="day">'. $t .'</td>';
				} else {
					$calendar .= '<td>'.$t.'</td>';
				}
			}
		}
	}//内循环结束
	$calendar .= '</tr>';
	$w += 7;
}//外循环结束
$calendar .= '</table>';

echo $calendar;

?>