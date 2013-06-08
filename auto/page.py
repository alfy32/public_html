#!/usr/bin/python

# Import modules for CGI handling 
import cgi, cgitb 
import socket

# needed to process the html
print "Content-type:text/html"
print ""

# show errors
cgitb.enable(display=1, logdir="log.txt")


HOST = '192.168.100.2'    # The remote host
PORT = 12345              # The same port as used by the server

def make_page(title, heading, form):
	print """
<!DOCTYPE html>
<html lang='en'>
	<head> 
		<title>%s</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<div class="main">
			<h1>%s</h1>
			<form action='page.py' method='post'>
				%s
			</form>
		</div>
	</body>
</html>
""" %(title,heading,form)

def send_socket(data):
	
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	try:
		s.connect((HOST, PORT))
		s.sendall(data)
		data = s.recv(1024)
		s.close()
		return str(data)
	except Exception, e:
		print "<h2>Failed to send: %s </h2>" % str(data)
		print '<h2>Server at %s is down.</h2>' % HOST
		print "<h2>Error: %s</h2>" % e
		exit(0)


# Create instance of FieldStorage 
# Gets the POST form data
form = cgi.FieldStorage() 
# to get the form info
#	form.getvalue('key')
#	form.getlist('key')
# this checks if 'string' value was posted
# 	if 'string' in form: 

# cgi.test()
# cgi.print_form(form)
# cgi.escape(value)


def main_page():
        make_page("Status", "Raspberry Pi",
                  "<input type='submit' name='lights' value='Set Lights' class='cmd_button' />")

def set_page(cmd, item=None, time=None):

	print '''
<!DOCTYPE html>
<html lang='en'>
	<head> 
		<title>Status</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="../style.css" />
	</head>
	<body>
		<div class='main'>
			<h1>Command List</h1>
'''
	if cmd is None:
		pass
	elif time is None and cmd is 'SET':
		cmd_page(cmd+' '+item)
	elif time is not None and time.isdigit():
		cmd_page(cmd+' '+item+' '+time)
		
	print '''
			<form action='page.py' method='post'>
				<input type='submit' name='SET' value='KIT_lights 0' class='cmd_button' />
				<input type='submit' name='SET' value='KIT_lights 1' class='cmd_button' />
				<input type='submit' name='SET' value='KIT_lights 2' class='cmd_button' />
				<input type='text' name='seconds' placeholder='seconds to pulse' class='pulse' />
				<input type='submit' name='PULSE' value='KIT_lights' class='cmd_button' />
			</form>
		</div>
	</body>
</html>
'''	

def status_page():	
	print """
<!DOCTYPE html>
<html lang='en'>
	<head> 
		<title>Status</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="../style.css" />
	</head>
	<body>
		<h1>Raspberry Pi's Status</h1>
		
"""

	recieved = send_socket("GET KIT_lights")
	print "<h3>KIT light Status: %s</h3>" % (recieved)

	recieved = send_socket("GET LR_lights")
	print "<h3>LR light Status: %s</h3>" % (recieved)

	recieved = send_socket("GET FR_lights")
	print "<h3>FR light Status: %s</h3>" % (recieved)

	recieved = send_socket("GET EXT_lights")
	print "<h3>EXT light Status: %s</h3>" % (recieved)

		
	print '''
		<p>HOST</p>
		</body>
	</html>
	'''
def cmd_page(cmd):
	print "<h2>Sending:", cmd, "...</h2>"
	recieved = send_socket(cmd)
	print "<h2>%s says: %s</h2>" % (HOST, recieved)
	
if 'status' in form:
	status_page()
	
elif 'cmd' in form:
	cmd_page(form.getvalue('cmd'))
elif 'lights' in form:
	set_page(None);
elif 'SET' in form:
	set_page('SET', form.getvalue('SET'))
elif 'PULSE' in form:
	set_page('PULSE', form.getvalue('PULSE'), form.getvalue('seconds'))
else:
	main_page()
