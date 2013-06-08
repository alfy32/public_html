$(document).ready(function(){
	$('th.item').click(function(){ tdClick(this, updateItem);});
	$('td.gross').click(function(){ tdClick(this, updateGross);});
	$('td.tare').click(function(){ tdClick(this, updateTare);});
	$('td.net').click(function(){ tdClick(this, updateNet);});
	$('td.calg').click(function(){ tdClick(this, updatecalg);});
	//not sure what it does
	//$('td.cal').click(function(){ tdClick(this, updateCal);});
});

// This will return the correct string from the item cell
function updateItem(tr, value){
	var item = $(tr).children('th.item');
	
	$(item).text(value);
}
function updateGross(tr, value){
	// uses the eval function to do math.
	var gross = eval(value);
	gross = Number(gross);
	if(isNaN(gross))
		gross = 0;
	
	// When the gross is updated the net and the cal must also be updated. 
	var tdGross = $(tr).children('td.gross');
	var tdTare = $(tr).children('td.tare');
	var tdNet = $(tr).children('td.net');
	var tdCalg = $(tr).children('td.calg');
	var tdCal = $(tr).children('td.cal');
	
	var tare = Number($(tdTare).text());
	var net = gross - tare;
	var calg = Number($(tdCalg).text());
	var cal = net * calg;
	
	//alert("Tare: " + tare + " net: " + net + " calg: " + calg + " cal: " + cal);

	$(tdNet).text(net.toFixed(2));
	$(tdCal).text(cal.toFixed(2));
	
	$(tdGross).text(gross.toFixed(2));
	
	calcTotals();
}
function updateTare(tr, value){
	// uses the eval function to do math.
	var tare = eval(value);
	tare = Number(tare);
	if(isNaN(tare))
		tare = 0;
	
	// When the gross is updated the net and the cal must also be updated. 
	var tdGross = $(tr).children('td.gross');
	var tdTare = $(tr).children('td.tare');
	var tdNet = $(tr).children('td.net');
	var tdCalg = $(tr).children('td.calg');
	var tdCal = $(tr).children('td.cal');
	
	var gross = Number($(tdGross).text());
	var net = gross - tare;
	var calg = Number($(tdCalg).text());
	var cal = net * calg;

	$(tdNet).text(net.toFixed(2));
	$(tdCal).text(cal.toFixed(2));
	
	$(tdTare).text(tare.toFixed(2));
	
	calcTotals();
}
function updateNet(tr, value){
	// uses the eval function to do math.
	var net = eval(value);
	net = Number(net);
	if(isNaN(net))
		net = 0;
		
	// When the gross is updated the net and the cal must also be updated. 
	var tdGross = $(tr).children('.gross');
	var tdTare = $(tr).children('.tare');
	var tdNet = $(tr).children('.net');
	var tdCalg = $(tr).children('.calg');
	var tdCal = $(tr).children('.cal');
	
	var tare = Number($(tdTare).text());
	var gross = net + tare;
	var calg = Number($(tdCalg).text());
	var cal = net * calg;

	$(tdGross).text(gross.toFixed(2));
	$(tdCal).text(cal.toFixed(2));
	
	$(tdNet).text(net.toFixed(2));
	
	calcTotals();
}
function updatecalg(tr, value){
	// uses the eval function to do math.
	var calg = eval(value);
	calg = Number(calg);
	if(isNaN(calg))
		calg = 0;
	
	// When the gross is updated the net and the cal must also be updated. 
	var tdNet = $(tr).children('.net');
	var tdCalg = $(tr).children('.calg');
	var tdCal = $(tr).children('.cal');
	
	var net = Number($(tdNet).text());
	var cal = net * calg;

	$(tdCal).text(cal.toFixed(2));
	
	$(tdCalg).text(calg.toFixed(2));
	
	calcTotals();
}
// Not sure what to do
//function updateCal(tr, value){}

// This is called when a cell from the WCCR table is clicked.
function tdClick(td, updateValue){
	// Get the current value
	var value = $(td).text();
	
	td.onclick = '';
	
	// Get the td dimensions to make the input fill it
	$(td).css('padding','0px');
	var width = $(td).width();
	var height = $(td).height();
	
	// Create an input for the user
	var input = document.createElement("input");
	input.setAttribute('class', 'input');
	$(input).val(value);
	$(input).width(width);
	$(input).height(height);
	if(td.getAttribute('class') == 'item') {
		input.setAttribute('list','calorieTable');
  
//		$.each(json, function(key, value){
//			if(value.item.search(/milk/i) != -1) {
//				document.write(value.item);
//				document.write('<br>');
//			}
//		});
	}
		
	// Clear the td and add the input
	$(td).empty();
	$(td).append(input);
	$(input).focus();
	
//	$(function() {
//		$( ".input" ).autocomplete({
//		  source: json,
//		  select: function( event, ui ) {
//			$("td.calg").text(ui.item.CalPerGram);
//		  }
//		});
//	  });
	
	// When the focus is lost or the user presses enter
	// remove the input and set back to regular td	
	function focusLost(){
		// get the input value
		value = $(input).val();
		
		// clear the td
		$(td).empty();
		
		// reset the padding
		$(td).css('padding','5px');
		
		// use the function passed in to know how to update the value
		updateValue($(td).parent() ,value); 
	}
	
	// When the user clicks away from the cell call the function
	$(input).blur(focusLost);
	
	// When the user hits enter
	$(input).keypress(function(event){
		// 13='Enter Key'
		if(event.keyCode == 13)
			focusLost();
	});
}

function calcTotals(){
	var cal = 0;
	var row = $(".wccr").children("tr");
	row = $(row).next('tr');
	for(var i = 2; i <= rows; i++) {
		nextCal = parseFloat($(row).children(".cal").text());
		if(!isNaN(nextCal))
			cal += nextCal;
		row = $(row).next('tr');
	}
	var budgeted = $(".dailyTotal").children(".budgeted");
	var totals = $(".dailyTotal").children(".totals");
	var balance = $(".dailyTotal").children(".balance");
	
	$(totals).text(cal.toFixed(2));
	$(balance).text( (Number($(budgeted).text()) - Number($(totals).text())).toFixed(2) );
}

var rows = 7;

function addRow(){
	rows++;
	$(".wccr").append("" +
		"<tr class='"+rows+"'>" +
			"<th class='item'></th>" +
			"<td class='gross'></td>" + 
			"<td class='tare'></td>" +
			"<td class='net'></td>" +
			"<td class='calg'></td>" +
			"<td class='cal'></td>" +
		"</tr>");
		
		
	$("td").click(function(){
		var td = $(this);
		var value = $(this).text();
		var input = document.createElement("input");
		input.setAttribute('class', 'input');
		$(input).val(value);
		$(td).css('padding','0px');
		$(input).width($(td).width());
		$(input).height($(td).height());
		$(td).empty();
		$(td).append(input);
		$(input).focus();
		
		function cellChanged(){
			$(td).empty();
			value = $(input).val();
			$(td).html(eval(value));
			$(td).css('padding','5px');
			updateRow( $(td).parent() );
			
			getTotals();
		}

		$(input).blur(cellChanged);
		$(input).keypress(function(event){
			if(event.keyCode == 13)
				cellChanged();
		});
	});
	$('th.item').click(function(){
		var td = $(this);
		var value = $(td).text();
		var input = document.createElement("input");
		input.setAttribute('class', 'input');
		$(input).val(value);
		$(td).css('padding','0px');
		$(input).width($(td).width());
		$(input).height($(td).height());
		$(td).empty();
		$(td).append(input);
		$(input).focus();
		
		function cellChanged(){
			$(td).empty();
			$(td).html( $(input).val() );
			$(td).css('padding','5px');
			
			getTotals();
		}

		$(input).blur(cellChanged);
		$(input).keypress(function(event){
			if(event.keyCode == 13)
				cellChanged();
		});
	});
}



function updateRow(tr) {
	var gross = $(tr).children('.gross');
	var tare = $(tr).children('.tare');
	var net = $(tr).children('.net');
	var calg = $(tr).children('.calg');
	var cal = $(tr).children('.cal');

	$(net).text( ($(gross).text() - $(tare).text()).toFixed(2) );
	$(cal).text( ($(net).text() * $(calg).text()).toFixed(2) );
}
