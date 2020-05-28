<?php 

namespace App\Traits;

trait XenHelper {
	public function timeConvert($num) {
		$hours = floor($num);
		$minutes = ($num - $hours) * 60;
		$rminutes = round($minutes);

		$time = $hours . " hr";
		if($rminutes) {
			$time .= " " . $rminutes . " min";
		}
		return $time;
	}

	public function timeConvertMinutesToHours($n) {
		$num = $n;
		$hours = ($num / 60);
		$rhours = floor($hours);
		$minutes = ($hours - $rhours) * 60;
		$rminutes = round($minutes);

		return $num . " minutes = " . $rhours . " hour(s) and " . $rminutes . " minute(s).";
	}
}