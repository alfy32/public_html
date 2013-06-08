<?php
	$str = '<h1>T1</h1>Lorem ipsum.<h1>T2</h1>The quick red fox...<h1>T3</h1>... jumps over the lazy brown FROG';
	//$lib = 'lib.html';
	$lib = file_get_contents('https://library.usu.edu/study_rooms/numrooms.php');
	$DOM = new DOMDocument;
	//$DOM->loadHTML($str);
	$DOM->loadHTML($lib);

	$tables = $DOM->getElementsByTagName('table');
	//$hash = array();
	
	class Reservation 
	{
		public $room;
		public $level;
		public $capacity;
		public $equipment;
		public $person;
		public $day;
		public $month;
		public $year;
		public $start_time;
		public $duration;
		
		public function __toString(){
			return  "Room: " . $this->room . " Floor: " . $this->level . " Capacity: " . $this->capacity . " Equipment: " . $this->equipment . "<br>" . 
					"Name: " . $this->person . "<br>" . 
					"Date: " . $this->month . " " . $this->day . ", " . $this->year . "<br>" .
					$this->start_time . " Duration: " . $this->duration . "<br>";
		}
	}
	
	$res = new Reservation();

 	//loop through the tables. There is one per room	
	foreach($tables as $table)
	{		
		//loop through the rows of the table. 
		//There is one that is the header with room info and 
		//the rest are reservation info
		foreach($table->childNodes as $row)
		{
			$col_count = 0;
			
			//loop throught the columns on the row
			foreach($row->childNodes as $col)
			{
				if($col->nodeName == 'th')
				{		
					global $res;
					
					//string format:  "Room: ###,  Floor: #,  Capacity: ######,  Equipment: ·##"
					//example         "Room: 107,  Floor: 1,  Capacity: 4 to 6,  Equipment: ·PC"
					
					//split by comma
					$data = explode(",", $col->nodeValue);
					
					$room = $data[0];
					$floor = $data[1];
					$capacity = $data[2];
					$equipment = $data[3];
					
					$room = explode(" ", $room);
					$floor = explode(" ", $floor);
					$capacity = explode(":", $capacity);
					$equipment = explode(":", $equipment);
					
					$res->room = trim($room[1]);
					$res->level = trim($floor[1]);
					$res->capacity = trim($capacity[1]);
					$res->equipment = trim($equipment[1]);
					
					echo "Here is the reservation after adding the room info:<br>" . $res . "<br>";
				}
				else
				{	
					global $res;
						
					if($col_count == 0)
					{
						global $cur;
						echo "Name: " . $col->nodeValue . "<br>";
						$cur->person = $col->nodeValue;
					}
					else if($col_count == 1)
					{
						global $cur;
						
						$data = explode("-", $col->nodeValue);
						$date = explode(" ", $data[0]);
						echo "Day: " . $date[0] . "<br>";
						echo "Month: " . $date[1] . "<br>";
						echo "Year: " . $date[2] . "<br>";
						
						$time = explode(" ", trim($data[1]));
						echo "Start Time: " . $time[0] . "<br>";
						echo "AM/PM: " . $time[1] . "<br>";					
						
						$cur->day = $date[0];
						$cur->month = $date[1];
						$cur->year = $date[2];
						$cur->start_time = $time[0] . " " . $time[1];
						
						//echo $cur;
						
						//hash[$cur->day][$cur->start_time] = true;
					}
					else if($col_count == 2)
					{
						global $cur;

						$duration = explode(" ", $col->nodeValue);
						echo "Duration: " . $duration[0] . " Units: " . $duration[1] . "<br>";
						$cur->duration = $duration[0];						
						
							
					}
					$col_count=$col_count+1;
					
				//	echo $cur;
				}
				//echo "Col:<br>";
				//echo $col->nodeValue;
			}
			echo "<br>";
		}
		echo "<br><br>";
	}
	//for ($i = 0; $i < $table->length; $i++)
		//echo "Item" . $i . ": " .$table->item(0)->childNodes->item(0)->nodeName . "<br>";
	
	//foreach($table->childNodes as $row)
	{
		//echo "Row start<br>";
		
		//foreach($row->ChildNodes as $col)
		{ 
			//echo "Col: " . $col->nodeName . "    ";
		}
		//echo "<br>";
	}
	
	
	
	
	//lists the reservations	
	
	//get all H1
	//$items = $DOM->getElementsByTagName('table');
	
	//for ($i = 0; $i < $items->length; $i++)
//		echo "Item" . $i . ": " .$items->item($i)->nodeValue . "<br>";

	//display all H1 text
//	for ($i = 0; $i < $items->length; $i=$i+3)
//		echo "Reservation " . $i/3 . ":" . 
//			"<ul>" .
//			"<li>   &#09;Name: " . $items->item($i)->nodeValue . "</li>" .
//			"<li>   Date: " . $items->item($i+1)->nodeValue . "</li>" .
//			"<li>   Length: " . $items->item($i+2)->nodeValue . "</li>" . "</ul><br/>";
?>
