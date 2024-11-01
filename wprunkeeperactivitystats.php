<?php
/*
Plugin Name: wprunkeeperactivitystats
Plugin URI: http://www.pweir.co.uk
Description: Displays the runkeeper data in a post by displaying [wprunkeeper activity="activityid"]
in the post.
Version: 1.0
Author: Peter Weir
Author URI: http://www.pweir.co.uk
*/

/*
Runkeeper-plugin (Wordpress Plugin)
Copyright (C) 2011 Peter Weir
Contact me at http://www.pweir.co.uk

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

function runkeeperposts_handler( $atts ) {
	extract( shortcode_atts( array(
		'activity' => '',
		'units' => '',
	), $atts ) );

	
	$activity =  $atts["activity"];
	$units =  $atts["units"];

	return $runkeeper_output = runkeeperdata($activity,$units);
}

//tell wordpress to register the Runkeeper-plugin shortcode
add_shortcode("wprunkeeper", "runkeeperposts_handler");

//Master Runkeeper function to scrape site and retrive data
function runkeeperdata($activity,$units)
{

	if ( $units == "km" ) {
			$distanceunits = "km";
			$averagepaceunits = "min/km";
			$averagespeedunits = "km/h";
			$climbunits = "m";
	}else{
			$distanceunits = "mi";
			$averagepaceunits = "min/mi";
			$averagespeedunits = "mph";
			$climbunits = "ft";
	}

$root = get_bloginfo(home);

$css = $root.'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/run.css';

echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"".$css."\" media=\"screen\"/>";

$file = file_get_contents('http://runkeeper.com/ajax/pointData?activityId='.$activity); 

$out = json_decode($file,true); 

//echo "<pre>";
//print_r($out);
//echo "</pre>";

//print_r ($out[feedData]);

print 
"


<div id=\"runkeeper\" class=\"".$activity."\">
<div id=\"activityStats\" class=\"\">
<div id=\"activityStatsReflection\"></div>
<div id=\"statsDistance\" class=\"activityStatsItem\">

		<div class=\"labelText\">Distance</div>
		<div class=\"mainText\">".$out[statsDistance]."</div>
		<div class=\"unitText\">".$distanceunits."</div>
	</div>	
	<div class=\"activityStatsDivider\"></div>
<div id=\"statsDuration\" class=\"activityStatsItem\">

		<div class=\"labelText\">Duration</div>
		<div class=\"mainText\">".$out[statsDuration]."</div>
		<div class=\"unitText\">h:m:s</div>    
</div>
<div class=\"activityStatsDivider\"></div>
<div id=\"statsPace\" class=\"activityStatsItem\">

		<div class=\"labelText\">Avg. Pace</div>
		<div class=\"mainText\">".$out[statsPace]."</div>
		<div class=\"unitText\">".$averagepaceunits."</div>    
</div>
<div class=\"activityStatsDivider\"></div>
<div id=\"statsSpeed\" class=\"activityStatsItem\">

		<div class=\"labelText\">Avg.Speed</div>
		<div class=\"mainText\">".$out[statsSpeed]."</div>
		<div class=\"unitText\">".$averagespeedunits."</div>    
</div>
<div class=\"activityStatsDivider\"></div>
<div id=\"statsCalories\" class=\"activityStatsItem\">

		<div class=\"labelText\">Burned</div>
		<div class=\"mainText\">".$out[statsCalories]."</div>
		<div class=\"unitText\">calories</div>    
</div>
<div class=\"activityStatsDivider\"></div>
<div id=\"statsElevation\" class=\"activityStatsItem\">

		<div class=\"labelText\">Climb</div>
		<div class=\"mainText\">".$out[statsElevation]."</div>
		<div class=\"unitText\">".$climbunits."</div>    
</div>
<div class=\"activityStatsDivider\"></div>
<div id=\"statsHeartRate\" class=\"activityStatsItem\">

		<div class=\"labelText\">".$label7."</div>
		<div class=\"mainText\">".$main7."</div>
		<div class=\"unitText\">".$unit7."</div>    
</div>
</div>

</div>";

return $retval;
}


?>