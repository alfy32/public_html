#!/usr/bin/perl

use strict;
use warnings;

print "Content-type: text/html \n\n";

print '

<!DOCTYPE html>
<html lang="en">
	<head>
		<style>
		body {
		background-color: white;
		}

		table { 
		border:1px solid black;
		border-collapse:collapse;
		}

		td, th {
		border:1px solid black;
		padding:5px;
		}
		</style>
	</head>
	<body>
';

# PERL MODULE WE WILL BE USING
use Mysql;

# MySQL CONFIG VARIABLES
my $host = "localhost";
my $database = "alan_home";
my $tablename = "cron_jobs";
my $user = "alan_alan";
my $pw = "ze3^717?0F-e";

# PERL MYSQL CONNECT
my $connect = Mysql->connect($host, $database, $user, $pw);

# SELECT DB
$connect->selectdb($database);

# LISTTABLES()
my @tables = $connect->listtables;

# PRINT EACH TABLE NAME
@tables = $connect->listtables;
foreach my $table (@tables) {
	print "Table: $table<br />";
}

# DEFINE A MySQL QUERY
my $myquery = "SELECT * FROM $tablename";

# EXECUTE THE QUERY
my $execute = $connect->query($myquery);

# FETCHROW ARRAY
print "<table>";
while (my @results = $execute->fetchrow()) {
	print "<tr>";
	foreach my $value (@results) {
		print "<td>",$value,"</td>";
	}
	print "</tr>";
}
print "</table>";

print '
	</body>
</html>
';