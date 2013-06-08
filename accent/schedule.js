

////////////// DATE HEADER /////////////////////////////////////////////
function click_header_date(date)
{
	var header_date = document.getElementById('header_date');
	var change_date = document.getElementById('change_date');
	
	header_date.style.display = 'none';
	change_date.style.display = 'block';
	change_date.focus();
	change_date.onblur = function () {
		header_date.style.display = 'block';
		change_date.style.display = 'none';
	};
}

function date_keypress(date_input, event) 
{
	key = String.fromCharCode(event.keyCode);
	if(event.keyCode == 13)
		document.getElementById("submit_date").click();
}

function date_changed(date_input, event) 
{
	document.getElementById("submit_date").click();
}
///////////////////////////////////////////////////////////////////////

///////// NEW APPOINTMENT /////////////////////////////////////////////
function new_appointment(p,time,employee_id)
{	
	p.style.display = 'none';
	
	var form = p.parentNode;
	var client_input = form.getElementsByTagName("input")[0];
	client_input.style.display = 'block';
	client_input.focus();
	client_input.onblur = function() {
		p.style.display = 'block';
		client_input.style.display = 'none';
	};
}
////////////////////////////////////////////////////////////////////////

/////////// EDIT APPOINTMENT ///////////////////////////////////////////
function edit_appointment(appointment_id, client, employee_id, 
							date, start_time, end_time)
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
	
	var width = window.innerWidth - edit_form_div.offsetWidth;
	var height = window.innerHeight - edit_form_div.offsetHeight;
	
	//edit_form_div.style.left = width/2 + 'px';
	//edit_form_div.style.top = height/2 + 'px';
	
	edit_cover.onclick = function() {
		edit_cancel();
	}
}
function edit_cancel()
{	
	var edit_cover = document.getElementById("edit_cover");
	edit_cover.style.display = 'none';
	
	var edit_form_div = document.getElementById("edit_form_div");
	edit_form_div.style.display = 'none';
	
	var edit_form = document.getElementById("edit_form");
	var p = edit_form.getElementsByTagName("p")[0];
	if(p)
		edit_form.removeChild(p);
}

function set_edit() 
{
	var edit_cover = document.getElementById("edit_cover");
	var edit_form_div = document.getElementById("edit_form_div");
	
	var width = window.innerWidth - edit_form_div.offsetWidth;
	var height = window.innerHeight - edit_form_div.offsetHeight;
	
	//edit_form_div.style.left = width/2 + 'px';
	//edit_form_div.style.top = height/2 + 'px';
	
	//edit_cover.style.width = document.body.innerWidth;
	//edit_cover.style.height = document.body.innerHeight;
}
window.onload = function() {
	set_edit();
}
window.onresize = function() {
	set_edit();
}
/////////////////////////////////////////////////////////////////////////

///////// CHANGE EMPLOYEE /////////////////////////////////////////////
function change_employee(p)
{	
	p.style.display = 'none';
	
	var form = p.parentNode;
	var select = form.getElementsByTagName("select")[0];
	select.style.display = 'block';
	select.focus();
	select.onblur = function() {
		p.style.display = 'block';
		select.style.display = 'none';
	};
}

function employee_changed(select)
{	
	var form = select.parentNode;
	var inputs = form.getElementsByTagName("input");
	var submit = inputs[inputs.length-1];
	submit.click();
}
////////////////////////////////////////////////////////////////////////