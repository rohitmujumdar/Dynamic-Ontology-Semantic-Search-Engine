db = MySQLdb.connect(unix_socket='/cloudsql/PROJECT-ID:INSTANCE-NAME', user='phil')

cursor = db.cursor()
cursor.execute('SHOW VARIABLES')
for r in cursor.fetchall():
  webapp2.RequestHandler.response.write('%s\n' % str(r))

db.close()

<?php
$dhost=":/cloudsql/myapp-1ab28:poshdb";
$duser="root";
$dpassword="";
$database="poshdb";
$connection=mysql_connect($dhost, $duser, $dpassword) or die("Could not Connect to SQL Server Suleman");
$db=mysql_select_db($database, $connection) or die(" Check the Database Name from Config.php , wrong database entered ");
?>



def makeSQLQuery(req):
    result = req.get("result")
    parameters = result.get("parameters")
    city = parameters.get("geo-city")
    if city is None:
        return None
	ID = 101  # fetch from the JSON
    return "select balance from blnc_tbl where blnc_id = " + ID + ";"