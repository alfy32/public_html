#!/usr/bin/python

import cgi, cgitb	# Import modules for CGI handling
import socket		# Used for the socket communication

## SHOWS ERRORS IN THE PAGE ##
cgitb.enable(display=1)

## CONSTANTS ##
HOST = "localhost" #'192.168.100.2'    # The remote host
PORT = 12345              # The same port as used by the server

## Tells the browser we are writing an HTML page ##
print "Content-type:text/html"
print ""

def send_socket(data):
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	try:
		s.connect((HOST, PORT))
		s.sendall(data)
		data = s.recv(1024)
		s.close()
		return str(data)
	except Exception, e:
		return '''
			Failed to send: %s <br/>
			Server at %s is down.<br/>
			Error: %s<br/>
		''' % (str(data), HOST, e)

## uses a standard template to make the page in HTML5 compliance
def make_page(title, body, message):
	print '''
	<!DOCTYPE html>
	<html lang='en'>
		<head> 
			<title>'''+title+'''</title>
			<meta charset="UTF-8">
			<link rel="stylesheet" type="text/css" href="style.css" />
		</head>
		<body>
			<form action='system.py' method='post'>
				<div class="main">
					<header>'''+title+'''</header>
					'''+body+'''
					<br/>
					<br/>
					<header>Messages </header>
					<div class="messages">
					<h2>'''+message+'''</h2>
					</div>
				</div>		
			</form>
			<br/>
		
		</body>
	</html>
	''';
	
## shows the main page ##
def main_page(message=None, value=None):
	if message is None: message = ''
	set = {}
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['kitchen'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['living'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['family'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['porch'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'OPENED' 
	elif result is 0: value = 'CLOSED'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['garageDoor'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['alarm'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['sprinklers'] = value
	
	result = send_socket("GET KIT_lights kitchen")
	if result is 1:  value = 'ON' 
	elif result is 0: value = 'OFF'
	else: value = 'OFFLINE'; message += "<br/>"+result 
	set['oven'] = value

	make_page("Pi Automation", '''
	
		
		<input type="submit" name="soundSystem" value="Sound System" />
		<input type="submit" name="thermostat" value="Thermostat" />
		<input type="submit" name="scheduler" value="Scheduler" />
		<input type="submit" name="cmd" value="Custom Command" />
	
		<div class="left_col">
			<header>Lights</header>
			<input type="submit" name="kitchen" value="Kitchen '''+set['kitchen']+'''" />
			<input type="submit" name="living" value="Living '''+set['living']+'''" />
			<input type="submit" name="family" value="Family '''+set['family']+'''" />
			<input type="submit" name="porch" value="Porch '''+set['porch']+'''" />	
						
		</div>
		<div class="right_col">
			<header>Systems</header>
			<input type="submit" name="garageDoor" value="Garage Door '''+set['garageDoor']+'''" />
			<input type="submit" name="alarm" value="Alarm System '''+set['alarm']+'''" />
			<input type="submit" name="sprinklers" value="Sprinklers '''+set['sprinklers']+'''" />
			<input type="submit" name="oven" value="Oven '''+set['oven']+'''" />
		</div>
				
		<div style="clear:both"></div>
		
	''' , message)

def sound_system(message=''):
	value = u''+message
	if value.isnumeric():
		message = ''
	result = send_socket("GET PLAY")
	if 'play' in result: 
		play = 'Play'
	else: 
		play = 'Pause'
		message += "<br>"+result
	result = send_socket("GET VOLUME")
	value = u"" + result
	if value.isnumeric(): 
		volume = result
		result = ''
	else: 
		volume = 'Fail'
		message += "<br>"+result
	make_page("Sound System", '''
		<h1>Current Volume: '''+volume+'''</h1>
		<h1>Current Status: '''+play+'''</h1>
		<input type="submit" name="volumeUp" value="Volume Up" />
		<input type="submit" name="volumeDown" value="Volume Down" />
		<input type="submit" name="play" value="'''+play+'''" />
		<input type="submit" name="cancel" value="Cancel/Close" /> 
	''', message)

def thermostat(message=''):
	value = u''+message
	if value.isnumeric():
		message = ''
	result = send_socket("GET TEMP")
	value = u"" + result
	if value.isnumeric(): 
		volume = result
		result = ''
	else: 
		volume = 'Fail'
		message += "<br>"+result
	make_page("Thermostat", '''
		<h1>Current Thermostat Temperature: '''+volume+'''</h1>
		<input type="submit" name="tempUp" value="Temp Up" />
		<input type="submit" name="tempDown" value="Temp Down" />
		<input type="submit" name="cancel" value="Cancel/Close" /> 
	''', message)

def scheduler():
	make_page("Scheduler", '''
		<iframe src="sql.pl" ></iframe>
		<input type="submit" name="cancel" value="Cancel/Close" /> 
	''', 'No Messages')

def cmd(command=None):
	if command is not None:
		result = send_socket(command)
	else: 
		result = ''
		command = ''
		
	make_page("Command Prompt", '''
		<input type="text" name="command" class="cmd" placeholder="Type Command here.." value="'''+command+'''" /> 
		<input type="submit" name="cmd" value="Submit Command" />
		<input type="submit" name="cancel" value="Cancel/Close" /> 
	''', result)
	

## TO GET THE POSTED DATA ## 
form = cgi.FieldStorage()

## PAGE FLOW OF CONTROL ## 
if 'status' in form:
	status_page()
elif 'soundSystem' in form:
	sound_system()	
elif 'volumeUp' in form:
	sound_system(send_socket("VOL UP"))	
elif 'volumeDown' in form:
	sound_system(send_socket("VOL UP"))	
elif 'play' in form:
	if(form.getvalue('play') == 'Play'):
		sound_system(send_socket("SET PLAY 1"))	
	else:
		sound_system(send_socket("SET PLAY 0"))
elif 'thermostat' in form:
	thermostat()	
elif 'tempUp' in form:
	thermostat(send_socket("TEMP UP"))	
elif 'tempDown' in form:
	thermostat(send_socket("TEMP UP"))	
elif 'scheduler' in form:
	scheduler()	
elif 'cmd' in form:
	if 'command' in form:  command = form.getvalue('command')
	else:  command = None
		
	cmd(command)
elif 'kitchen' in form:	
	if 'OFF' in form.getvalue('kitchen'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights kitchen "+value)
	
	main_page(result, value)
elif 'living' in form:	
	if 'OFF' in form.getvalue('living'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights living "+value)
	
	main_page(result)	
elif 'family' in form:	
	if 'OFF' in form.getvalue('family'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights family "+value)
	
	main_page(result)
elif 'porch' in form:	
	if 'OFF' in form.getvalue('porch'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights porch "+value)
	
	main_page(result)	
elif 'garageDoor' in form:	
	if 'CLOSED' in form.getvalue('garageDoor'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights garageDoor "+value)
	
	main_page(result)
elif 'alarm' in form:	
	if 'OFF' in form.getvalue('alarm'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights alarm "+value)
	
	main_page(result)	
elif 'sprinklers' in form:	
	if 'OFF' in form.getvalue('sprinklers'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights sprinklers "+value)
	
	main_page(result)
elif 'oven' in form:	
	if 'OFF' in form.getvalue('oven'):
		value = '1'
	else:
		value = '0'
		
	result = send_socket("SET KIT_lights oven "+value)
	
	main_page(result)	
else:
	main_page()
	
