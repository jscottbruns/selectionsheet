<?php
/*////////////////////////////////////////////////////////////////////////////////////
Class: weather
Description: This class contains methods and processes for displaying a user's weather station
File Location: core/home/weather.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class weather {
	var $length = 3;
	var $partner_ID = "1010116790";
	var $license_key = "68cecfa2ed4ee125";
	var $loc_id; 
	var $image_size = "64x64"; 
	var $url = "http://xoap.weather.com/weather/local/";

	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: weather
	Description: This constructor is is used to update the weather_xml table, keeping the weather data
	current. If there is no current data found, this function makes a connection to weather.com's xml service
	and updates the DB accordingly.
	Arguments: $id, $days
	File Referrer: core/home/message_center.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function weather($id,$days=NULL) {
		global $db;
		
		$this->loc_id = $id;
		$this->length = $days;
		$datetime = date("Y-m-d h:i:s");
		$this->url .= $this->forecast_url.$this->loc_id."?cc=*&dayf=".$this->length."&prod=xoap&par=".$this->partner_ID."&key=".$this->license_key;
		
		$xml_url = md5($this->url);
		// Hours to keep data in db before being considered old
		$interval = 6;	
		$expires = $interval * 60 * 60;
		$expiredatetime = date("Y-m-d H:i:s", time() - $expires);
	
		// Delete expired records
		$db->query("DELETE FROM weather_xml 
				  WHERE last_updated < '$expiredatetime'");
		
		$result = $db->query("SELECT * 
							  FROM weather_xml 
							  WHERE xml_url = '$xml_url'"); 
		$row = $db->fetch_assoc($result);
		$time_diff = strtotime($datetime) - strtotime($row['last_updated']);

		//There is no data in the table, go out and get it
		if ($db->num_rows($result) == 0) {
			// Get XML Query Results from Weather.com
			$fp = fopen($this->url,"r");
			while (!feof ($fp))
				$xml .= fgets($fp, 4096);
				
			fclose ($fp);

			// Fire up the built-in XML parser
			$parser = xml_parser_create(  ); 
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			
			// Set tag names and values
			xml_parse_into_struct($parser,$xml,$this->values,$this->index); 
			
			// Close down XML parser
			xml_parser_free($parser);
			
			// Only inserts forecast feed, not search results feed, into db
			if ($this->loc_id) 
				$db->query("INSERT INTO weather_xml 
						 	 VALUES ('$xml_url', '$xml', '$datetime')");
		// Data in table, and it is within expiration period - do not load from weather.com and use cached copy instead.
		} else {
			$xml = $row['xml_data'];
	
			// Fire up the built-in XML parser
			$parser = xml_parser_create(  ); 
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	
			// Set tag names and values
			xml_parse_into_struct($parser,$xml,$this->values,$this->index); 
	
			// Close down XML parser
			xml_parser_free($parser);
		}
		$this->generate_output();
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: generate_output
	Description: This function takes the xml data stored to the class variables and parses it 
	to return the "current conditions" data
	Arguments: none
	File Referrer: core/home/message_center.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function generate_output() {
		$this->city = $this->values[$this->index[dnam][0]][value];
		$this->unit_temp = $this->values[$this->index[ut][0]][value];
		$this->unit_speed = $this->values[$this->index[us][0]][value];
		$this->unit_precip = $this->values[$this->index[up][0]][value];
		$this->unit_pressure = $this->values[$this->index[ur][0]][value];
		$this->sunrise = $this->values[$this->index[sunr][0]][value];
		$this->sunset = $this->values[$this->index[suns][0]][value];
		$this->timezone = $this->values[$this->index[tzone][0]][value];
		$this->last_update = $this->values[$this->index[lsup][0]][value];
		$this->curr_temp = $this->values[$this->index[tmp][0]][value];
		$this->curr_flik = $this->values[$this->index[flik][0]][value];
		$this->curr_text = $this->values[$this->index[t][0]][value];
		$this->curr_icon = $this->values[$this->index[icon][0]][value];
	}
}
?>