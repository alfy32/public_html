

////////////// DATE HEADER /////////////////////////////////////////////
function click_header_date(date)
{
	var header_date = document.getElementById('header_date');
	var change_date = document.getElementById('change_date');
	var submit_date = document.createElement('input');
	var date_center = header_date.parentNode;
	
	submit_date.id = 'submit_date';
	submit_date.type = 'submit';
	submit_date.name = 'submit';
	submit_date.value = 'Change Date';
	
	date_center.removeChild(header_date);
	date_center.appendChild(submit_date);
	change_date.type = 'date';
}

function date_changed(date_input) 
{
	document.getElementById("submit_date").click();
}
///////////////////////////////////////////////////////////////////////

///////// NEW APPOINTMENT /////////////////////////////////////////////
var td_old;

function new_appointment(td,time,employee_id)
{
	td_old = td.innerHTML;
	
	var input = document.createElement("input");
	input.id = "client";
	input.name = "client";
	input.setAttribute("class", "new_input");
	
	td.innerHTML = ""; 
	
	td.appendChild(input);
	input.focus();
	
	var time_input = document.createElement("input");
	time_input.type = "hidden";
	time_input.name = "start_time";
	time_input.value = time;
	td.appendChild(time_input);
	var employee_input = document.createElement("input");
	employee_input.type = "hidden";
	employee_input.name = "employee_id";
	employee_input.value = employee_id;
	td.appendChild(employee_input);
	
	var submit = document.createElement("input");
	submit.id = "create_submit";
	submit.name = "submit";
	submit.type = "submit";
	submit.value = "Create New Appointment";
	submit.setAttribute("class","create_submit");
	
	var form = document.getElementById("main_form");
	form.appendChild(submit);
	
	input.onblur = function() {
		td.innerHTML = td_old;
		form.removeChild(submit);
	};
}
////////////////////////////////////////////////////////////////////////

/////////// EDIT APPOINTMENT ///////////////////////////////////////////
function edit_appointment(appointment_id, client, employee_id, date, start_time, end_time)
{
	var edit_cover = document.getElementById("edit_cover");
	edit_cover.style.display = 'block';
	
	var edit_form_div = document.getElementById("edit_form_div");
	edit_form_div.style.display = 'block';
	
	var edit_appointment_id = document.getElementById("edit_appointment_id");
	edit_appointment_id.value= appointment_id;
	var edit_client = document.getElementById("edit_client");
	edit_client.value = client;
	var edit_employee = document.getElementById("edit_employee");
	for(var i = 0; i < edit_employee.options.length; i++)
	{
		if(edit_employee.options[i].value == employee_id)
			edit_employee.options[i].selected = true;
	}
	var edit_date = document.getElementById("edit_date");
	edit_date.value = date;
	var edit_start_time = document.getElementById("edit_start_time");
	edit_start_time.value = start_time;
	var edit_end_time = document.getElementById("edit_end_time");
	edit_end_time.value = end_time;
	
	
	cover.onclick = function() {
		edit_cover.style.display = 'none';
		edit_form_div.style.display = 'none';
	}
}
function edit_cancel()
{	
	var edit_cover = document.getElementById("edit_cover");
	edit_cover.style.display = 'none';
	
	var edit_form_div = document.getElementById("edit_form_div");
	edit_form_div.style.display = 'none';
	
	cover.onclick = function() {
		edit_cover.style.display = 'none';
		edit_form_div.style.display = 'none';
	}
}
/////////////////////////////////////////////////////////////////////////