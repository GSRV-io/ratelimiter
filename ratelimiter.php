<?php
/* Copyright (C) 2020 GSRV - All Rights Reserved
 * You may use, distribute and modify this code under the
 * terms of the GNU GPL v3 license.
 *
 * You should have received a copy of the GNU GPL v3 license with
 * this file. If not, please write to: william@gsrv.io
 */

function ratelimiter ($ip, $service = "general", $limit = 3, $time = "10 MINUTE") {
	global $conn; //mysql link to database
	$time = $conn->real_escape_string($time);
	$sql = "SELECT * from ratelimits where ip = ? and service = ? and `time` > utc_timestamp() - INTERVAL $time limit 1";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ss", $ip, $service);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows === 0) {
		//delete any old rows for this service
		$sql = "DELETE from ratelimits where ip = ? and service = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $ip, $service);
		$stmt->execute();

		$tries = 1;
		$sql = "INSERT into ratelimits (ip, service, requests, `time`) values (?, ?, 1, utc_timestamp())";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $ip, $service);
		$stmt->execute();
	}
	else {
		$row = $result->fetch_object();
		$tries = ((int) $row->requests) + 1;

		$sql = "UPDATE ratelimits set requests = ? where ip = ? and service = ? limit 1";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("iss", $tries, $ip, $service);
		$stmt->execute();
	}

	return $tries <= $limit;
}
