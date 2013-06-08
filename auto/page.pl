#!/usr/bin/python

pageTemplate = '''
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Hello</title>
</head>
<body>
Hello, {person}!
</body>
</html>''' # NEW note '{person}' two lines up

def main():    # NEW
    person = input("Enter a name: ")  
    contents = pageTemplate.format(**locals())   
    browseLocal(contents) 